<?php

namespace App\Http\Controllers\Admin;

use App\Shop;
use App\Discount;
use App\Language;
use App\Address;
use App\DiscountTranslate;
use App\AddressTranslate;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class DiscountsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 1);
        $search = $request->input('search', false);
        $limit = $request->input('limit', 50);
        $locale = env('APP_LOCALE', 'ua');

        $discounts = DiscountTranslate::searchDiscounts($locale, $search, $status);

        return view('backend.discounts.index', [
            'discounts'         => $discounts->paginate($limit),
            'app_locale'        => env('APP_LOCALE', 'ua'),
            'count_on'          => count(Discount::where('status', 1)->get()),
            'count_off'         => count(Discount::where('status', 0)->get()),
            'status'            => $status,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $discount = new Discount;

        return view('backend.discounts.create', [
            'discount'     => $discount->forAdmin()['discount'],
            'contents' => $discount->forAdmin()['contents'],
            'languages' => Language::where('status', '1')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->image);
        $request->validate([
            'slug' => 'required|unique:pages|max:255',
            'import_file' => 'required',
            'image' => 'required'
        ]);


        $discount = Discount::create($request->all());
        $image = $request->file('image')->store('uploads/'.$discount->id, 'public');
        $discount->image = $image;
        $discount->save();
        $languages = Language::where('status', '1')->get();
        $discount->categories()->attach($request->category);

        foreach ($languages as $lang) {

            $locale = $lang->locale;

            $discount_translate = new DiscountTranslate();
            $discount_translate->discount_id = $discount->id;
            $discount_translate->locale = $locale;
            $discount_translate->title = $request->$locale['title'];
            $discount_translate->save();
        }

        $filename = fopen($request->file('import_file'), "r");

        $i = 0;
        $city_arr = array();
        while (($data = fgetcsv($filename, 1000, ";")) !== FALSE) {
            if($i != 0){
                $addres = new Address();
                if (!in_array($data[0], $city_arr)){
                    DB::table('city_discount')->insert(['city_id' => $data[0], 'discount_id' => $discount->id]);
                    array_push($city_arr, $data[0]);
                }

                $addres->city_id = $data[0];
                $addres->latitude = $data[3];
                $addres->longitude = $data[4];
                $addres->save();
                $addres->discounts()->attach($discount->id);

                foreach ($languages as $lang) {

                    $locale = $lang->locale;

                    $address_translate = new AddressTranslate();
                    $address_translate->address_id = $addres->id;
                    $address_translate->locale = $locale;
                    $address_translate->title = $data[1];
                    $address_translate->save();

                }
            }
            $i++;
        }
        fclose($filename);


        return redirect()
            ->route('admin.discounts.edit', $discount->id)->with('success', 'Discount add' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('backend.language.show', [
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $discount = Discount::find($id)->forAdmin();

        if ($discount) {
            return view('backend.discounts.edit', [
                'discount'     => $discount['discount'],
                'contents' => $discount['contents'],
                'languages' => Language::where('status', '1')->get()
            ]);
        }

        return redirect()->route('admin.discounts.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $discount = Discount::find($id);

        if ($discount) {
            $request->validate([
                'slug' => Rule::unique('discounts')->ignore($discount->id),
                'slug' => 'required|max:255',
//                'import_file' => 'required',
//                'image' => 'required'
            ]);

            $discount->fill($request->all())->save();
            $discount->categories()->sync($request->category);

            if ($request->file('image')){
                $image = $request->file('image')->store('uploads/'.$discount->id, 'public');
                $discount->image = $image;
                $discount->save();
            }


            $languages = Language::where('status', '1')->get();

            foreach ($languages as $lang) {

                $locale = $lang->locale;

                $discount_translate = DiscountTranslate::where('discount_id', $discount->id)->where('locale', $lang->locale)->first();

                if (!$discount_translate) {
                    $discount_translate = new DiscountTranslate();
                    $discount_translate->discount_id = $discount->id;
                }
                $discount_translate->locale = $locale;
                $discount_translate->title = $request->$locale['title'];
                $discount_translate->save();
            }
            if ($request->file('import_file')) {

                $old_addresses = DB::table('address_discount')->where('discount_id', $discount->id)->pluck('address_id');
                DB::table('addresses')->whereIn('id', $old_addresses)->delete();
                DB::table('addresses_translations')->whereIn('address_id', $old_addresses)->delete();
                DB::table('city_discount')->where('discount_id', $discount->id)->delete();
                DB::table('address_discount')->where('discount_id', $discount->id)->delete();


                $filename = fopen($request->file('import_file'), "r");

                $i = 0;
                $city_arr = array();
                while (($data = fgetcsv($filename, 1000, ",")) !== FALSE) {
                    if($i != 0){
                        $addres = new Address();
                        if (!in_array($data[0], $city_arr)){
                            DB::table('city_discount')->insert(['city_id' => $data[0], 'discount_id' => $discount->id]);
                            array_push($city_arr, $data[0]);
                        }

                        $addres->city_id = $data[0];
                        $addres->latitude = $data[3];
                        $addres->longitude = $data[4];
                        $addres->save();
                        $addres->discounts()->sync($discount->id);
                        foreach ($languages as $lang) {

                            $locale = $lang->locale;

                            $address_translate = new AddressTranslate();
                            $address_translate->address_id = $addres->id;
                            $address_translate->locale = $locale;
                            $address_translate->title = $data[1];
                            $address_translate->save();

                        }
                    }
                    $i++;
                }
                fclose($filename);
            }

            return redirect()
                ->route('admin.discounts.edit', $discount->id)
                ->with('success', 'Discount update');
        }

        return redirect()->route('admin.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Discount $discount)
    {
        $old_addresses = DB::table('address_discount')->where('discount_id', $discount->id)->pluck('address_id');
        DB::table('addresses')->whereIn('id', $old_addresses)->delete();
        DB::table('addresses_translations')->whereIn('address_id', $old_addresses)->delete();
        DB::table('city_discount')->where('discount_id', $discount->id)->delete();
        DB::table('category_discount')->where('discount_id', $discount->id)->delete();
        DB::table('address_discount')->where('discount_id', $discount->id)->delete();
        DB::table('discounts_translations')->where('discount_id', $discount->id)->delete();
        $discount->delete();
        return response()->json('success', 200);
    }

    /**
     * Validate request form.
     *
     * @param Request $request
     */
    protected function validateForm(Request $request)
    {

        $this->validate($request, [
            'title'             => 'required|max:255',
        ]);

    }

}