<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Http\Traits\ImageStorageTrait;
use App\Http\Traits\ResponseTrait;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use ResponseTrait;
    use ImageStorageTrait;

    public function store(StoreRequest $request)
    {
        $userId = auth()->user()->id;
        $path = null;
        if ($request->has('image')) {
            $path = $this->storeImage('posts', $request->file('image'));
        }

        $postAttributes = $request->validated();
        $postAttributes['image'] = $path;
        $postAttributes['user_id'] = $userId;

        $post = Post::query()->create($postAttributes);

        if((int)$postAttributes['status'] !== 0){
            $data = Post::query()
                ->where('id', $post->id)
                ->withCount('likes')
                ->withCount('dislikes')
                ->withCount('comments')
                ->with('user:id,last_name,first_name,avatar')
                ->with('reactions')
                ->get();

            return $this->successResponse($data ,'Tạo bài đăng thành công!');
        }

        return $this->successResponse($post, 'Tạo bài đăng thành công!');
    }

    public function edit(Post $post)
    {
        if ($post->user_id === auth()->user()->id) {
            return $this->successResponse($post);
        }

        return $this->errorResponse('Bài đăng này không phải của bạn!');
    }

    public function update(UpdateRequest $request, $postId)
    {
        $postAttributes = $request->validated();
        $post = Post::query()->find($postId);

        if ($post->user_id !== auth()->user()->id) {
            return $this->errorResponse('Bài đăng này không phải của bạn!');
        }

        if ($request->hasFile('image')) {
            $path = $this->storeImage('posts', $request->file('image'));

            $postAttributes['image'] = $path;

            $this->deleteImage($post->image);
        }

        $post->update($postAttributes);
        if((int)$postAttributes['status'] !== 0){
            $data = Post::query()
                ->where('id', $post->id)
                ->withCount('likes')
                ->withCount('dislikes')
                ->withCount('comments')
                ->with('user:id,last_name,first_name,avatar')
                ->with('reactions')
                ->get();

            return $this->successResponse($data ,'Cập nhật bài đăng thành công!');
        }

        return $this->successResponse($post, 'Cập nhật bài đăng thành công!');
    }
    public function show(Post $post){
        $data = Post::query()
            ->where('id', $post->id)
            ->withCount('likes')
            ->withCount('dislikes')
            ->withCount('comments')
            ->with('user:id,last_name,first_name,avatar')
            ->with('reactions')
            ->get();

        return $this->successResponse($data);
    }

    public function destroy(Post $post)
    {
        if ($post->user_id === auth()->user()->id) {
            $post->delete();

            return $this->successResponse(message: 'Xoá thành công!');
        }

        return $this->errorResponse('Đây là bài đăng của người khác');
    }

    public function updatePostStatus(Request $request,$postId){
        $post = Post::query()->find($postId);
        if ($post) {
            $post->update([
                'status' => $request->status
            ]);
            if((int)$request->status === 1){
                return $this->successResponse(message: 'Hiện bài viết thành công!');
            }

            return $this->successResponse(message: 'Ẩn bài viết thành công!');
        }

        return $this->errorResponse('Không tìm thấy bài viết');
    }
}
