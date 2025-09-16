@if (config('settings.currencyexchange.activation'))
	@if (isset($currencies) && !empty($currencies))
		<li class="nav-item dropdown no-arrow">
			<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
				<span>
					@if(session()->has('curr'))
						@foreach($currencies as $iCurr)
							@if($iCurr->get('code') == session('curr'))
							{!! $iCurr->get('symbol') ? $iCurr->get('symbol') : '-' !!} {{ $iCurr->get('code') }}
							@endif
						@endforeach
					@else
						{!! config('selectedCurrency.symbol') !!} {{config('selectedCurrency.code')}}
					@endif
				</span>
				<i class="fas fa-chevron-down hidden-sm"></i>
			</a>
			<ul id="currenciesDropdownMenu" class="dropdown-menu user-menu">
				@foreach($currencies as $iCurr)
					<li class="{!! ($iCurr->get('code') == config('selectedCurrency.code')) ? 'dropdown-item active' : 'dropdown-item' !!}">
						<a href="{!! qsUrl(request()->path(), array_merge(request()->except(['curr']), ['curr' => $iCurr->get('code')]), null, false) !!}">
							{!! $iCurr->get('symbol') ? $iCurr->get('symbol') : '-' !!} {{ $iCurr->get('code') }}
						</a>
					</li>
				@endforeach
			</ul>
		</li>
	@endif
@endif
