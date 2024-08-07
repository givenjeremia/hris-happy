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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('employee_id')->nullable()->constrained('employees');

            $table->text('latitude_in')->nullable();
            $table->text('longitude_in')->nullable();
            $table->text('latitude_out')->nullable();
            $table->text('longitude_out')->nullable();

            $table->text('office')->nullable();

            $table->time('time_in');
            $table->time('time_out');

            $table->date('date');

            $table->text('status');

            $table->text('information')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
