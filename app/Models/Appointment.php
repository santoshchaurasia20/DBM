<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $table = 'appointment';

    protected $fillable = [
        'patient_id',
        'dr_id',
        'appointment_date',
        'slot',
        'patient_status',
        'dr_status',
        'isdelete',
    ];

}
