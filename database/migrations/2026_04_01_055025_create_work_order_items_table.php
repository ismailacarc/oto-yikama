<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('work_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_order_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['service', 'product']);
            $table->unsignedBigInteger('item_id')->nullable();  // service_id veya product_id
            $table->string('name');                              // snapshot (silinirse kaybolmasın)
            $table->decimal('quantity', 10, 3)->default(1);
            $table->string('unit')->nullable();                  // adet, m², kg, litre
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('work_order_items'); }
};
