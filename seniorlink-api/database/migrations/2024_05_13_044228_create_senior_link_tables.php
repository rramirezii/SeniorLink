<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the database if it doesn't exist
        DB::statement('CREATE DATABASE IF NOT EXISTS senior_link');

        // Switch to the senior_link database
        config(['database.connections.mysql.database' => 'senior_link']);
        
        Schema::connection('mysql')->create('town', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->integer('zip_code')->unique()->nullable(false);
            $table->string('username')->unique()->nullable(false); // in controller, make it non editable field by making format t[zip_code]
            $table->string('password')->nullable(false);
        });

        Schema::connection('mysql')->create('super_admin', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('username')->unique()->nullable(false); // in controller, make it non editable field by making format admin[name]
            $table->string('password')->nullable(false);
        });

        Schema::connection('mysql')->create('establishment', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('code')->unique()->nullable(false);
            $table->string('address')->nullable(false);
            $table->string('username')->unique()->nullable(false); // in controller, make it non editable field by making format e[code]
            $table->string('password')->nullable(false);
        });

        Schema::connection('mysql')->create('barangay', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->unsignedBigInteger('town_id')->nullable(false); // Adding town_id column
            $table->foreign('town_id')->references('id')->on('town');
            $table->string('username')->unique()->nullable(false); // in controller, make it non editable field by making format b[zip_code][barangay_name]
            $table->string('password')->nullable(false);
        });

        Schema::connection('mysql')->create('senior', function (Blueprint $table) {
            $table->id();
            $table->string('osca_id')->nullable(false);
            $table->string('fname')->nullable(false);
            $table->string('mname');
            $table->string('lname')->nullable(false);
            $table->unsignedBigInteger('barangay_id')->nullable(false);
            $table->foreign('barangay_id')->references('id')->on('barangay');
            $table->date('birthdate')->nullable(false);
            $table->string('contact_number', 11)->nullable(false);
            $table->string('username')->unique()->nullable(false); // in controller, make it non editable field by making format s[zip_code][osca_id]
            $table->binary('profile_image')->nullable(); 
            $table->binary('qr_image')->nullable(); 
        });

        Schema::connection('mysql')->create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->integer('quantity')->nullable(false);
            $table->decimal('price', 10, 2); 
        });

        Schema::connection('mysql')->create('transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('senior_id')->nullable(false);
            $table->foreign('senior_id')->references('id')->on('senior');
            $table->unsignedBigInteger('establishment_id')->nullable(false); 
            $table->foreign('establishment_id')->references('id')->on('establishment');
            $table->date('date');
        });

        Schema::connection('mysql')->create('product_transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('products_id')->nullable(false);
            $table->foreign('products_id')->references('id')->on('products');
            $table->unsignedBigInteger('transaction_id')->nullable(false); 
            $table->foreign('transaction_id')->references('id')->on('transaction');
        });
    }

    
    public function down(): void
    {
        config(['database.connections.mysql.database' => null]);

        Schema::connection('mysql')->dropIfExists('town');
        Schema::connection('mysql')->dropIfExists('super_admin');
        Schema::connection('mysql')->dropIfExists('establishment');
        Schema::connection('mysql')->dropIfExists('barangay');
        Schema::connection('mysql')->dropIfExists('senior');
        Schema::connection('mysql')->dropIfExists('products');
        Schema::connection('mysql')->dropIfExists('transaction');
        Schema::connection('mysql')->dropIfExists('product_transaction');

        // Drop the senior_link database itself
        DB::statement('DROP DATABASE IF EXISTS senior_link');
    }
};
