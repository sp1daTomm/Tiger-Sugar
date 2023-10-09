<?php

namespace App\Http\Controllers;

use App\Models\BlogNews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Services\FileService;

class BlogNewsController extends Controller
{

    public function __construct(protected FileService $fileService)
    {
    }

    public function index()
    {
        $lists = BlogNews::all();
        return view('backend.blog_news.index',compact('lists'));
    }

    public function create()
    {
        return view('backend.blog_news.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'author' => 'required|max:255',
            'link' => 'required|max:255',
            'title' => 'required|max:255',
            'main_photo' => 'required',
            'sort' => 'required',
        ]);

        $BlogNews = BlogNews::create([
            'author' => $request->author,
            'link' => $request->link,
            'title' => $request->title,
            'info' => $request->info,
            'main_photo' => '',
            'date' => '2023-10-07',
            'sort' => $request->sort,
        ]);
        $img = $this->fileService->imgUpload($request->file('main_photo'), 'blog_news');
        $BlogNews->update([
            'main_photo' => $img,
        ]);
        return redirect(route('back.blog_news.index'))->with('message','新增成功!');
    }

    public function edit($id)
    {
        $list = BlogNews::find($id);
        return view('backend.blog_news.edit',compact('list'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'author' => 'required|max:255',
            'link' => 'required|max:255',
            'title' => 'required|max:255',
            'main_photo' => 'required',
            'sort' => 'required',
        ]);
        $BlogNews = BlogNews::find($id);
        $BlogNews->update([
            'author' => $request->author,
            'link' => $request->link,
            'title' => $request->title,
            'info' => $request->info,
            'sort' => $request->sort,
        ]);

        if($request->hasFile('main_photo')){
            $img = $this->fileService->imgUpload($request->file('main_photo'), 'blog_news');
            $BlogNews->update([
                'main_photo' => $img,
            ]);
        }

        return redirect(route('back.blog_news.index'))->with('message','更新成功!');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $BlogNews = BlogNews::find($id);
        $this->fileService->deleteUpload($BlogNews->main_photo);
        $BlogNews->delete();
        return 'success';
    }

    // //上傳檔案
    // public function upload_file($file){
    //     $allowed_extensions =["png", "jpg", "gif", "PNG", "JPG", "GIF","jpeg","JPEG"];
    //     if ($file->getClientOriginalExtension() &&
    //         !in_array($file->getClientOriginalExtension(), $allowed_extensions))
    //     {
    //         return redirect()->back()->with('message','僅接受.jpg, .png, .gif, .jepg格式檔案!');
    //     }
    //     $extension = $file->getClientOriginalExtension();
    //     $destinationPath = public_path() . '/summernote_upload/';
    //     $original_filename = $file->getClientOriginalName();

    //     $filename = $file->getFilename() . '.' . $extension;
    //     $url = '/summernote_upload/' . $filename;

    //     $file->move($destinationPath, $filename);

    //     return $url;
    // }

    // //刪除檔案
    // public function delete_file($path){
    //     File::delete(public_path().$path);
    // }
}
