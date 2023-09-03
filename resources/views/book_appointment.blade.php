@extends('layout')
  @section('style')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">

  @endsection
@section('content')
<main class="login-form">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Book Appointment') }}

                    <a href="{{url('/dashboard')}}" class="btn btn-primary float-right">Back</a>
                </div>
  
                <div class="card-body">
                   
                    <form id="appointment">
                     <input type="hidden" name="user_id" id="user_id" value="{{Auth::user()->id}}" >
                        <div class="form-group row">
                            <label for="dr_id" class="col-md-4 col-form-label text-md-right">Select Doctor</label>
                            <div class="col-md-8">
                                <select name="dr_id" id="dr_id" class="form-control selectpicker" onchange="getslot()">
                                    <option>Please Select Doctor Name</option>
                                  
                                </select>
                            </div>
                        </div>

                        
                        <div class="form-group row">
                            <label for="appointment_date" class="col-md-4 col-form-label text-md-right">Select DateTime</label>
                            <div class='col-md-8'>
                            <div class="input-group date" id='datepicker'>
                              <input type='text' class="form-control" name="appointment_date" id='appointment_date'/>
                              <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                              </span>
                            </div>
                        </div>
                          </div>

                          <div class="form-group row">
                            <label for="slot" class="col-md-4 col-form-label text-md-right">Select Slot</label>
                            <div class="col-md-8">
                                <select name="slot" id="slot" class="form-control selectpicker">
                                    <option>Please Select Slot</option>
                                  
                                </select>
                            </div>
                        </div>

                       <button type="submit" class="btn btn-primary float-right">Submit</button>

                    </form>
  
                    
                </div>
            </div>
        </div>
    </div>
</div>
</main>
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="{{asset('/js/comman.js')}}"></script>
<script>
     var APP_URL = {!! json_encode(url('/api')) !!};
      $(document).ready(function(){
    $('#datepicker').datetimepicker({
    minDate: moment().add('d', 1).toDate(),
    format : 'YYYY-MM-DD'
  });
    getdr_list();

    $('form[id="appointment"]').validate({
    rules: {
       dr_id: 'required',
       appointment_date: 'required',
       slot: 'required',
    },
    messages: {
        dr_id: 'Please Doctor Name',
        appointment_date: 'Please Select Date',
        slot:'Please Select Slot for Appointment'
    },
    submitHandler: function(form) {
        $.ajax({
                    type: 'post',
                    url: APP_URL+"/save_appointment",
                    data: $('#appointment').serialize(),
                  
                    // crossDomain:true,
                    success: function(success) {
                        console.log("ajax data=", success)
                       toast_success(success.message)
                        window.location.href='/dashboard';
                    },
                    error: function(xhr, status, error) {
                        let errors_msg="";
                            $.each( xhr.responseJSON.errors, function( key, value ) {
                                errors_msg+=`${value}\n`;
                            });
                            console.log(errors_msg)
                            toast_error(errors_msg)
                    }
                });
    }
    });

      });

 function getdr_list(){
   $.ajax({
                type: "POST",
                url: APP_URL+"/getdr_list",
                data: {},
                success: function(result) {
                    console.log("ajax data=", result)
                    if(result.success==true)
                    {
                        var list = $("#dr_id");
                        list.empty().append(new Option('Select Doctor',''))
                        $.each(result.data, function(index, item) {
                        list.append(new Option(item.name, item.id));
                        });
                    }      
                },
                error: function(error) {
                    console.log(error)
                    // alert_error('Something Wrong')  
                 }
                });
 }

 function getslot()
 {
    var dr_id=$('#dr_id').val();
    $.ajax({
                type: "POST",
                url: APP_URL+"/getslot_list",
                data: {dr_id:dr_id},
                success: function(result) {
                    console.log("ajax data=", result)
                    if(result.success==true)
                    {
                        var list = $("#slot");
                        list.empty().append(new Option('Select Slot',''))
                        $.each(result.data, function(index, item) {
                        list.append(new Option(item.slot, item.id));
                        });
                    }      
                },
                error: function(error) {
                    console.log(error)
                    // alert_error('Something Wrong')  
                 }
                });

 }
  </script>
  @endsection

@endsection