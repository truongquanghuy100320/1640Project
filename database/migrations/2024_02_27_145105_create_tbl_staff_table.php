<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_staff', function (Blueprint $table) {
            $table->increments('staff_id');
            $table->string('staffname', 255)->nullable();
            $table->string('staffemail', 255)->nullable();
            $table->string('imges', 255)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();


            Schema::table('tbl_users', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('tbl_users');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_staff');
    }
};
