<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Language;
use App\CityTranslate;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class CitiesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

//        $old_cities = DB::table('old_cities')->get();
//        foreach ($old_cities as $old_city) {
//            DB::table('cities')->insert([
//                'id' => $old_city->id,
//                'slug' => $old_city->slug,
//                'code' => $old_city->id,
//                'status' => 1,
//                'created_at' => NOW(),
//                'updated_at' => NOW(),
//            ]);
//            DB::table('cities_translations')->insert([
//                'city_id' => $old_city->id,
//                'locale' => 'ua',
//                'title' => $old_city->nameuk,
//                'created_at' => NOW(),
//                'updated_at' => NOW(),
//
//            ]);
//        }
//        dd($old_cities);
        if ($request->get('search')) {
            $cities = City::where('code', 'like', '%'. $request->search . '%')
                ->orderBy('id', 'asc');
        } else {
            $cities = City::orderBy('id', 'desc');
        }
//        dd($cities);
        return view('backend.cities.index', [
            'cities' => $cities->paginate(10),
            'app_locale' => env('APP_LOCALE', 'ua')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $city = new City;
//        dd(Language::where('status', '1')->get());

        return view('backend.cities.create', [
            'city'     => $city->forAdmin()['city'],
            'contents' => $city->forAdmin()['contents'],
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
        $request->validate([
            'slug' => 'required|unique:pages|max:255',
        ]);

        $city = City::create($request->all());
        $language = Language::where('status', '1')->get();

        foreach ($language as $lang) {

            $locale = $lang->locale;

            $city_translate = new CityTranslate();
            $city_translate->city_id = $city->id;
            $city_translate->locale = $locale;
            $city_translate->title = $request->$locale['title'];
            $city_translate->save();
        }


        return redirect()
            ->route('admin.cities.edit', $city->id)->with('success', 'City add' );
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
        $city = City::find($id)->forAdmin();

        if ($city) {
            return view('backend.cities.edit', [
                'city'     => $city['city'],
                'contents' => $city['contents'],
                'languages' => Language::where('status', '1')->get()
            ]);
        }

        return redirect()->route('admin.cities.index');
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
        $city = City::find($id);

        if ($city) {
            $request->validate([
                'slug' => Rule::unique('categories')->ignore($city->id),
                'slug' => 'required|max:255',
            ]);

            $city->fill($request->all())->save();

            $languages = Language::where('status', '1')->get();

            foreach ($languages as $lang) {

                $locale = $lang->locale;

                $city_translate = CityTranslate::where('city_id', $city->id)->where('locale', $lang->locale)->first();
                if (!$city_translate) {
                    $city_translate = new CityTranslate();
                    $city_translate->city_id = $city->id;
                }
                $city_translate->locale = $locale;
                $city_translate->title = $request->$locale['title'];
                $city_translate->save();
            }

            return redirect()
                ->route('admin.cities.edit', $city->id)
                ->with('success', 'City update');
        }

        return redirect()->route('admin.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $language)
    {
        DB::table('page_translations')->where('locale', $language['locale'])->delete();
        DB::table('post_translations')->where('locale', $language['locale'])->delete();
        DB::table('city_translations')->where('locale', $language['locale'])->delete();
        $language->delete();
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