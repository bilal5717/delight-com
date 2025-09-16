<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\ShippingAddress;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class ShippingAddressController extends Controller
{
    public function __construct()
	{
		view()->share('pagePath', 'shipping_address');
		view()->share('og', '');
	}
	public function index()
	{
		$company = Auth::user()->company ? Auth::user()->company->id : '';
		$addresses = ShippingAddress::with('city')->where('company_id', $company)->orderBy('default_address', 'desc')->get();
		return appView('account.shipping-address')->with('addresses', $addresses);
	}

	public function create()
	{
		$country = config('country');
		$addresses = ShippingAddress::where('company_id',Auth::user()->company->id)->get()->count();
		$countries = Country::active()->get();
		return appView('account.create-shipping-address', compact("addresses","country","countries"));
	}

	public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'address'  => 'required',
        'address_title'  => 'required',
        'city_id'     => ['required', 'not_in:0'],
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
    $city = City::find($request->input('city_id'));

    if ($request->has('default_address')) {
        ShippingAddress::where('company_id', Auth::user()->company->id)->update(['default_address' => 0]);
    }
    // Retrieve validated input
    $validated = $validator->validated();
    $validated['company_id'] = Auth::user()->company->id;
    $validated['address_title'] = $request->input('address_title');
    $validated['default_address'] = $request->has('default_address') ? 1 : 0;
    $validated['latitude'] = $city->latitude ?? null;
    $validated['longitude'] = $city->longitude ?? null;
    ShippingAddress::create($validated);

    return redirect('account/shipping-address')->with('success', t('shipping_address_stored_successfully'));
}
	public function edit($id)
	{
		$country = config('country');
		$shipping_address = ShippingAddress::with('country')->find($id);
		$countries = Country::active()->get();
		$cities = City::all();
 		return appView('account.edit-shipping-address',compact("shipping_address",'country','countries','cities'));
	}
	public function update(Request $request, $id)
{
    $company_address = ShippingAddress::find($id);

    if (!$company_address) {
        return redirect()->back()->with('error', t('shipping_address_not_found'));
    }

    $validator = Validator::make($request->all(), [
        'address'  => 'required',
        'address_title'  => 'required',
        'city_id'     => ['required', 'not_in:0'],
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

    $city = City::find($request->input('city_id'));
    if ($request->has('default_address')) {
        if ($request->input('default_address') && !$company_address->default_address) {
            ShippingAddress::where('company_id', Auth::user()->company->id)->update(['default_address' => 0]);
        }
    } elseif ($company_address->default_address && $request->input('original_default_address') == 1) {
    } else {
        $addresses = ShippingAddress::where("company_id", Auth::user()->company->id)
                      ->where("id", "!=", $id)
                      ->first();

        if ($addresses) {
            $addresses->update(['default_address' => 1]);
        }
    }

    $validated = $validator->validated();
    $validated['default_address'] = $request->has('default_address') 
        ? ($request->input('default_address') ? 1 : 0)
        : $company_address->default_address;
    $validated['latitude'] = @$city->latitude;
    $validated['longitude'] = @$city->longitude;

    $company_address->update($validated);

    return redirect('account/shipping-address')->with('success', t('shipping_address_updated_successfully'));
}

	public function destroy($id)
	{
		$companies = ShippingAddress::where('id', $id)->where('default_address', 0);

		$companies->delete();
		return response()->noContent();
	}

	public function updateDefaultAddress(Request $request)
{
    $address = ShippingAddress::find($request->address_id);

    if (!$address) {
        return response()->json(['error' => t('shipping_address_not_found')], 404);
    }
    if($address->default_address == 1) {
        $addresses = ShippingAddress::where("company_id", Auth::user()->company->id)->where("id", "!=", $request->address_id)->first();
        if ($addresses) { // Ensure the address exists before updating
            $addresses->update(['default_address' => 1]);
        }       
        $address->update(['default_address' => 0]);
    } else {
        ShippingAddress::where('company_id', Auth::user()->company->id)->update(['default_address' => 0]);
        $address->update(['default_address' => $request->default_address ? 1 : 0]);
    }
    return response()->json(['success' => t('default_address_updated')]);
}

}
