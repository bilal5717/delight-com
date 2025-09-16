<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyPayment extends Model
{
    use HasFactory;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'company_payment';

    /**
     * The primary key for the model.
     *
     * @var string
     */

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'receipt_type',
        'information',
        'account_holder_name',
        'iban',
        'ifsc',
        'uk_sort_code',
        'account_number',
        'ach_routing_number',
        'account_type',
        'recipient_address',
        'country',
        'city',
        'postal_code',
        'recipient_nationality',
        'tax_id',
        'bsb_code',
        'bank_name',
        'branch_name',
        'branch_code',
        'registration_number',
        'recipient_phone_number',
        'institution_number',
        'transit_number',
        'recipient_rut_number',
        'card_number',
        'id_doc_type',
        'identification_no',
        'bank_code',
        'account_prefix',
        'recipient_dob',
        'clabe',
        'curp',
        'duitnow_id',
        'job_title',
        'national_id',
        'default_payment',
        'show_on_invoice',
        'currency_code'
    ];
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
