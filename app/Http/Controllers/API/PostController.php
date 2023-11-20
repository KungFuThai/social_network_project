<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $posts
        ]);
    }

    public function store(StoreRequest $request)
    {
        $userId = auth()->user()->id;
        $path = Storage::disk('public')->putFile('posts', $request->file('image'));
        $arr = $request->validated();
        $arr['image'] = $path;
        $arr['user_id'] = $userId;
        Post::query()->create($arr);

        return response()->json([
            'success' => true,
            'message' => 'Tạo bài đăng thành công',
            'data' => []
        ]);
    }

    public function edit(Post $post)
    {
        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $post
        ]);
    }
    public function update(StoreRequest $request, $postId)
    {
        $arr = $request->validated();
        $post = Post::query()->find($postId);

        if ($request->hasFile('image')) {
            $path = Storage::disk('public')->putFile('posts', $request->file('image'));

            $arr['image'] = $path;

            if (File::exists(public_path('storage/' . $post->image))) {
                File::delete(public_path('storage/' . $post->image));
            }
        }

        $post->update($arr);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật bài đăng thành công',
            'data' => []
        ]);
    }

    public function destroy(Post $post)
    {
        if (File::exists(public_path('storage/' . $post->image))) {
            File::delete(public_path('storage/' . $post->image));
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xoá thành công',
            'data' => []
        ]);
    }
}
