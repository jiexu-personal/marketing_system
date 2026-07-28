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
        Schema::create('marketing_activities', function (Blueprint $table) {
            $table->id();
            // --- 基础信息 ---
            $table->string('outlet_name');
            $table->string('account_code')->nullable();
            $table->string('salesman');
            $table->date('apply_date');

            // --- 1) New Opening Account / Branches ---
            $table->decimal('new_opening_amount', 10, 2)->default(0);

            // --- 2) New Product Listing Fee ---
            $table->decimal('listing_fee_per_sku', 10, 2)->default(0);
            $table->integer('listing_total_sku')->default(0);
            $table->decimal('listing_by_package', 10, 2)->default(0);
            $table->decimal('listing_total_fee', 10, 2)->default(0);

            // --- 3) Rental Fee ---
            $table->string('rental_duration_from')->nullable();
            $table->string('rental_duration_to')->nullable();
            $table->decimal('rental_gondola_full', 10, 2)->default(0);
            $table->decimal('rental_gondola_half', 10, 2)->default(0);
            $table->decimal('rental_power_wing_full', 10, 2)->default(0);
            $table->decimal('rental_power_wing_half', 10, 2)->default(0);
            $table->decimal('rental_shelf_full', 10, 2)->default(0);
            $table->decimal('rental_shelf_half', 10, 2)->default(0);
            $table->decimal('rental_standee', 10, 2)->default(0);
            $table->decimal('rental_block_island', 10, 2)->default(0);

            // --- 4) Price Solve ---
            $table->string('ps_aged_stock_product')->nullable();
            $table->integer('ps_aged_stock_qty')->default(0);
            $table->decimal('ps_aged_stock_total', 10, 2)->default(0);
            $table->string('ps_markdown_product')->nullable();
            $table->integer('ps_markdown_qty')->default(0);
            $table->decimal('ps_markdown_total', 10, 2)->default(0);

            // --- 5) Sponsorships ---
            $table->decimal('sponsor_new_opening', 10, 2)->default(0);
            $table->decimal('sponsor_warehouse', 10, 2)->default(0);
            $table->decimal('sponsor_mailer', 10, 2)->default(0);
            $table->decimal('sponsor_anniversary', 10, 2)->default(0);
            $table->decimal('sponsor_exhibition', 10, 2)->default(0);
            $table->decimal('sponsor_others', 10, 2)->default(0);

            // --- 6) Total Order Amount ---
            $table->decimal('order_walfood_brand', 10, 2)->default(0);
            $table->decimal('order_other_brand', 10, 2)->default(0);

            // --- 终极总报销额 (计算 1 到 5 项) ---
            $table->decimal('grand_total_claim', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketing_activities');
    }
};