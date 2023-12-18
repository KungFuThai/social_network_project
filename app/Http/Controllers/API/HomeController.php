<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ResponseTrait;

    public function search(Request $request)
    {
        $search = $request->get('search');

        $data = User::query()
            ->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%'.$search.'%')
                    ->orWhere('first_name', 'like', $search.'%')
                    ->orWhere('last_name', 'like', '% '.$search.'%')
                    ->orWhere('last_name', 'like', $search.'%')
                    ->orWhere('email', 'like', '% '.$search.'%')
                    ->orWhere('email', 'like', $search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', $search.'%');
            })
            ->limit(10)
            ->get();

        return $this->successResponse($data);
    }

    public function getPosts()
    {
        $posts = Post::query()
            ->where('status', true)
            ->withCount('likes')
            ->withCount('dislikes')
            ->withCount('comments')
            ->with('user:id,last_name,first_name,avatar')
            ->with('reactions')
            ->latest()
            ->get();

        return $this->successResponse($posts);
    }
}
