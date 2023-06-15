<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ResidentServices;
use App\Models\InspectorServices;
use App\Models\ServiceUsed;

class InspectorServicesController extends Controller
{
    //
    public function create(Request $request){
        $user = User::find($request->user_id);
        if($user->user_type_id == 1){
            $inspector = User::find($request->resident_id);
            if($inspector->user_type_id > 2){
                $inspector->user_type_id = 2;
                $inspector->save();
            }
            $service = InspectorServices::where('user_id', '=', $request->resident_id)
                                        ->where('resident_service_id', '=', $request->resident_service_id)
                                        ->get();
            if($service->count()>0){
                return response()->json([
                    'message' => 'Service already assigned for inspector',
                    'status' => 0,
                ]);
            }
            $inspector_service = new InspectorServices;
            $inspector_service->user_id = $request->resident_id;
            $inspector_service->resident_service_id = $request->resident_service_id;
            $inspector_service->added_by = $request->user_id;
            $inspector_service->save();
            return response()->json([
                'message' => 'Service added for inspector successfully',
                'status' => 1,
            ]);
        }
        return response()->json([
            'message' => 'Only admin can add services for an inspector',
            'status' => 0,
        ]);
    }
}
