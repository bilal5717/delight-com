<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\CompanyPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function __construct()
    {
        view()->share('pagePath', '');
        view()->share('og', '');
    }
    public function viewCompany($id)
    {
        $company = Company::with('category', 'currency', 'companyPayment', 'companyAddresss')->find($id);
        return view('admin::panel.view_company')->with('company', $company);
    }

    public function editCompany($id)
    {
        $company = Company::find($id);
        $categories = Category::where('active', 1)->get();
        return view('admin::panel.edit_company')->with('categories', $categories)->with('company', $company);
    }
    public function updateCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo'                  => '',
            'name'                  => 'required',
            'description'           => 'required',
            'phone'                 => 'required',
            'revenue'               => 'required',
            'category_id'               => '',
            'registration_number'   => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Retrieve the validated input...
        $validated = $validator->validated();
        if ($request->hasFile('logo')) {
            $filename = $request->logo;
            $disk = Storage::disk('public')->put('company-logo', $filename);
            $validated['logo'] = $disk;
        }
        $validated['user_id'] = Auth::user()->id;
        $company = Auth::user()->company;
        Company::where(['user_id' => Auth::user()->id])->update($validated);

        return redirect()->back()->with('success', trans('company-admin.company_updated_successfully'));
    }
    public function getForm($currency, $getcurrency = '')
    {
        $data = Config::get('CompanyPaymentFields');
        $payment = '';
        if ($getcurrency) {
            $payment = CompanyPayment::find($getcurrency);
        }
        return view('admin::panel.view_company')->with('fields', $data[$currency])->with('payment', $payment)->render();
    }
}
