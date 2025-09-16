<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\CompanyAddress;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CompanyAddressController extends Controller
{
	public function __construct()
	{
		view()->share('pagePath', 'company_address');
		view()->share('og', '');
	}
	public function index()
	{
		$company = Auth::user()->company ? Auth::user()->company->id : '';
		$addresses = CompanyAddress::with('city')->where('company_id', $company)->orderBy('default_address', 'desc')->get();
		return appView('account.company-address')->with('addresses', $addresses);
	}

	public function create()
	{
		$country = config('country');
		$addresses = CompanyAddress::where('company_id',Auth::user()->company->id)->get()->count();
		$countries = Country::active()->get();
		return appView('account.create-company-address', compact("addresses","country","countries"));
	}

	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'address'  => 'required',
			'city_id'     => 'required',
			'state'     => 'required',
			'country'  => 'required',
			'pincode'  => 'required',
		]);
		if ($validator->fails()) {
			return redirect()
				->back()
				->withErrors($validator)
				->withInput();
		}
		// Get the companies City
		$city = City::find($request->input('city_id'));

		if ($request->has('default_address')) {
			CompanyAddress::where('company_id', Auth::user()->company->id)->update(['default_address' => 0]);
		}
		// Retrieve the validated input...
		$validated = $validator->validated();
		$validated['company_id'] = Auth::user()->company->id;
		$validated['default_address'] = $request->has('default_address') ? 1 : 0;
		$validated['latitude'] = $city->latitude;
		$validated['longitude'] = $city->longitude;
		CompanyAddress::create($validated);

		return redirect('account/company-address')->with('success', trans('company.address_stored_successfully'));
	}
	public function edit($id)
	{
		$country = config('country');
		$company_address = CompanyAddress::with('country')->find($id);
		$countries = Country::active()->get();
		return appView('account.edit-company-address',compact("company_address",'country','countries'));
	}
	public function update(Request $request, $id)
	{
		$company_address = CompanyAddress::find($id);
		$validator = Validator::make($request->all(), [
			'address'  => 'required',
			'city_id'     => 'required',
			'state'     => 'required',
			'country'  => 'required',
			'pincode'  => 'required',
		]);
		if ($validator->fails()) {
			return redirect()
				->back()
				->withErrors($validator)
				->withInput();
		}

		// Get the companies City
		$city = City::find($request->input('city_id'));
		if ($request->has('default_address')) {
			CompanyAddress::where('company_id', Auth::user()->company->id)->update(['default_address' => 0]);
		} else if(!$request->input('default_address') && ($id == $company_address->id)){
			$addresses = CompanyAddress::where("company_id", Auth::user()->company->id)->where("id", "!=",$id)->first();

			$addresses->update(['default_address' => 1]);
		}

		// Retrieve the validated input...
		$validated = $validator->validated();
		$validated['default_address'] = $request->has('default_address') ? 1 : 0;
		$validated['latitude'] = $city->latitude;
		$validated['longitude'] = $city->longitude;

		$company_address->update($validated);

		return redirect('account/company-address')->with('success', trans('company.address_updated_successfully'));
	}

	public function destroy($id)
	{
		$companies = CompanyAddress::where('id', $id)->where('default_address', 0);

		$companies->delete();
		return response()->noContent();
	}

	public function updateDefaultAddress(Request $request)
	{
		$address = CompanyAddress::find($request->address_id);

		if($address->default_address == 1) {
			$addresses = CompanyAddress::where("company_id", Auth::user()->company->id)->where("id", "!=",$request->address_id)->first();
			$addresses->update(['default_address' => 1]);
			$address->update(['default_address' => 0]);
		} else {
			CompanyAddress::where('company_id', Auth::user()->company->id)->update(['default_address' => 0]);
			$address->update(['default_address' => $request->default_address ? 1 : 0]);
		}
		return true;
	}
}
