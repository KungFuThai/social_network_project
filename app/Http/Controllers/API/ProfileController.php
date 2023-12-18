<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateRequest;
use App\Http\Traits\ImageStorageTrait;
use App\Http\Traits\ResponseTrait;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use ResponseTrait;
    use ImageStorageTrait;
    public function index(User $user)
    {
        $profile = [];
        $profileInfo = $user->toArray();
        $profileFriends = $user->allFriends()->toArray();
        $requestFriends = $user->requestFriends()->get();
        $friendRequests = $user->friendRequests()->get();

        if ($user->id === auth()->id()) {
            $profilePostsQuery = Post::query()
                ->where('user_id', $user->id);
        } else {
            $profilePostsQuery = Post::query()
                ->where('user_id', $user->id)
                ->where('status', true);
        }

        $profilePosts = $profilePostsQuery
            ->withCount(['likes', 'dislikes', 'comments'])
            ->with(['user:id,last_name,first_name,avatar', 'reactions'])
            ->latest()
            ->get();

        $profile['info'] = $profileInfo;
        $profile['posts'] = $profilePosts;
        $profile['friends'] = $profileFriends;
        $profile['requestFriends'] = $requestFriends;
        $profile['friendRequests'] = $friendRequests;

        return $this->successResponse($profile);
    }

    public function updateProfile(UpdateRequest $request)
    {
        $user = auth()->user();

        $userAttributes = $request->validated();

        if ($request->hasFile('avatar')) {
            $path = $this->storeImage('avatars', $request->file('avatar'));

            $userAttributes['avatar'] = $path;

            $this->deleteImage($user->avatar);
        }

        if($request->hasFile('cover_image')) {
            $path = $this->storeImage('cover_images', $request->file('cover_image'));

            $userAttributes['cover_image'] = $path;

            $this->deleteImage($user->cover_image);
        }
        $user->update($userAttributes);

        return $this->successResponse($user,'Cập nhật thông tin người dùng thành công!');
    }
}
