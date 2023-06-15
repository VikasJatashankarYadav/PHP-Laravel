<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Business;
use App\Models\Counties;
use App\Models\Education;
use App\Models\Transport;

class ExtraServicesController extends Controller
{
    //
    public function add_business(Request $request){
        $user = User::find($request->user_id);
        if($user->user_type_id  == 1){
            $entity = new Business;
            $entity->name = $request->name;
            $entity->discount = $request->discount;
            $entity->start_date = $request->start_date;
            $entity->end_date = $request->end_date;
            $entity->save();
            return response()->json([
                'message' => 'Business saved successfully',
                'status' => 1,
            ]);
        }
        else{
            return response()->json([
                'message' => 'User not a Admin',
                'status' => 0,
            ]);
        }
    }

    public function add_counties(Request $request){
        $user = User::find($request->user_id);
        if($user->user_type_id  == 1){
            $entity = new Counties;
            $entity->name = $request->name;
            $entity->type = $request->type;
            $entity->discount = $request->discount;
            $entity->start_date = $request->start_date;
            $entity->end_date = $request->end_date;
            $entity->save();
            return response()->json([
                'message' => 'Business saved successfully',
                'status' => 1,
            ]);
        }
        else{
            return response()->json([
                'message' => 'User not a Admin',
                'status' => 0,
            ]);
        }
    }

    public function add_education(Request $request){
        $user = User::find($request->user_id);
        if($user->user_type_id  == 1){
            $entity = new Education;
            $entity->name = $request->name;
            $entity->type = $request->type;
            $entity->discount = $request->discount;
            $entity->start_date = $request->start_date;
            $entity->end_date = $request->end_date;
            $entity->save();
            return response()->json([
                'message' => 'Business saved successfully',
                'status' => 1,
            ]);
        }
        else{
            return response()->json([
                'message' => 'User not a Admin',
                'status' => 0,
            ]);
        }
    }

    public function add_transport(Request $request){
        $user = User::find($request->user_id);
        if($user->user_type_id  == 1){
            $entity = new Transport;
            $entity->name = $request->name;
            $entity->type = $request->type;
            $entity->discount = $request->discount;
            $entity->start_date = $request->start_date;
            $entity->end_date = $request->end_date;
            $entity->save();
            return response()->json([
                'message' => 'Business saved successfully',
                'status' => 1,
            ]);
        }
        else{
            return response()->json([
                'message' => 'User not a Admin',
                'status' => 0,
            ]);
        }
    }

    public function get_extra_services(Request $request){
        $transport = Transport::get();
        $education = Education::get();
        $counties = Counties::get();
        $business = Business::get();

        return response()->json([
            'message' => '',
            'transport' => $transport,
            'education' => $education,
            'counties' => $counties,
            'business' => $business,
            'status' => 1,
        ]);
    }
}
