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
        Schema::create('transactions', function (Blueprint $table) {
            $table->string('id', 15)->primary();
            $table->string('client_name')->nullable();
            $table->string('client_code')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->enum('type', ['poupança', 'depósito', 'levantamento', 'desembolso', 'ajuste']);
            $table->string('user_id', 15)->nullable();
            $table->string('teller_id', 15)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('teller_id')->references('id')->on('tellers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
