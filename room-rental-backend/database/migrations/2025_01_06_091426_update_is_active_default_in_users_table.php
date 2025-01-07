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

        //
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(0)->change(); // Thay đổi giá trị mặc định thành 0
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(1)->change(); // Khôi phục giá trị mặc định thành 1
        });
    }
};
