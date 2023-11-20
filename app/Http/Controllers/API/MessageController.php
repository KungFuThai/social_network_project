<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index(Request $request, ?int $receiverId = null)
    {
        $messages = $receiverId === null ? [] : Message::query()
                ->whereIn('sender_id', [$request->user()->id, $receiverId])
                ->whereIn('receiver_id', [$receiverId, $request->user()->id])
                ->get();

        return response()->json([
            'success' => true,
            'message' => '',
            'data'    => [
                'messages' => $messages,
                'userList' => $this->userList(),
            ],
        ]);
    }

    private function userList()
    {
        DB::statement("SET SESSION sql_mode=''");
        $senderId = auth()->user()->id;

        $recentMessages = Message::query()->where(function ($query) use ($senderId) {
            $query->where('sender_id', $senderId)
                ->orWhere('receiver_id', $senderId);
        })->groupBy('sender_id', 'receiver_id')
            ->select('sender_id', 'receiver_id', 'message')
            ->orderBy('id', 'desc')
            ->limit(30)
            ->get();

        return $this->getFilterRecentMessages($recentMessages, $senderId);
    }

    private function getFilterRecentMessages(Collection $recentMessages, int $senderId)
    {
        $recentUserWithMessage = [];
        $usedUserIds = [];
        foreach ($recentMessages as $message) {
            $userId = $message->sender_id === $senderId ? $message->receiver_id : $message->sender_id;
            if(! in_array([$userId, $usedUserIds], $recentUserWithMessage, true)){
                $recentUserWithMessage[]=[
                    'user_id' => $userId,
                    'message' => $message->message
                ];
                $usedUserIds = $userId;
            }
        }
        foreach($recentUserWithMessage as $key => $userMessage){
            $recentUserWithMessage[$key]['info'] = User::query()
                    ->where('id', $userMessage['user_id'])
                    ->get(['last_name', 'first_name', 'avatar']);
        }
        return $recentUserWithMessage;
    }

    public function store(Request $request, ?int $receiverId = null)
    {
        if ($receiverId === null) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn muốn gửi tin nhắn cho ai?',
                'data'    => [],
            ]);
        }

        try {
            $message = Message::query()
                ->create([
                    'sender_id'   => $request->user()->id,
                    'receiver_id' => $receiverId,
                    'message'     => $request->get('message'),
                ]);

            return response()->json([
                'success' => true,
                'message' => '',
                'data'    => $message,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi rồi!',
                'data'    => [],
            ]);
        }
    }

}
