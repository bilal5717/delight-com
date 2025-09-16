<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CompanyPayment;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class CompanyPaymentController extends Controller
{
	public function __construct()
	{
		view()->share('pagePath', 'company-payment');
		view()->share('og', '');
	}

	public function index()
	{
		$company = Auth::user()->company ? Auth::user()->company->id : '';
		$payment_details = CompanyPayment::with('currency')->where('company_id', $company)->orderBy('default_payment', 'desc')->get();
		return appView('account.company-payment-list')->with('payment_details', $payment_details);
	}

	public function create()
	{
		$currencies = Currency::all();
		return appView('account.create-company-payment')->with('currencies', $currencies);
	}

	public function store(Request $request)
	{
		// $validator = Validator::make($request->all());
		$paymentData = $request->all();
		$paymentData['company_id'] = Auth::user()->company->id;
		$paymentData['currency_code'] = $request->currency;
		$paymentData['default_payment'] = $request->has('default_payment') ? 1 : 0;
		if ($request->has('default_payment')) {
			CompanyPayment::where('company_id', Auth::user()->company->id)->update(['default_payment' => 0]);
		}
		CompanyPayment::create($paymentData);

		return redirect('account/company-payment')->with('success', trans('company.payment_details_submitted_successfully'));
	}
	public function edit($id)
	{
		$currencies = Currency::all();
		$company_payment = CompanyPayment::with('currency')->find($id);
		return appView('account.edit-company-payment')->with('currencies', $currencies)->with('company_payment', $company_payment);
	}
	public function update(Request $request, $id)
	{
		$paymentData = CompanyPayment::with('currency')->find($id);
		$payment = $request->all();

		if ($request->has('default_payment')) {
			CompanyPayment::where('company_id', Auth::user()->company->id)->update(['default_payment' => 0]);
		}
		$paymentData->update($payment);

		return redirect('account/company-payment')->with('success', trans('company.payment_details_updated_successfully'));
	}
	public function getForm($currency, $getcurrency = '')
	{
		$payments = CompanyPayment::where('company_id',Auth::user()->company->id)->get()->count();
		$data = Config::get('CompanyPaymentFields');
		$payment = '';
		if ($getcurrency) {
			$payment = CompanyPayment::find($getcurrency);
		}

		return response()->json(["is_data"=> $data[$currency] == null ? false : true,"html" => view('account.generate-form')->with('fields', $data[$currency])->with('payment', $payment)->with('payments',$payments)->render()]);
	}

	public function destroy($id)
	{
		$company_payment = CompanyPayment::where('id', $id)->where('default_payment', 0);

		$company_payment->delete();
		return response()->noContent();
	}

	public function updateDefaultpayment(Request $request)
	{
		$payment = CompanyPayment::find($request->payment_id);
		CompanyPayment::where('company_id', Auth::user()->company->id)->update(['default_payment' => 0]);
		$payment->update(['default_payment' => $request->default_payment ? 1 : 0]);
		return true;
	}
	public function updateShowOnInvoice(Request $request)
    {
        $payment = CompanyPayment::where('id', $request->payment_id)
            ->where('company_id', Auth::user()->company->id)
            ->firstOrFail();

        // If setting to true, first set all others to false
        if ($request->show_on_invoice) {
            CompanyPayment::where('company_id', Auth::user()->company->id)
                ->where('id', '!=', $request->payment_id)
                ->update(['show_on_invoice' => false]);
        }

        $payment->show_on_invoice = $request->show_on_invoice;
        $payment->save();

        return response()->json([
            'success' => true,
            'message' => t('Show on invoice status updated successfully')
        ]);
    }
}
