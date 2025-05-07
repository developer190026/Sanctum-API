<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data["posts"] = Post::all();
        return response()->json([
            "status" => true,
            "message" => "ALL post data",
            "data" => $data
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
       
       $validatorUser = Validator::make($request->all(),
       [

        "title" => "required",
        "description" => "required",
        "image" => "required|mimes:jpg,png,gif"
       ]);
       if($validatorUser->fails()){

    return response()->json([
        "status" => false,
        "message" => 'validation error',
        "error" =>$validatorUser->errors()->all(),
    ],401);
       }
       

$img = $request->image;
$ext = $img->getClientOriginalExtension(); // entention of the image
$ImageName = time().".".$ext;

$img->move(public_path()."/uploads", $ImageName);

        $posts = Post::create([

            "title" => $request->title,
            "description" => $request->description,
           "image" => $ImageName,
        ]);

        return response()->json([
            "status" => true,
            "message" => "Post created successfully",
            "Posts" => $posts
        ],200);
       

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data["post"] = Post::select(
            "id",
            "title",
            "description",
            "image"
        )->where(["id" => $id])->get();

        return response()->json([

            "status" =>true,
            "message" => "Your single post",
            "data"=> $data
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
