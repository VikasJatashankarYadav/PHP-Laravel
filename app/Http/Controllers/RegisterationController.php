<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Models\ResidentServices;
use App\Models\InspectorServices;
use App\Models\ServiceUsed;
use App\Models\Chat;
use App\Models\MoveOut;
use App\Models\Business;
use App\Models\Counties;
use App\Models\Education;
use App\Models\Transport;

class RegisterationController extends Controller
{
    public function create(Request $request){
        $verify_email = 0;
        // return $request;
        $user = new User;
        $user->email = $request->email;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->password = Crypt::encryptString($request->password);
        $user->verification_code = rand(1111,9999);
        $user->dob = $request->dob;
        $user->place_of_birth = $request->place_of_birth;
        if($verify_email){
            $user->verified = 0;
        }
        else{
            $user->verified = 1;
        }
        $user->user_type_id = 3;
        $user->save();
        $resident_service = ResidentServices::get();
        foreach($resident_service as $service){
            $service_used = new ServiceUsed;
            $service_used->user_id = $user->id;
            $service_used->resident_service_id = $service->id;
            $service_used->resident_service_name = $service->name;
            $service_used->value = 0;
            $service_used->save();
        }
        $url = config('info.url').'/user/verify/email/'.$user->id.'/'.$user->verification_code;
        // return $url;
        $data = [
            'name' => $user->first_name,
            'url' => $url
        ];
        Mail::to($user->email)->send(new VerifyEmail($data));
        return response()->json([
            'message' => 'User Created Successfully',
            'status' => 2,
            'success' => 1,
            'user' => $user,
        ]);
    }

    public function login(Request $request){
        $user = User::where('email', $request->email)->first();
        // return $user;
        $password = Crypt::encryptString($request->password);
        if(is_null($user)){
            return response()->json([
                'message' => 'Wrong Email',
                'status' => 0,
            ]);
        }
        if($user->verified == 0){
            return response()->json([
                'message' => 'User not verified',
                'user' => $user,
                'status' => 1,
            ]);
        }
        if( $password = $user->password){
            // move out
            // $move_out = MoveOut::where('user_id', '=', $user->id)->get();
            // if(!empty($move_out)){
            //     return response()->json([
            //         'message' => 'Resident has move out',
            //         'status' => 0,
            //     ]);
            // }

            $user_chats = [];
            $counter = 0;
            $chats = Chat::where('admin_id', '=', $user->id)->orderByDesc('created_at')->get()->unique('resident_id');
            foreach($chats as $chat){
                $cht = Chat::where('admin_id', '=', $chat->admin_id)->where('resident_id', '=', $chat->resident_id)->orderByDesc('created_at')->get();
                if(!empty($cht)){
                    // return $cht;
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

            if($user->user_type_id == 3){
                // $service_used = ServiceUsed::where('user_id', '=', $user->id)->get();
                $service_used = [];
                $unique_services = ResidentServices::get()->unique('category');
                foreach($unique_services as $us){
                    $service = ResidentServices::where('category', '=', $us->category)->get();
                    foreach($service as $s){
                        $service_status = ServiceUsed::where('user_id', '=', $user->id)->where('resident_service_id', '=', $s->id)->first();
                        // return $service_status;
                        $s['status'] = $service_status->value;
                    }
                    $service_used[$us->category] = $service;
                }
                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user,
                    'services_used' => $service_used,
                    'chats' => $user_chats,
                    'status' => 1,
                ]);
            }
            if($user->user_type_id == 2){
                // $service_used = ServiceUsed::where('user_id', '=', $user->id)->get();
                $service_used = [];
                $unique_services = ResidentServices::get()->unique('category');
                foreach($unique_services as $us){
                    $service = ResidentServices::where('category', '=', $us->category)->get();
                    foreach($service as $s){
                        $service_status = ServiceUsed::where('user_id', '=', $user->id)->where('resident_service_id', '=', $s->id)->first();
                        $s['status'] = $service_status->value;
                    }
                    $service_used[$us->category] = $service;
                }

                $inspector_services = InspectorServices::where('user_id', '=', $user->id)->get();
                foreach($inspector_services as $service){
                    $resident_service = ResidentServices::find($service->resident_service_id);
                    $service['resident_service'] = $resident_service;
                }

                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user,
                    'services_used' => $service_used,
                    'inspector_services' => $inspector_services,
                    'chats' => $user_chats,
                    'status' => 1,
                ]);
            }
            if($user->user_type_id == 1){
                $resident = User::where('user_type_id', '=', 3)->get();
                $inspector = User::where('user_type_id', '=', 2)->get();
                // $resident_service = ResidentServices::get();
                $resident_service = [];
                $unique_services = ResidentServices::get()->unique('category');
                foreach($unique_services as $us){
                    $service = ResidentServices::where('category', '=', $us->category)->get();
                    $resident_service[$us->category] = $service;
                }


                $transport = Transport::get();
                $education = Education::get();
                $counties = Counties::get();
                $business = Business::get();

                return response()->json([
                    'message' => '',
                    'user' => $user,
                    'resident' => $resident,
                    'inspector' => $inspector,
                    'service' => $resident_service,
                    'chats' => $user_chats,
                    'transport' => $transport,
                    'education' => $education,
                    'counties' => $counties,
                    'business' => $business,
                    'status' => 1,
                ]);
            }
        }
        return response()->json([
            'message' => 'Wrong Password',
            'status' => 0,
        ]);
    }

    public function verify($user_id, $token){
        $user = User::find($user_id);
        if($user->verification_code == $token){
            $user->verified = 1;
            $user->save();
            // dd($user)
            return view('verification_successful');    
        }
        
    }

    public function resend_email(Request $request){
        $user = User::find($request->user_id);
        if($user->verified == 1){
            return response()->json([
                'message' => 'User already verified',
                'status' => 0,
                'user' => $user,
            ]);
        }
        $url = config('info.url').'/user/verify/email/'.$user->id.'/'.$user->verification_code;
        $data = [
            'name' => $user->first_name,
            'url' => $url
        ];
        Mail::to($user->email)->send(new VerifyEmail($data));
        return response()->json([
            'message' => 'Verification mail is sent successfully',
            'status' => 1,
            'user' => $user,
        ]);
    }
}
