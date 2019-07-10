<?php

namespace App\Http\Controllers\Admin;

use App\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class LanguagesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->get('search')) {
            $language = Language::where('name', 'like', '%'. $request->search . '%')
                ->orderBy('id', 'asc');

        } else {
            $language = Language::orderBy('id', 'desc');
        }
        return view('backend.language.index', [
            'language' => $language->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.language.create', [
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
        $this->validateForm($request);

        $lang = Language::create($request->all());
        return redirect()
            ->route('admin.language.edit', $lang->id)
            ->with('success', 'Lang add' );
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
        $language = Language::find($id);

        if ($language) {
            return view('backend.language.edit', [
                'lang'   => $language,
            ]);
        }

        return redirect()->route('admin.language.index');
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
        // dd($request);
        $language = Language::find($id);

        if ($language) {
            $this->validateForm($request);

            $language->fill($request->all())->save();

            return redirect()
                ->route('admin.language.edit', $language->id)
                ->with('success', 'Категория обновлена');
        }

        return redirect()->route('admin.language.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Language $language)
    {
        DB::table('page_translations')->where('locale', $language['locale'])->delete();
        DB::table('post_translations')->where('locale', $language['locale'])->delete();
        DB::table('category_translations')->where('locale', $language['locale'])->delete();
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
            'name'             => 'required|max:255',
        ]);

    }

}