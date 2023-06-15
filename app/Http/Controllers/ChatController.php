<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;

class ChatController extends Controller
{
    //
    public function send_message(Request $request){
        $chat = new Chat;
        $admin = User::find($request->admin_id);
        $user = User::find($request->user_id);
        if($admin->user_type_id < 3){
            $chat->admin_id = $admin->id;
            $chat->admin_email = $admin->email;
            $chat->admin_name = $admin->first_name.' '.$admin->last_name;
            $chat->resident_id = $user->id;
            $chat->resident_email = $user->email;
            $chat->resident_name = $user->first_name.' '.$user->last_name;
            $chat->content = $request->content;
            $chat->sender_is_admin = $request->sender_is_admin;
            $chat->save();
            return response()->json([
                'message' => 'Message is saved',
                'status' => 1,
            ]);
        }
        else{
            return response()->json([
                'message' => 'Admin ID is not appropriate',
                'status' => 0,
            ]);
        }
    }

    public function delete_message(Request $request){
        $chat = Chat::find($request->chat_id);
        if(is_null($chat)){
            return response()->json([
                'message' => 'Wrong chat id',
                'status' => 0,
            ]);
        }
        if($chat->sender_is_admin == 0){
            if($chat->user_id != $request->user_id){
                return response()->json([
                    'message' => 'Can not delete the message',
                    'status' => 0,
                ]);
            }
        }
        if($chat->sender_is_admin == 1){
            if($chat->admin != $request->user_id){
                return response()->json([
                    'message' => 'Can not delete the message',
                    'status' => 0,
                ]);
            }
        }
        $chat = Chat::find($request->chat_id)->delete();
        return response()->json([
            'message' => 'Message marked for deletion',
            'status' => 1,
        ]);
    }

    public function find_all_chats(Request $request){
        $user = User::find($request->user_id);
        $user_chats = [];
        $counter = 0;
        $chats = Chat::where('admin_id', '=', $user->id)->orderByDesc('created_at')->get()->unique('resident_id');
        // $chat = Chat::where('admin_id', '=', $request->user_id)->orderByDesc('resident_id')->get();
        foreach($chats as $chat){
            $cht = Chat::where('admin_id', '=', $chat->admin_id)->where('resident_id', '=', $chat->resident_id)->orderByDesc('created_at')->get();
            if(!empty($cht)){
                foreach($cht as $c){
                    if($c->sender_is_admin == 1){
                        $c['sender_id'] = $c['admin_id'];
                    }
                    else{
                        $c['sender_id'] = $c['resident_id'];
                    }
                }
                $user_chats[$counter] = $cht;
                $counter=$counter+1;
            }
        }
        $chats = Chat::where('resident_id', '=', $user->id)->orderByDesc('created_at')->get()->unique('admin_id');
        // $chat = Chat::where('admin_id', '=', $request->user_id)->orderByDesc('resident_id')->get();
        foreach($chats as $chat){
            $cht = Chat::where('admin_id', '=', $chat->admin_id)->where('resident_id', '=', $chat->resident_id)->orderByDesc('created_at')->get();
            if(!empty($cht)){
                foreach($cht as $c){
                    if($c->sender_is_admin == 1){
                        $c['sender_id'] = $c['admin_id'];
                    }
                    else{
                        $c['sender_id'] = $c['resident_id'];
                    }
                }
                $user_chats[$counter] = $cht;
                $counter=$counter+1;
            }
        }
        return response()->json([
            'chats' => $user_chats,
            'message' => 'Messages',
            'status' => 1,
        ]);
    }
}
