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
        $checkFriend = $authUser->friends()->where('friend_id', $user->id)->exists();

        if($checkFriend){
            auth()->user()->friends()->updateExistingPivot($user, [
                'status' => true,
            ]);

            return $this->successResponse(message: 'Đồng ý kết bạn thành công!');
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
        auth()->user()->friends()->detach($user);

        return $this->successResponse(message: 'Từ chối kết bạn thành công!');
    }
}
