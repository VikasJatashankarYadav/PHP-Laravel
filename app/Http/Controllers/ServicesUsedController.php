<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ResidentServices;
use App\Models\InspectorServices;
use App\Models\ServiceUsed;
use Illuminate\Http\Request;

class ServicesUsedController extends Controller
{
    //
    public function update(Request $request){
        $user = User::find($request->user_id);
        if($user->user_type_id == 1){
            return response()->json([
                'message' => 'Admin can not enrol into a service',
                'status' => 0,
                'user' => $user,
            ]);
        }
        
        if($request->value>=0 && $request->value<=1){
            $service = ServiceUsed::where('user_id', '=', $request->user_id)
                                    ->where('resident_service_id', '=', $request->resident_service_id)
                                    ->first();
            $service->value = $request->value;
            $service->save();
            return response()->json([
                'message' => 'Users request is logged',
                'status' => 1,
            ]);
        }
        return response()->json([
            'message' => 'Invalid request',
            'status' => 0,
        ]);
        
    }
}
