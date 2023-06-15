<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MoveOut;
use Illuminate\Support\Carbon;

class MoveOutController extends Controller
{
    public function move_out_request(Request $request){
        $admin = User::find($request->updated_by);
        $user = User::find($request->user_id);
        if($admin->id == $user->id or $admin->user_type_id == 1){
            $move_out = new MoveOut;
            $move_out->user_id = $user->id;
            $move_out->user_first_name = $user->first_name;
            $move_out->user_last_name = $user->last_name;
            $move_out->updated_by = $admin->id;
            $move_out->date = $request->date;
            $move_out->save();
            
            return response()->json([
                'message' => 'Resident logged to move out',
                'status' => 1,
            ]);
        }
        return response()->json([
            'message' => 'Permission denied',
            'status' => 0,
        ]);
    }
}
