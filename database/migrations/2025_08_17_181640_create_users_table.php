<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id', 15)->primary();
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('token_key', 60)->unique();
            $table->string('fullname');
            $table->string('avatar')->nullable();
            $table->enum('role', ['manager', 'admin', 'teller', 'treasurer'])->nullable();
            $table->boolean('email_visibility')->default(false);
            $table->boolean('verified')->default(false);
            $table->string('teller_id', 15)->nullable();
            $table->timestamps();

            $table->foreign('teller_id')->references('id')->on('tellers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
