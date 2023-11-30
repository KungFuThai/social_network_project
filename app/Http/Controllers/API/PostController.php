<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Http\Traits\ImageStorageTrait;
use App\Http\Traits\ResponseTrait;
use App\Models\Post;

class PostController extends Controller
{
    use ResponseTrait;
    use ImageStorageTrait;

    public function store(StoreRequest $request)
    {
        $userId = auth()->user()->id;
        $path = null;
        if($request->has('image')){
            $path = $this->storeImage('posts', $request->file('image'));
        }

        $postAttributes = $request->validated();
        $postAttributes['image'] = $path;
        $postAttributes['user_id'] = $userId;
        Post::query()->create($postAttributes);

        return $this->successResponse(message:  'Tạo bài đăng thành công');
    }

    public function edit(Post $post)
    {
        if($post->user_id === auth()->user()->id){
            return $this->successResponse($post);
        }
        return $this->errorResponse('Bài đăng này không phải của bạn!');
    }
    public function update(UpdateRequest $request, $postId)
    {
        $postAttributes = $request->validated();
        $post = Post::query()->find($postId);
        if ($post->user_id !== auth()->user()->id){
            return $this->errorResponse('Bài đăng này không phải của bạn!');
        }

        if ($request->hasFile('image')) {
            $path = $this->storeImage('posts', $request->file('image'));

            $postAttributes['image'] = $path;

            $this->deleteImage($post->image);
        }

        $post->update($postAttributes);

        return $this->successResponse(message:  'Cập nhật bài đăng thành công!');
    }

    public function destroy(Post $post)
    {
        $this->deleteImage($post->image);

        $post->delete();

        return $this->successResponse(message: 'Xoá thành công!');
    }
}
