<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Stripe PaymentIntent id (e.g. pi_xxx) for reconciliation.
            $table->string('payment_intent_id')->nullable()->after('payment_method');
            // Only non-sensitive card metadata is stored (PCI compliant) — never the full PAN.
            $table->string('card_brand')->nullable()->after('payment_intent_id');
            $table->string('card_last4', 4)->nullable()->after('card_brand');
            // 'paid' indicates the gateway authorized the charge.
            $table->boolean('is_paid')->default(false)->after('card_last4');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_intent_id', 'card_brand', 'card_last4', 'is_paid']);
        });
    }
};
