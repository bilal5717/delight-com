<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_payment', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->boolean('receipt_type')->nullable()->comment('0-personal,1-business');
            $table->string('account_holder_name', 255);
            $table->string('iban', 255);
            $table->string('ifsc', 255);
            $table->string('uk_sort_code', 255);
            $table->string('account_number', 255);
            $table->string('ach_routing_number', 255);
            $table->string('account_type', 255)->comment('0-Checking,1-Savings,2-Demand');
            $table->string('recipient_address', 255);
            $table->string('country', 255);
            $table->string('city', 255);
            $table->string('postal_code', 255);
            $table->string('recipient_nationality', 255);
            $table->string('tax_id', 255);
            $table->string('bsb_code', 255);
            $table->string('bank_name', 255);
            $table->string('branch_name', 255);
            $table->string('branch_code', 255);
            $table->string('registration_number', 255);
            $table->string('recipient_phone_number', 255);
            $table->string('institution_number', 255);
            $table->string('transit_number', 255);
            $table->string('recipient_rut_number', 255);
            $table->string('card_number', 255);
            $table->string('id_doc_type', 255);
            $table->string('identification_no', 255);
            $table->string('bank_code', 255);
            $table->string('account_prefix', 255);
            $table->string('recipient_dob', 255);
            $table->string('clabe', 255);
            $table->string('curp', 255);
            $table->string('duitnow_id', 255);
            $table->string('job_title', 255);
            $table->string('national_id', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_payment');
    }
}
