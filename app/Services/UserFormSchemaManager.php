<?php

namespace App\Services;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class UserFormSchemaManager
{
    /**
     * Create the users table: register/login system.
     */
    public static function createUsersTable()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Create the forms table: stores each user's forms.
     */
    public static function createFormsTable()
    {
        if (!Schema::hasTable('forms')) {
            Schema::create('forms', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id'); // owner
                $table->string('title');
                $table->text('description')->nullable();
                $table->json('header')->nullable();
                $table->json('footer')->nullable();
                $table->json('fields')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Drop the users table (careful: deletes all users).
     */
    public static function dropUsersTable()
    {
        Schema::dropIfExists('users');
    }

    /**
     * Drop the forms table (careful: deletes all forms).
     */
    public static function dropFormsTable()
    {
        Schema::dropIfExists('forms');
    }
}
