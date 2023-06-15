<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ResidentServices;
use App\Models\InspectorServices;
use App\Models\ServiceUsed;
use Illuminate\Support\Carbon;
use App\Models\MoveOut;

class UserController extends Controller
{
    public function details(Request $request){
        $admin = User::find($request->user_id);
        if($admin->user_type_id == 1){
            $resident = User::where('user_type_id', '=', 3)->get();
            $inspector = User::where('user_type_id', '=', 2)->get();

            return response()->json([
                'message' => '',
                'obj' => $obj,
                'json' => $json,
                'json2' => $json2,
                'resident' => $resident,
                'inspector' => $inspector,
                'status' => 1,
            ]);
        }
        return response()->json([
            'message' => 'Only Admin view all the users',
            'status' => 0,
        ]);
    }

    public function make_inspector(Request $request){
        $admin = User::find($request->admin_id);
        if($admin->user_type_id == 1){
            $user = User::find($request->user_id);
            $user->user_type_id = 2;
            $user->upgraded_at = Carbon::now()->format('Y-m-d H:i:s');
            $user->upgraded_by = $request->admin_id;
            $user->save();
            return response()->json([
                'message' => 'Resident upgraded to inspector',
                'user' => $user,
                'status' => 1,
            ]);
        }
        return response()->json([
            'message' => 'Only Admin can upgrade resident to inspector',
            'status' => 0,
        ]);
    }

    public function services_report(Request $request){
        $admin = User::find($request->user_id);
        if($admin->user_type_id == 1){
            $resident_service = ResidentServices::get();
            $services_report = [];
            $counter = 0;
            // return $resident_service;
            foreach($resident_service as $service){
                $obj = [];
                $obj['service_id'] = $service->id;
                $obj['service_name'] = $service->name;
                $obj['service_description'] = $service->description;
                $obj['created_at'] = $service->created_at;
                $user = User::find($service->added_by);
                $obj['created_by'] = $user;
                // $inspector_services = InspectorServices::where('resident_service_id', '=', $service->resident_service_id)
                //                                         ->get();
                // return $inspector_services;
                // $obj['services_used_by_count'] =    $inspector_services->count();
                // return $service;
                $services_user_by_count = ServiceUsed::where('resident_service_id', '=', $service->id)
                                        ->where('value', '=', 1)
                                        ->get();
                
                $obj['services_used_by_count'] =    $services_user_by_count->count();
                $services_report[$counter] = $obj;
                $counter++;
            }
            return response()->json([
                'message' => 'Services Report',
                'services_report' => $services_report,
                'status' => 1,
            ]);
        }
        return response()->json([
            'message' => 'Only Admin view service report',
            'status' => 0,
        ]);
    }

    public function user_report(Request $request){
        $admin = User::find($request->user_id);
        if($admin->user_type_id == 1){
            $users = User::where('user_type_id', '>', 1)->get();
            $user_report = [];
            $counter = 0;
            foreach($users as $user){
                $obj = [];
                $obj['resident_id'] = $user->id;
                $obj['resident_first_name'] = $user->first_name;
                $obj['resident_last_name'] = $user->last_name;
                $obj['created_at'] = $user->created_at;
                if($user->user_type_id == 2){
                    $obj['is_inspector'] = 'Yes';
                }
                elseif($user->user_type_id == 3){
                    $obj['is_inspector'] = 'No';
                }
                
                $services_names = [];
                $itr = 0;
                $services = ServiceUsed::where('user_id', '=', $user->id)
                                        ->where('value', '=', 1)
                                        ->get();
                foreach($services as $service){
                    $services_names[$itr] = $service->resident_service_name;
                    $itr++;
                }
                $obj['services_opted'] = $services_names;
                $user_report[$counter] = $obj;
                $counter++;
            }
            return response()->json([
                'message' => 'Resident Report',
                'resident_report' => $user_report,
                'status' => 1,
            ]);
        }
        return response()->json([
            'message' => 'Only Admin view resident report',
            'status' => 0,
        ]);
    }

    public function residents(Request $request){
        $admin = User::find($request->user_id);
        if($admin->user_type_id == 1){
            $users = User::where('user_type_id', '=', 3)->get();
            return response()->json([
                'message' => 'Residents',
                'residents' => $users,
                'status' => 1,
            ]);
        }
        return response()->json([
            'message' => 'Only Admin view resident report',
            'status' => 0,
        ]);
    }
}
