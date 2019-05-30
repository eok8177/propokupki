<?php

namespace App\Http\Controllers\Admin;

use App\Shop;
use App\City;
use App\Language;
use App\Address;
use App\ShopTranslate;
use App\AddressTranslate;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class ShopsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->get('search')) {
            $shops = new Shop();
            $shops->searchShops($request->get('search'), env('APP_LOCALE', 'ua'));
        } else {
            $shops = Shop::orderBy('id', 'desc');
        }
        if ($request->get('limit')) {
            $shops =  $shops->paginate($request->get('limit'));
        } else {
            $shops =  $shops->paginate(50);
        }
//        dd($shops);
        return view('backend.shops.index', [
            'shops' => $shops,
            'app_locale' => env('APP_LOCALE', 'ua'),
            'count_on' => Shop::where('status', 1)->get(),
            'count_off' => Shop::where('status', 0)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shop = new Shop;
//        dd(Language::where('status', '1')->get());

        return view('backend.shops.create', [
            'method' => 'create',
            'shop'     => $shop->forAdmin()['shop'],
            'contents' => $shop->forAdmin()['contents'],
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


        $shop = Shop::create($request->all());
        $image = $request->file('image')->store('uploads/'.$shop->id, 'public');
        $shop->image = $image;
        $shop->save();
        $languages = Language::where('status', '1')->get();
        $shop->categories()->attach($request->category);

        foreach ($languages as $lang) {

            $locale = $lang->locale;

            $shop_translate = new ShopTranslate();
            $shop_translate->shop_id = $shop->id;
            $shop_translate->locale = $locale;
            $shop_translate->title = $request->$locale['title'];
            $shop_translate->save();
        }

        $filename = fopen($request->file('import_file'), "r");

        $i = 0;
        $city_arr = array();
        while (($data = fgetcsv($filename, 1000, ";")) !== FALSE) {
            if($i != 0){
                $addres = new Address();
                if (!in_array($data[0], $city_arr)){
                    DB::table('city_shop')->insert(['city_id' => $data[0], 'shop_id' => $shop->id]);
                    array_push($city_arr, $data[0]);
                }

                $addres->city_id = $data[0];
                $addres->latitude = $data[3];
                $addres->longitude = $data[4];
                $addres->save();
                $addres->shops()->attach($shop->id);

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
            ->route('admin.shops.edit', $shop->id)->with('success', 'Shop add' );
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
        $shop = Shop::find($id)->forAdmin();

        if ($shop) {
            return view('backend.shops.edit', [
                'shop'     => $shop['shop'],
                'contents' => $shop['contents'],
                'languages' => Language::where('status', '1')->get()
            ]);
        }

        return redirect()->route('admin.shops.index');
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
        $shop = Shop::find($id);

        if ($shop) {
            $request->validate([
                'slug' => Rule::unique('shops')->ignore($shop->id),
                'slug' => 'required|max:255',
//                'import_file' => 'required',
//                'image' => 'required'
            ]);

            $shop->fill($request->all())->save();
            $shop->categories()->sync($request->category);

            if ($request->file('image')){
                $image = $request->file('image')->store('uploads/'.$shop->id, 'public');
                $shop->image = $image;
                $shop->save();
            }


            $languages = Language::where('status', '1')->get();

            foreach ($languages as $lang) {

                $locale = $lang->locale;

                $shop_translate = ShopTranslate::where('shop_id', $shop->id)->where('locale', $lang->locale)->first();

                if (!$shop_translate) {
                    $shop_translate = new ShopTranslate();
                    $shop_translate->shop_id = $shop->id;
                }
                $shop_translate->locale = $locale;
                $shop_translate->title = $request->$locale['title'];
                $shop_translate->save();
            }
            if ($request->file('import_file')) {

                $old_addresses = DB::table('address_shop')->where('shop_id', $shop->id)->pluck('address_id');
                DB::table('addresses')->whereIn('id', $old_addresses)->delete();
                DB::table('addresses_translations')->whereIn('address_id', $old_addresses)->delete();
                DB::table('city_shop')->where('shop_id', $shop->id)->delete();
                DB::table('address_shop')->where('shop_id', $shop->id)->delete();


                $filename = fopen($request->file('import_file'), "r");

                $i = 0;
                $city_arr = array();
                while (($data = fgetcsv($filename, 1000, ",")) !== FALSE) {
                    if($i != 0){
                        $addres = new Address();
                        if (!in_array($data[0], $city_arr)){
                            DB::table('city_shop')->insert(['city_id' => $data[0], 'shop_id' => $shop->id]);
                            array_push($city_arr, $data[0]);
                        }

                        $addres->city_id = $data[0];
                        $addres->latitude = $data[3];
                        $addres->longitude = $data[4];
                        $addres->save();
                        $addres->shops()->sync($shop->id);
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
                ->route('admin.shops.edit', $shop->id)
                ->with('success', 'Shop update');
        }

        return redirect()->route('admin.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Shop $shop)
    {
        $old_addresses = DB::table('address_shop')->where('shop_id', $shop->id)->pluck('address_id');
        DB::table('addresses')->whereIn('id', $old_addresses)->delete();
        DB::table('addresses_translations')->whereIn('address_id', $old_addresses)->delete();
        DB::table('city_shop')->where('shop_id', $shop->id)->delete();
        DB::table('category_shop')->where('shop_id', $shop->id)->delete();
        DB::table('address_shop')->where('shop_id', $shop->id)->delete();
        DB::table('shops_translations')->where('shop_id', $shop->id)->delete();
        $shop->delete();
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