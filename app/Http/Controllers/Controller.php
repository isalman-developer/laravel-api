<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function createPost(Request $request)
    {
        $user = User::first();

        $post = new Post();
        $post->title = 'Second Post';
        $post->user_id = $user->id;

        $user->posts()->save($post);
        echo 'Post added';
    }

    public function createComment(Request $request)
    {
        $post = Post::firstOrFail();
        $post->comments()->create([
            'description' => 'This is the testing comment'
        ]);

        echo 'Comment added';
    }

    public function showUser()
    {
        $user = User::with('posts:id,title,user_id', 'posts.comments:post_id,description')->first();
        return $user;
    }

    public function deleteUser()
    {
        DB::transaction(function () {
            $user = User::firstOrFail();
            foreach ($user->posts as $key => $post) {
                // $post->comments->each->delete(); fire the event so observer or listener will work
                $post->comments()->delete(); //it dont fire the event so no listenere or observer will work
            }

            // $user->posts->each->delete(); fire the event so observer or listener will work
            $user->posts()->delete(); //it don't fire the event so observer or listener won't work
            $user->delete();
        });
    }
}
