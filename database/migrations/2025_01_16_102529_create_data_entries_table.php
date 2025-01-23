<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_entries', function (Blueprint $table) {
            $table->id();
            $table->string('building_name', 255);
            $table->string('address', 255);
            $table->string('tower', 50);
            $table->string('tenant_name', 255);
            $table->integer('suit');
            $table->string('rent', length: 255); // You can change this to decimal for currency validation if needed
            $table->integer('square_feet');
            $table->decimal('percentage_of_total', 5, 2); // Allowing for decimals up to 100.00
            $table->date('lease_expiration');
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_entries');
    }
}
