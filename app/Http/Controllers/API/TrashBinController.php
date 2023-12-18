<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\ImageStorageTrait;
use App\Http\Traits\ResponseTrait;
use App\Models\Post;
use Illuminate\Http\Request;

class TrashBinController extends Controller
{
    use ResponseTrait;
    use ImageStorageTrait;
    public function index()
    {
        $user = auth()->user();
        $deletedPosts = Post::query()
            ->where('user_id', $user->id)
            ->onlyTrashed()
            ->get();

        return $this->successResponse($deletedPosts);
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()
            ->find($id)
            ->restore();

        return $this->successResponse($post,'Khôi phục bài đăng thành công!');
    }

    public function destroy($id){
        $user = auth()->user();
        $deletedPost = Post::onlyTrashed()
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if($deletedPost){
            $deletedPost->forceDelete();

            $this->deleteImage($deletedPost->image);

            return $this->successResponse(message:'Xoá bài đăng thành công!');
        }

        return $this->errorResponse('Bài đăng chưa xoá hoặc đã được khôi phục!');
    }
}
