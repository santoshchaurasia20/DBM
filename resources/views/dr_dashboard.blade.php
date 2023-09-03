@extends('layout')
  @section('style')
  <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet"/>
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">  --}}
  <link rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.css" />

  @endsection
@section('content')
<main class="login-form">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">{{ __('Dashboard') }}</div>
                        <div class="col-md-4"> 
                             <div class="col-lg-12">
                                <div class="form-group date" id='datepicker'>
                                    <input type='text' class="form-control" name="appointment_date" id='appointment_date' />
                                    <span class="input-group-addon form-control-icon ">
                                        <i class="fa fa-calendar"></i>
                                      
                                    </span>
                                  </div>
                        </div></div>
                        <div class="col-md-4">
                            {{-- <a href="{{url('/book_appointment')}}" class="btn btn-primary float-right">Book Appointment</a> --}}
                        </div>
                    </div>
                    
                  
                  
                    
                </div>
  
                <div class="card-body">
                   
  
                    <table id="appointment_list" class="display table table-bordered dt-responsive" style="width:100%">
                        <input type="hidden" name="user_id" value="{{Auth::user()->id}}" id="user_id">
                        <thead>
                            <tr>
                                <th>Sr. No</th>
                                <th>Patient Name</th>
                                <th>Appointment Date</th>
                                <th>Appointment Slot</th>
                                <th>Patient Status</th>
                                <th>Doctor Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                      
                    </table>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="drstatus" tabindex="-1"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-soft-info p-3">
                <h5 class="modal-title" id="exampleModalLabel">Change Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close" id="close-modal"><span class="glyphicon glyphicon-remove"></span></button>
            </div>
            <form id="status_form" autocomplete="off">
                <div class="modal-body">
                  
                    <div class="row g-3">
                        <input type="hidden" name="appointment_id" id="appointment_id" value="">
                        <input type="hidden" id="user_id" name="user_id" class="form-control" value="{{Auth::user()->id}}" />
                        <div class="col-lg-12">
                            <div>
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-control selectpicker">
                                    <option>Please Select Status</option>
                                    <option value="0">Pending</option>
                                    <option value="1">Confirmed</option>
                                    <option value="2">Reject</option>
                                    <option value="3"> Postpond</option>
                                    <option value="4">Cancel</option>
                                </select>
                            </div>
                        </div>
                      
                    </div>
               
                </div>
                <div class="modal-footer">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick="update_status()">Save</button>
           
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</main>
@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="{{asset('/js/comman.js')}}"></script>

    <script>
var APP_URL = {!! json_encode(url('/api')) !!};
$(document).ready(function(){
    $('#datepicker').datepicker({
    minDate: moment().add('d', 1).toDate(),
    clearBtn: true,
    format : 'yyyy-mm-dd',
  });
    getlist();
    $('#datepicker').on('change', function() {
        $('#appointment_list').dataTable().fnDestroy();
        getlist();
    });
});



function getlist()
{
    $('#appointment_list').DataTable({
    proccessing: true,
    serverSide: false,
    searching: true,
    bFilter: true,
    ajax: {
        url: APP_URL+"/dr_list",
        type: "POST",
        dataType: 'JSON',
        data:function(d) {
        d.user_id=$('[name=user_id]').val();
        d.range=$('[name=appointment_date]').val();
    },
        },
    columns: [
        { data: "id", render: function (data, type, row, meta) {return meta.row + meta.settings._iDisplayStart + 1;}},
        { data: 'patient_name' },
        { data: 'appointment_date' },
        { data: 'slot' },
        { data: 'patient_status_name' },
        { data: 'dr_status_name' },
        { data: 'id',render:function(data,type,row){ 
            return `<button type="button" class="btn btn-primary" onclick="changestatus(${data},${row.dr_status})"> <i class="fa fa-edit"></i></button>`
        } 
         },
    ]
});
}

function changestatus(id,status)
{
    console.log(id)
    console.log(status)
    $('#appointment_id').val(id);
    $('#status').val(status);
    $('#drstatus').modal('show');
}

function update_status()
 {
    $.ajax({
                type: "POST",
                url: APP_URL+"/updateDr_status",
                data: $('#status_form').serialize(),
                success: function(result) {
                    console.log("ajax data=", result)
                    if(result.success==true)
                    {
                        toast_success(result.message)
                        location.reload();
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