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
        Schema::create('transaction_denominations', function (Blueprint $table) {
            $table->string('id', 15)->primary();
            $table->string('transaction_id', 15)->nullable();
            $table->string('denomination_id', 15)->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->enum('operation', ['in', 'out'])->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('denomination_id')->references('id')->on('denominations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_denominations');
    }
};
