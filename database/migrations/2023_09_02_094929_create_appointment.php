<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment', function (Blueprint $table) {
            $table->id();
            $table->string('patient_id');
            $table->string('dr_id');
            $table->date('appointment_date');
            $table->string('slot');
            $table->enum('patient_status', [0, 1, 2,3,4])->default(0)->comment('0-Pending,1-Approved,2-Reject,3-Postpond,4-Cancel');
            $table->enum('dr_status', [0,1,2,3,4])->default(0)->comment('0-Pending,1-Approved,2-Reject,3-Postpond,4-Cancel');
            $table->string('isdelete')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment');
    }
};
