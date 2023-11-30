<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reaction\ToggleReactionRequest;
use App\Http\Traits\ResponseTrait;
use App\Models\Post;
use App\Models\Reaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    use ResponseTrait;
    public function toggleReaction(ToggleReactionRequest $request)
    {
        $postId = $request->post_id;
        $type = $request->type;
        $user = auth()->user();

        $existingReaction = Reaction::query()
            ->where('user_id', $user->id)
            ->where('post_id', $postId)
            ->first();

        if ($existingReaction) {
            if ($existingReaction->type === (int) $type) {
                $existingReaction->delete();

                return $this->successResponse(-1);
            } else {
                $existingReaction->type = $type;
                $existingReaction->save();

                return $this->successResponse($existingReaction);
            }
        } else {
            $reaction = new Reaction([
                'type'    => $type,
                'user_id' => $user->id,
                'post_id' => $postId,
            ]);
            $reaction->save();

            return $this->successResponse($reaction);
        }
    }
}
