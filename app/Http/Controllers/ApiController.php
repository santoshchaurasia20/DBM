<?php

namespace App\Http\Controllers;
use Validator, Input, Redirect,Response,Hash,Storage,DB,Mail,URL,Session; 
use App\Models\User;
use App\Models\Appointment;
use App\Models\Slot;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiController extends Controller
{
     public function getdr_list(Request $request)
     {
        $list=User::select('id','name')->where('role','1')->get();
        return Response::json(array(
            'success' => true,
            'data' => $list,
            'message'=>'Doctor List'
        ), 200); 
     }

     
     public function getslot_list(Request $request)
     {
        $data=$request->all();
        $rules = array(
        'dr_id' => 'required',
        );
       $messages = [
       'dr_id.required' => 'Please Select Doctor Name',
       ];
    $validator = Validator::make($request->all(), $rules,$messages);
      
      if ($validator->fails()) {
        return Response::json(array(
            'success' => false,
            'errors' => $validator->errors(),
            'message'=>'Please Fill All Details.'

        ), 400); 
      }
      else{
        $list=Slot::select('id','dr_id','slot')->where('dr_id',$request->dr_id)->get();
        return Response::json(array(
            'success' => true,
            'data' => $list,
            'message'=>'Doctor Slot List'
        ), 200); 
    }
     }

     public function save_appointment(Request $request)
     {
         $data=$request->all();
         $rules = array(
         'dr_id' => 'required',
         'user_id' => 'required',
         'appointment_date' => 'required',
         'slot' => 'required',
         );
        $messages = [
        'dr_id.required' => 'Please Select Doctor Name',
        'user_id.required' => 'Enter User Id',
        'appointment_date.required' => 'Please Select Appointment Date and Time',
        'slot.required' => 'Please Select Appointment Date and Time',
        ];
     $validator = Validator::make($request->all(), $rules,$messages);
       
       if ($validator->fails()) {
         return Response::json(array(
             'success' => false,
             'errors' => $validator->errors(),
             'message'=>'Please Fill All Details.'
 
         ), 400); 
       }
       else{
       $app_date=date("Y-m-d",strtotime($data['appointment_date']));
        $check=Appointment::where('dr_id',$data['dr_id'])->where('appointment_date', $app_date)->where('slot',$data['slot'])->get();
        if(count($check)==0)
        {
           $appointment= Appointment::create(['dr_id' => $data['dr_id'],'patient_id'=>$data['user_id'],'appointment_date'=>$app_date,'slot'=>$data['slot'],'patient_status'=>'1']);
            return Response::json(array(
                'success' => false,
                'data' => $appointment,
                'message'=>'Your Appointment is Booked'
            ), 200); 
        }
        else{
            return Response::json(array(
                'success' => false,
                'errors' => array('Sorry! Doctor is Already Booked for same Slot'),
                'message'=>'Sorry! Doctor is Already Booked for same Slot'
            ), 400); 
        }
             
       }
     }


     public function patient_list(Request $request)
     {
        $data=$request->all();
        $rules = array(
        'user_id' => 'required',
        );
       $messages = [
       'user_id.required' => 'Enter User Id',
       ];
    $validator = Validator::make($request->all(), $rules,$messages);
      
      if ($validator->fails()) {
        return Response::json(array(
            'success' => false,
            'errors' => $validator->errors(),
            'message'=>'Please Fill All Details.'

        ), 400); 
      }
      else{
        $data=$request->all();
        $list=Appointment::select('appointment.id','appointment_date','patient_status','slots.slot as slot','dr_status','users.name as dr_name',DB::raw('replace(replace(replace(replace(replace(patient_status, 0, "Pending"),1,"Confirmed"),2,"Reject"),3,"Postpond"),4,"Cancel")as patient_status_name'),DB::raw('replace(replace(replace(replace(replace(dr_status, 0, "Pending"),1,"Approved"),2,"Reject"),3,"Postpond"),4,"Cancel")as dr_status_name'))
        ->leftJoin('users', 'users.id', '=', 'appointment.dr_id')
        ->leftJoin('slots', 'slots.id', '=', 'appointment.slot')
        ->where('patient_id',$request->user_id);

        if(isset($data['range']))
        {
            $list=$list->where('appointment_date',$data['range']);
        }

       $list=$list->get();
       
        return Response::json(array(
            'success' => true,
            'data' => $list,
            'message'=>'Patient List'
        ), 200); 
    }
     }
 

     public function updatePatient_status(Request $request)
     {
        $data=$request->all();
         $rules = array(
         'user_id' => 'required',
         'appointment_id' => 'required',
         'status' => 'required',
         );
        $messages = [
        'appointment_id.required' => 'Select Valid ID',
        'status.required' => 'Please Select Status',
        ];
     $validator = Validator::make($request->all(), $rules,$messages);
       
       if ($validator->fails()) {
         return Response::json(array(
             'success' => false,
             'errors' => $validator->errors(),
             'message'=>'Please Fill All Details.'
 
         ), 400); 
       }
       else{
        $check=Appointment::where('patient_id',$data['user_id'])->where('id',$data['appointment_id'])->update(['patient_status'=>$data['status']]);
        return Response::json(array(
            'success' => true,
            'data' => $check,
            'message'=>'Appointment Status Change Successfully.'
        ), 200);
       }
     }


     public function dr_list(Request $request)
     {
        $data=$request->all();
        $rules = array(
        'user_id' => 'required',
        );
       $messages = [
       'user_id.required' => 'Enter User Id',
       ];
    $validator = Validator::make($request->all(), $rules,$messages);
      
      if ($validator->fails()) {
        return Response::json(array(
            'success' => false,
            'errors' => $validator->errors(),
            'message'=>'Please Fill All Details.'

        ), 400); 
      }
      else{
        $list=Appointment::select('appointment.id','appointment_date','patient_status','dr_status','slots.slot as slot','users.name as patient_name',DB::raw('replace(replace(replace(replace(replace(patient_status, 0, "Pending"),1,"Confirmed"),2,"Reject"),3,"Postpond"),4,"Cancel")as patient_status_name'),DB::raw('replace(replace(replace(replace(replace(dr_status, 0, "Pending"),1,"Approved"),2,"Reject"),3,"Postpond"),4,"Cancel")as dr_status_name'))
        ->leftJoin('users', 'users.id', '=', 'appointment.patient_id')
        ->leftJoin('slots', 'slots.id', '=', 'appointment.slot')
        ->where('appointment.dr_id',$request->user_id);

        if(isset($data['range']))
        {
            $list=$list->where('appointment_date',$data['range']);
        }

       $list=$list->get();
       
        return Response::json(array(
            'success' => true,
            'data' => $list,
            'message'=>'Doctor List'
        ), 200);
    } 
     }


     public function updateDr_status(Request $request)
     {
        $data=$request->all();
         $rules = array(
         'user_id' => 'required',
         'appointment_id' => 'required',
         'status' => 'required',
         );
        $messages = [
        'appointment_id.required' => 'Select Valid ID',
        'status.required' => 'Please Select Status',
        ];
     $validator = Validator::make($request->all(), $rules,$messages);
       
       if ($validator->fails()) {
         return Response::json(array(
             'success' => false,
             'errors' => $validator->errors(),
             'message'=>'Please Fill All Details.'
 
         ), 400); 
       }
       else{
        $check=Appointment::where('dr_id',$data['user_id'])->where('id',$data['appointment_id'])->update(['dr_status'=>$data['status']]);
        return Response::json(array(
            'success' => true,
            'data' => $check,
            'message'=>'Appointment Status Change Successfully.'
        ), 200);
       }
     }


}
