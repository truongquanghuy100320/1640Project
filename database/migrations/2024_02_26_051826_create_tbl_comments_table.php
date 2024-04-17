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
        Schema::create('tbl_comments', function (Blueprint $table) {
            $table->increments('comment_id'); // ID duy nhất của người dùng
            $table->text('comment');
            $table->string('image_url',525);
            $table->tinyInteger('status')->length(1);
            $table->string('user_type'); // Loại người dùng
            $table->unsignedBigInteger('user_id'); // ID duy nhất của vai trò
            $table->foreign('user_id')->references('user_id')->on('tbl_users');
            $table->unsignedBigInteger('contribution_id'); // ID duy nhất của vai trò
            $table->foreign('contribution_id')->references('contribution_id')->on('tbl_contributions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_comments');
    }
};
