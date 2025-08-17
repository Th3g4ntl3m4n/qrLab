<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('generated_qrs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('url');
            $table->string('file_png');
            $table->string('file_svg');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('generated_qrs');
    }
};
