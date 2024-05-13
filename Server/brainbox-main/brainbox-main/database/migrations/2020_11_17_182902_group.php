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
    Schema::create('groups', function (Blueprint $table) {
        $table->id();
        $table->string('title')->required();
        $table->unsignedInteger('member_count')->default(0);
        $table->timestamps();
    });

    Schema::create('group_user', function (Blueprint $table) {
        $table->unsignedBigInteger('group_id');
        $table->unsignedBigInteger('user_id');
        $table->boolean('is_admin')->default(false); 
        $table->timestamps();

        $table->primary(['group_id', 'user_id']);
        $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

/**
 * Reverse the migrations.
 */
public function down(): void
{
    Schema::dropIfExists('group_user');
    Schema::dropIfExists('groups');
}

};
