<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use App\Models\Category;
use App\Models\Company;
use App\Models\PostType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
	public $wordLimit = 500;

	public function __construct()
	{
		view()->share('pagePath', 'company-profile');
		view()->share('og', '');
	}
	public function index(Request $request)
	{
		$categories = Category::where('active', 1)->get();
		
		if (Auth::user()) {
			$company = Auth::user()->company;
			view()->share('company', $company);

			$post = PostType::where('id','!=',1)->orderBy('lft')->get();
			view()->share('post', $post);

			try {
				// Ajax response
				if ($request->ajax()) {
					$data = [];
					$data['initialPreview'] = [];
					$data['initialPreviewConfig'] = [];

					if (!empty($company->logo)) {
						// Get Deletion Url
						$initialPreviewConfigUrl = url('account/' . $company->id . '/logo/delete');

						// Build Bootstrap-Input plugin's parameters
						$data['initialPreview'][] = imgUrl($company->logo, 'company');

						$data['initialPreviewConfig'][] = [
							'caption' => last(explode(DIRECTORY_SEPARATOR, $company->logo)),
							'size'    => (isset($this->disk) && $this->disk->exists($company->logo)) ? (int)$this->disk->size($company->logo) : 0,
							'url'     => $initialPreviewConfigUrl,
							'key'     => $company->id,
							'extra'   => ['id' => $company->id],
						];
					}

					return response()->json($data);
				}
			} catch (\Exception $e) {
				dd($e);
			}
		}
		return appView('account.company-profile')->with('categories', $categories)->with('company', $company);
	}
	public function viewCompanyDetails(Request $request)
	{
		$categories = Category::where('active', 1)->get();
		$company = Company::with('companyAddresss', 'defaultCompanyAddresss')->find($request->id);
		view()->share('company', $company);

		try {
			// Ajax response
			if ($request->ajax()) {
				$data = [];
				$data['initialPreview'] = [];
				$data['initialPreviewConfig'] = [];

				if (!empty($company->logo)) {
					// Get Deletion Url
					$initialPreviewConfigUrl = url('account/' . $company->id . '/logo/delete');

					// Build Bootstrap-Input plugin's parameters
					$data['initialPreview'][] = imgUrl($company->logo, 'company');

					$data['initialPreviewConfig'][] = [
						'caption' => last(explode(DIRECTORY_SEPARATOR, $company->logo)),
						'size'    => (isset($this->disk) && $this->disk->exists($company->logo)) ? (int)$this->disk->size($company->logo) : 0,
						'url'     => $initialPreviewConfigUrl,
						'key'     => $company->id,
						'extra'   => ['id' => $company->id],
					];
				}

				return response()->json($data);
			}
		} catch (\Exception $e) {
			dd($e);
		}

		return appView('account.company')->with('categories', $categories)->with('company', $company);
	}
	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'name'                  => 'required',
			'description'           => 'required',
			'email'           => 'required',
			'facebook'           => '',
			'twitter'           => '',
			'instagram'           => '',
			'linkedin'           => '',
			'kvk'           => '',
			'wechat'           => '',
			'website'           => '',
			'phone'                 => 'required',
			'revenue'               => 'required',
			'category_id'               => 'required',
			'registration_number'   => 'required',
			'default_business_type'   => '',
		]);
		if ($validator->fails()) {
			return redirect('account/company-profile')
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

		$value = trim(mb_strtolower($validated['description']));
		$words = Blacklist::whereIn('type', ['word', 'domain', 'email'])->get();

		if ($words->count() > 0) {
			foreach ($words as $word) {
				// Check if a ban's word is contained in the user entry
				$startPatten = '\s\-.,;:=/#\|_<>';
				$endPatten = $startPatten . 's';
				try {
					if (preg_match('|[' . $startPatten . '\\\]+' . $word->entry . '[' . $endPatten . '\\\]+|ui', ' ' . $value . ' ')) {
						return redirect()->route('account/company-profile')->withErrors([trans('company.can_not_use_bad_word_in_description', ['badword' => $word->entry])])->withInput();
					}
				} catch (\Exception $e) {
					if (preg_match('|[' . $startPatten . ']+' . $word->entry . '[' . $endPatten . ']+|ui', ' ' . $value . ' ')) {
						return redirect()->route('company-profile')->withErrors([trans('company.can_not_use_bad_word_in_description', ['badword' => $word->entry])])->withInput();
					}
				}
			}
		}

		$desc = strip_tags($validated['description']);
		$desc_len = Str::of($desc)->wordCount();

		if ($desc_len >= $this->wordLimit) {
			return redirect()->route('company-profile')->withErrors([trans('company.company_description_length_not_greater_then')])->withInput();
		}

		$validated['user_id'] = Auth::user()->id;
		$data = DB::table('companies')
			->updateOrInsert(['user_id' => Auth::user()->id], $validated);
		return redirect('account/company-profile')->with('success', trans('company.company_profile_updated'));
	}
}