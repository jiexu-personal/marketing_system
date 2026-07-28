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
    Schema::table('marketing_activities', function (Blueprint $table) {
        $table->string('approval_status')->default('Pending'); // Pending / Approved
        $table->string('approved_by')->nullable();             // 审批人名字
        $table->timestamp('approved_at')->nullable();          // 审批时间
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('marketing_activities', function (Blueprint $table) {
            //
        });
    }
};
