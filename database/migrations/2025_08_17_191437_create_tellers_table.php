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
        Schema::create('tellers', function (Blueprint $table) {
            $table->string('id', 15)->primary();
            $table->integer('number')->unsigned();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->integer('today_transaction_nr')->default(0);
            $table->date('last_reset_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tellers');
    }
};
