<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ResidentServices;
use App\Models\InspectorServices;
use App\Models\ServiceUsed;


class ResidentServicesController extends Controller
{
    //
    public function create(Request $request){
        $user = User::find($request->user_id);
        if($user->user_type_id == 1){
            $resident_service = new ResidentServices;
            $resident_service->name = $request->name;
            $resident_service->description = $request->description;
            $resident_service->category = $request->category;
            $resident_service->type = $request->type;
            $resident_service->start_date = $request->start_date;
            $resident_service->end_date = $request->end_date;
            $resident_service->discount = $request->discount;
            $resident_service->added_by = $request->user_id;
            $resident_service->save();
            $users = User::get();
            foreach($users as $user){
                $service_used = new ServiceUsed;
                $service_used->user_id = $user->id;
                $service_used->resident_service_id = $resident_service->id;
                $service_used->resident_service_name = $resident_service->name;
                $service_used->value = 0;
                $service_used->save();
            }
            return response()->json([
                'message' => 'Service created successfully',
                'status' => 1,
                'resident_services' => $resident_service,
            ]);
        }
        return response()->json([
            'message' => 'Only admin can create services',
            'status' => 0,
            'resident_services' => '',
        ]);
        
    }

    public function edit(Request $request){
        $user = User::find($request->user_id);
        if($user->user_type_id == 1){
            $resident_service = ResidentServices::find($request->resident_service_id);
            $resident_service->name = $request->name;
            $resident_service->description = $request->description;
            $resident_service->updated_by = $request->user_id;
            $resident_service->category = $request->category;
            $resident_service->type = $request->type;
            $resident_service->start_date = $request->start_date;
            $resident_service->end_date = $request->end_date;
            $resident_service->discount = $request->discount;
            $resident_service->save();
            $services_used = ServiceUsed::where('resident_service_id', '=', $request->resident_service_id)->get();
            foreach($services_used as $services){
                $service = ServiceUsed::find($services->id);
                $service->resident_service_name = $request->name;
                $service->save();
            }
            return response()->json([
                'message' => 'Service updated successfully',
                'status' => 1,
                'resident_services' => $resident_service,
            ]);
        }
        if($user->user_type_id == 2){
            $inspector_services = InspectorServices::where('user_id', '=', $request->user_id)
                                                    ->where('resident_service_id', '=', $request->resident_service_id)
                                                    ->get();

            // return $inspector_service;
                                                    
            if(!empty($inspector_services)) {
                $resident_service = ResidentServices::find($request->resident_service_id);
                $resident_service->name = $request->name;
                $resident_service->description = $request->description;
                $resident_service->updated_by = $request->user_id;
                $resident_service->category = $request->category;
                $resident_service->type = $request->type;
                $resident_service->start_date = $request->start_date;
                $resident_service->end_date = $request->end_date;
                $resident_service->discount = $request->discount;
                $resident_service->save();
                $services_used = ServiceUsed::where('resident_service_id', '=', $request->resident_service_id)->get();
                foreach($services_used as $services){
                    $service = ServiceUsed::find($services->id);
                    $service->resident_service_name = $request->name;
                    $service->save();
                }
                return response()->json([
                    'message' => 'Service updated successfully',
                    'status' => 1,
                ]);
            }
            return response()->json([
                'message' => 'This Inspector can not update the following service',
                'status' => 0,
                'resident_services' => '',
            ]);
        }
        return response()->json([
            'message' => 'Resident can not update any services',
            'status' => 0,
            'resident_services' => '',
        ]);
    }

    public function delete(Request $request){
        // 

        $user = User::find($request->user_id);
        if($user->user_type_id == 1){
            $resident_service = ResidentServices::find($request->resident_service_id);
            $services_used = ServiceUsed::where('resident_service_id', '=', $request->resident_service_id)->get();
            foreach($services_used as $services){
                $service = ServiceUsed::find($services->id)->delete();
            }
            $resident_service = ResidentServices::find($request->resident_service_id)->delete();
            return response()->json([
                'message' => 'Service deleted',
                'status' => 1,
            ]);
        }
        if($user->user_type_id == 2){
            $inspector_services = InspectorServices::where('user_id', '=', $request->user_id)
                                                    ->where('resident_service_id', '=', $request->resident_service_id)
                                                    ->get();
                                                    
            if(!empty($inspector_services)) {
                $resident_service = ResidentServices::find($request->resident_service_id);
                $services_used = ServiceUsed::where('resident_service_id', '=', $request->resident_service_id)->get();
                foreach($services_used as $services){
                    $service = ServiceUsed::find($services->id)->delete();
                }
                $resident_service = ResidentServices::find($request->resident_service_id)->delete();
                return response()->json([
                    'message' => 'Service deleted',
                    'status' => 1,
                ]);
            }
            return response()->json([
                'message' => 'This Inspector can not delete the following service',
                'status' => 0,
            ]);
        }
        return response()->json([
            'message' => 'Resident can not delete any services',
            'status' => 0,
        ]);
    }

    public function get_all(Request $request){
        $user = User::find($request->user_id);
        if($user->user_type_id == 1){
            $resident_services = ResidentServices::get();
            return response()->json([
                'message' => '',
                'status' => 1,
                'resident_services' => $resident_services,
            ]);
        }
        if($user->user_type_id == 2){
            $services = [];
            $counter = 0;
            $inspector_services = InspectorServices::where('user_id', '=', $request->user_id)->get();
            foreach ($inspector_service as $service) {
                $service = ResidentServices::find($service->resident_service_id);
                $services[$counter] = $service;
                $counter++;
            }
            return response()->json([
                'message' => '',
                'status' => 1,
                'resident_services' => $services,
            ]);
        }
        return response()->json([
            'message' => 'Its is a resident',
            'status' => 0,
            'resident_services' => '',
        ]);
    }

    public function get_one(Request $request){

        $user = User::find($request->user_id);
        if($user->user_type_id == 1){
            $resident_service = ResidentServices::find($request->resident_service_id);
            return response()->json([
                'message' => '',
                'status' => 1,
                'resident_service' => $resident_service,
            ]);
        }
        if($user->user_type_id == 2){
            $inspector_services = InspectorServices::where('user_id', '=', $request->user_id)
                                                    ->where('resident_service_id', '=', $request->resident_service_id)
                                                    ->get();
                                                    
            if(!empty($inspector_service)) {
                $resident_service = ResidentServices::find($request->resident_service_id);
                return response()->json([
                    'message' => '',
                    'status' => 1,
                    'resident_service' => $resident_service,
                ]);
            }
            return response()->json([
                'message' => 'The service is not allocated to this Inspector',
                'status' => 0,
                'resident_service' => '',
            ]);
        }
        return response()->json([
            'message' => 'User is a resident',
            'status' => 0,
            'resident_service' => '',
        ]);
        
    }
}
