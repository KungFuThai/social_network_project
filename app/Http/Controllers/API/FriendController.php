<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function getFriends()
    {
        $user = auth()->user();
        $friends = $user->allFriends()->toArray();

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $friends
        ],200);
    }

    public function sendFriendRequest(User $user)
    {
        $user->friends()->sync(auth()->id(),false);

        return response()->json([
            'success'  => true,
            'message' => 'Gửi lời mời kết bạn thành công! Chờ người này đồng ý!',
            'data' => [],
        ], 200);
    }

    public function reject(User $user)
    {
        $user->friends()->detach(auth()->user());

        return response()->json([
            'success'  => true,
            'message' => 'Huỷ thành công!',
            'data' => [],
        ], 200);
    }

    public function accept(User $user)
    {
        $user->friends()->updateExistingPivot(auth()->user(),[
            'status' => true,
        ]);

        return response()->json([
            'success'  => true,
            'message' => 'Đồng ý kết bạn thành công!',
            'data' => [],
        ], 200);
    }
}
