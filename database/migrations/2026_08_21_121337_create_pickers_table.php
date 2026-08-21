<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pickers', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code', 50)->nullable()->unique();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('display_name', 50)->nullable();
            $table->string('zone_assigned', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('status', 40)->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickers');
    }
};
