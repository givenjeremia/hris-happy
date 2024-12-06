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
        Schema::create('income_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            $table->foreignId('income_id')->nullable()->constrained('incomes');

            // "TUNJANGAN", "LEMBUR", "POTONGAN", "GAJI POKOK
            $table->text('category');

            // "IN" , "OUT
            $table->text('type');

            $table->text('desc');

            $table->double('nominal');

            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income_details');
    }
};
