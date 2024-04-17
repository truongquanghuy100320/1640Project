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
        Schema::create('tbl_users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('email', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('user_type', 255)->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->string('phone_number', 255)->nullable();
            $table->timestamps();
        });

        Schema::table('tbl_users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('tbl_roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_users');
    }
};
