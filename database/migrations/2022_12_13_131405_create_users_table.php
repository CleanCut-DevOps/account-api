<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (Schema::hasTable('users')) Schema::drop('users');

        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('username', 48)->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('contact');
            $table->text('avatar')->nullable();
            $table->enum('type', ['admin', 'cleaner', 'user'])->default('user');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
