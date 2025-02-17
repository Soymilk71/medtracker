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
        Schema::create('meds_data', function (Blueprint $table) {
            $table->id();
            $table->boolean('meds_taken');
            $table->timestamp('last_dose');
            $table->timestamp('next_dose')->nullable();
            $table->timestamps();

    });
}

    public function down(): void
    {
       Schema::dropIfExists('meds_data');

    }
};
