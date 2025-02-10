<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id()->index();
            $table->string('name', 200);
            $table->text('description');
            $table->foreignId('manager_id')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();

            $table->unique(['name', 'start_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
