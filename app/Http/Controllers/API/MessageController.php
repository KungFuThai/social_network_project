<?php

namespace App\Http\Controllers\API;

use App\Events\SendMessage;
use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    use ResponseTrait;

    public function index(Request $request, ?int $receiverId = null)
    {
        $messages = $receiverId === null ? [] : Message::query()
                ->whereIn('sender_id', [$request->user()->id, $receiverId])
                ->whereIn('receiver_id', [$receiverId, $request->user()->id])
                ->get();

        return $this->successResponse([
            'messages' => $messages,
            'userList' => $this->userList(),
            'receiver' => User::query()->find($receiverId),
        ]);
    }


    public function store(Request $request, $receiverId = null)
    {
        if ($receiverId === null) {
            return $this->errorResponse('Bạn muốn gửi tin nhắn cho ai?');
        }
        try {
            $message = Message::query()
                ->create([
                    'sender_id'   => $request->user()->id,
                    'receiver_id' => $receiverId,
                    'message'     => $request->get('message'),
                ]);
            event(new SendMessage($message));

            return $this->successResponse($message);
        } catch (\Throwable $th) {
            return $this->errorResponse(message: $th);
        }
    }

    private function userList()
    {
        //đặt lại chế độ làm việc của sql thành chuỗi rỗng vì ảnh hưởng của câu groupby
        DB::statement("SET SESSION sql_mode=''");
        $senderId = auth()->user()->id;

        $recentMessages = Message::query()
            ->where(function ($query) use ($senderId) {
            $query->where('sender_id', $senderId)
                ->orWhere('receiver_id', $senderId);
        })
            ->groupBy('sender_id', 'receiver_id')
            ->select('sender_id', 'receiver_id', 'message')
            ->orderBy('id', 'desc')
            ->limit(30)
            ->get();

        return $this->getFilterRecentMessages($recentMessages, $senderId);
    }

    private function getFilterRecentMessages(Collection $recentMessages, int $senderId): array
    {
        $recentUsersWithMessage = [];
        $usedUserIds = [];
        foreach ($recentMessages as $message) {
            $userId = $message->sender_id == $senderId ? $message->receiver_id : $message->sender_id;
            if (!in_array($userId, $usedUserIds,true)) {
                $recentUsersWithMessage[] = [
                    'user_id' => $userId,
                    'message' => $message->message
                ];
                $usedUserIds[] = $userId;
            }
        }

        foreach ($recentUsersWithMessage as $key => $userMessage) {
            $recentUsersWithMessage[$key]['info'] = User::query()
                ->where('id', $userMessage['user_id'])
                ->get(['last_name', 'first_name', 'avatar']);
        }

        return $recentUsersWithMessage;
    }
}
