<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\Comment;


class LikeController extends Controller
{
    // like or unlike a post
    public function likeOrUnlike($id){
        $post=Post::find($id);

        if(!$post)
        {   
            return response([
                'message'=>'Post Nor Found'
            ],403);
 
        }

        $like = $post->likes()->where('user_id',auth()->user()->id)->first();

        // if not liked then
        if(!$like){
            $post->likes()->create([
                'user_id' => auth()->user()->id,
            ]);
            return response([
                'message' => ' Liked ',
            ], 200);
        }
        // else dislike it
        $like->delete();

        return response([
            'message' =>'Disliked'
        ], 200);
    }
}
