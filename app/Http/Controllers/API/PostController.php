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
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "description" => "required",
            "image" => "sometimes|mimes:jpg,png,gif"
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => 'Validation error updating',
                "errors" => $validator->errors()->all(),
            ], 401);
        }
    
        $post = Post::find($id);
    
        if (!$post) {
            return response()->json([
                "status" => false,
                "message" => "Post not found"
            ], 404);
        }
    
        $imageName = $post->image; // Default to current image
    
        if ($request->hasFile('image')) {
            $path = public_path('/uploads');
    
            // Delete old image if exists
            if ($imageName && file_exists($path . '/' . $imageName)) {
                unlink($path . '/' . $imageName);
            }
    
            $img = $request->file('image');
            $ext = $img->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            $img->move($path, $imageName);
        }
    
        $post->update([
            "title" => $request->title,
            "description" => $request->description,
            "image" => $imageName
        ]);
    
        return response()->json([
            "status" => true,
            "message" => "Post updated successfully",
            "post" => $post
        ], 200);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $ImagePah = Post::select("image")->where("id",$id)->get();
        $filePath = public_path()."/uploads/".$ImagePah[0]["image"];

        unlink($filePath);

        $post = Post::where("id",$id)->delete();

        return response()->json([
         "status" => true,
         "message" => "Post has been removed",
         "Post" => $post

        ]);
    }
}
