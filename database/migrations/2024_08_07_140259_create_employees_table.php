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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('posision_id')->nullable()->constrained('positions');
            $table->text('nik');
            $table->text('full_name');
            $table->date('date_of_birth');
            $table->text('address');
            $table->text('bank_account_name');
            $table->text('bank_account_number');
            $table->text('phone_number');
            $table->text('code_ptkp')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
