<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;


class PostController extends Controller
{
    //get all posts
    public function index()
    {
        // $posts = Post::all();
        return response([
                'posts' => Post::orderBy('created_at','desc')->with('user:id,name,image')->withCount('comments','likes')->get()
            ]
        );
    }

    // get single post
    public function show($id)
    {
        return response([
                'post' => Post::where('id',$id)->withCount('comments','likes')->get()
            ] , 200
        );
    }
    // create a post
    public function store(Request $request)
    {
        $attrs=$request->validate([
            'body' => 'required|string',
        ]);
        $post = Post::create([
            'body' => $attrs['body'],
            'user_id' => auth()->user()->id,
        ]);


        return response([
            'message' => 'Post created successfully',
            'post' => $post
        ], 200);
    }
    
    // update a post
    public function update(Request $request, $id)
    {
        $post=Post::find($id);

        if(!$post)
        {   
            return response([
                'message'=>'Post Not Found'
            ],403);

        }

        if($post->user_id!=auth()->user()->id)
        {
            return response([
                'message'=>'You are not authorized to update this post'
            ],403);
        }

        $attrs=$request->validate([
            'body' => 'required|string',
        ]);


        $post->update([
            'body' => $attrs['body'],
        ]);

        return response([
            'message' => 'Post Updated Successfully',
            'post' => $post
        ], 200);

    }

    // delete a post
    public function destroy($id)
    {
        $post=Post::find($id);

        if(!$post)
        {   
            return response([
                'message'=>'Post Not Found'
            ],403);

        }

        if($post->$user_id!=auth()->user()->id)
        {
            return response([
                'message'=>'You are not authorized to delete this post'
            ],403);
        }
        $post->comment()->delete();
        $post->like()->delete();
        $post->delete();

        return response([
            'message' => 'Post Deleted Successfully',
        ], 200);
    }


}
