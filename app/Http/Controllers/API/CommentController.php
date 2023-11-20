<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreRequest;
use App\Http\Requests\Comment\UpdateRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $comments = Comment::query()
            ->where('post_id', $request->get('id'))
            ->get();

        return response()->json([
            'success' => true,
            'message' => '',
            'data'    => $comments
        ]);
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

            return response()->json([
                'success' => true,
                'message' => 'Bình luận thành công!',
                'data'    => []
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Bài đăng đã được xoá hoặc không tồn tại!',
            'data'    => []
        ]);
    }

    public function edit(Comment $comment)
    {
        $userId = auth()->user()->id;
        if($userId === $comment->user_id){
            return response()->json([
                'success' => true,
                'message' => '',
                'data' => $comment
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Bình luận này không phải của bạn!',
            'data'    => []
        ]);
    }

    public function update(UpdateRequest $request, $commentId)
    {
        $userId = auth()->user()->id;
        $comment = Comment::query()->find($commentId);
        
        if($userId === $comment->user_id){
            $data = $comment->update([
                'comment' => $request->get('comment'),
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật bình luận thành công!',
                'data' => $data
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Bình luận này không phải của bạn',
            'data'    => []
        ]);
    }

    public function destroy(Comment $comment)
    {
        $userId = auth()->user()->id;
        // thiếu kiểm  tra  cho  phép người chủ bài đăng  xoá comment
        if($userId === $comment->user_id){
            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xoá bình luận thành công!',
                'data' => []
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đây là bình luận của người khác!',
            'data' => []
        ]);
    }
}
