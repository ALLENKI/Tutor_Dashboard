<?php

namespace Aham\Http\Controllers\Backend\Content;

use Illuminate\Http\Request;

use Aham\Http\Requests;
use Aham\Http\Controllers\Controller;

use Aham\Models\SQL\Page;

use Aham\Http\Controllers\Backend\BaseController;

use Input;
use Validator;

use Intervention\Image\ImageManagerStatic as Image;

class PagesController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::all();

        return view('backend.content.pages.index',compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $rules = [
            'name' => 'required',
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        Page::create(Input::only('name'));

        return redirect()->route('admin::content::pages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Page::find($id);

        return view('backend.content.pages.edit',compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {        
        $rules = [
            'name' => 'required',
            'slug' => 'required|unique:pages,slug,'.$id,
            'content' => 'required'
        ];

        $v = Validator::make(Input::all(), $rules);

        if ($v->fails()) {
            flash()->error('There were errors, Please check.');
            return redirect()->back()->withErrors($v->errors())->withInput();
        }

        $page = Page::find($id);

        $content = Input::get('content');
        $dom = new \DomDocument();
        @$dom->loadHtml( mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        // $dom->loadHtml($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $images = $dom->getElementsByTagName('img');

        foreach($images as $img){

            $src = $img->getAttribute('src');

            if(preg_match('/data:image/', $src)){

                preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);

                $mimetype = $groups['mime'];

                $filename = uniqid();
                $filepath = "images/".$filename.'.'.$mimetype;

                $image = Image::make($src)
                  ->encode($mimetype, 100)
                  ->save(public_path($filepath));

                $new_src = asset($filepath);
                $img->removeAttribute('src');
                $img->setAttribute('src', $new_src);
                $img->setAttribute('class', 'img-responsive');
            }
            else
            {
                $img->setAttribute('class', 'img-responsive');
            }

            $img->setAttribute('style', '');

        }

        $page->content = $dom->saveHTML();
        $page->name = Input::get('name');
        $page->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Page::destroy($id);

        return redirect()->route('admin::content::pages.index');

    }
}
