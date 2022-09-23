<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class NotificationController extends Controller
{
    public function get()
    {
        $userId = auth()->id();
        $reply = DB::table('messages')
            ->join('user_message', 'user_message.message_id', '=', 'messages.id')
            ->select('user_message.id', 'messages.message', 'messages.photo')
            ->where(function ($query) use ($userId) {
                $query->where('user_message.user_id', $userId);
                $query->where('read_at', 1);
                $query->where('until_at', NULL);
            })
            ->orWhere(function ($query) use ($userId) {
                $query->where('user_message.user_id', $userId);
                $query->whereDate('until_at', '>=', date('Y-m-d'));
            })
            ->get();

        return response()->json(['status' => 'success', 'response' => $reply]);
    }
}
