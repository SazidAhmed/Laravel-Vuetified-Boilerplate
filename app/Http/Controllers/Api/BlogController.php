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
        return response()->json(['blogs'=>$blogs], 200);
    }

    //single data from Blog
    public function show($id)
    {
        return Blog::find($id);
    }

    public function store(Request $request)
    {
        dd($request);

        $success = Blog::create([
            'category'=>$request->category,
            'title'=>$request->title,
            'body'=>$request->body,
            'active'=>$request->active,
            'date'=>$request->date,
            'time'=>$request->time,
            'image'=>$request->image
        ]);
        if($success){
            return response()->json(['blogs'=>$success], 200);
        }

    }
    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);
        $blog->category = $request->category;
        $blog->title = $request->title;
        $blog->body = $request->body;
        $blog->active = $request->active;
        $blog->date = $request->date;
        $blog->time = $request->time;
        $success = $blog->save();
        if($success){
            return response()->json(['blog'=>$success], 200);
        }
    }

    public function destroy($id)
    {
        $blog = Blog::find($id)->delete();
        return response()->json(['blogs'=>'Deleted'], 200);
    }
}
