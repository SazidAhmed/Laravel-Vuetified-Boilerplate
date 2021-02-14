<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Blog;

class BlogController extends Controller
{
    
    public function index()
    {
        $blogs = Blog::latest()->get();
        return $blogs;
    }

    //single data from Blog
    public function show($id)
    {
        return Blog::find($id);
    }

    public function store(Request $request)
    {

        $success = Blog::create([
            'title'=>$request->title,
            'body'=>$request->body,
        ]);
        if($success){
            return response()->json($this->index());
        }

    }
    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);
        $blog->title = $request->title;
        $blog->body = $request->body;
        $success = $blog->save();
        if($success){
            return response()->json($this->index());
        }
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);
        $blog->delete();
        return response()->json($this->index());
    }
}
