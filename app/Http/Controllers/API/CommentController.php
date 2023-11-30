<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreRequest;
use App\Http\Requests\Comment\UpdateRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use ResponseTrait;
    public function index(Post $post)
    {
        $comments = Comment::query()
            ->where('post_id', $post->id)
            ->with('user')
            ->latest()
            ->get();

        return $this->successResponse($comments);
    }

    public function store(StoreRequest $request, $postId)
    {
        if(Post::query()->where('id',$postId)->exists()){
            $userId = auth()->user()->id;
            $comment = $request->get('comment');
            Comment::query()->create([
                'comment' => $comment,
                'user_id' => $userId,
                'post_id' => $postId,
            ]);

            return $this->successResponse(message: 'Bình luận thành công!');
        }

        return $this->errorResponse(message: 'Bài đăng đã được xoá hoặc không tồn tại!');
    }

    public function edit(Comment $comment)
    {
        $userId = auth()->user()->id;

        if($userId === $comment->user_id){
            return $this->successResponse($comment);
        }

        return $this->errorResponse(message: 'Bình luận này không phải của bạn!');
    }

    public function update(UpdateRequest $request, $commentId)
    {
        $userId = auth()->user()->id;
        $comment = Comment::query()->find($commentId);

        if($userId === $comment->user_id){
            $data = $comment->update([
                'comment' => $request->get('comment'),
            ]);

            return $this->successResponse($data, 'Cập nhật bình luận thành công!');
        }

        return $this->errorResponse(message: 'Bình luận này không phải của bạn!');
    }

    public function destroy(Comment $comment)
    {
        $userId = auth()->user()->id;
        $post = Post::query()->find($comment->post_id);

        $isPostOwner = $post->user_id === $userId;
        $isCommentOwner = $userId === $comment->user_id;

        if($isCommentOwner || $isPostOwner){
            $comment->delete();

            return $this->successResponse('Xoá bình luận thành công!');
        }

        return $this->errorResponse(message: 'Bình luận này là của người khác!');
    }
}
