<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    use ResponseTrait;

    public function getFriends()
    {
        $user = auth()->user();
        $friends = $user->allFriends()->toArray();

        return $this->successResponse($friends);
    }

    public function friendRequests()
    {
        $user = auth()->user();

        $friendRequests = $user->friendRequests()->orderBy('id', 'desc')->get();

        return $this->successResponse($friendRequests);
    }

    public function suggestFriends()
    {
        $user = auth()->user();
        $friendIds = $user->allFriends()->pluck('id')->toArray();
        $friendIds[] = $user->id;
        $suggestFriends = User::query()
            ->whereNotIn('id', $friendIds)
            ->inRandomOrder()
            ->limit(8)
            ->get();

        return $this->successResponse($suggestFriends);
    }

    public function sendFriendRequest(User $user)
    {
        $authUser = auth()->user();
        $checkFriendTo = $authUser->friends()->where('friend_id', $user->id)->exists();
        if($checkFriendTo){
            return $this->errorResponse("Đã là bạn bè với người này!");
        }

        $user->friends()->sync(auth()->id(), false);

        return $this->successResponse(message: 'Gửi lời mời kết bạn thành công!');
    }

    public function accept(User $user)
    {
        auth()->user()->friends()->updateExistingPivot($user, [
            'status' => true,
        ]);

        return $this->successResponse(message: 'Đồng ý kết bạn thành công!');
    }

    public function reject(User $user)
    {
        $checkFriendTo = auth()->user()->friends()->where('friend_id', $user->id)->exists();
//        $checkFriendFrom = $user->friends()->where('friend_id', $authUser->id)->exists();
        if($checkFriendTo){
            auth()->user()->friends()->detach($user);

            return $this->successResponse(message: 'Từ chối kết bạn thành công!');
        }

        $user->friends()->detach(auth()->user()->id);

        return $this->successResponse("Xoá kết bạn thành công!");
    }

    public function remove(User $user)
    {
        $authUser = auth()->user();

        $user->friends()->detach($authUser);

        return $this->successResponse(message: "Thu hồi kết bạn thành công!");
    }
}
