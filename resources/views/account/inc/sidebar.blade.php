<aside>
    <div class="inner-box">
        <div class="user-panel-sidebar">

            <div class="collapse-box">
                <h5 class="collapse-title no-border">
                    {{ t('My Account') }}&nbsp;
                    <a href="#MyClassified" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a>
                </h5>
                <div class="panel-collapse collapse show" id="MyClassified">
                    <ul class="acc-list">
                        <li>
                            <a {!! $pagePath == '' ? 'class="active"' : '' !!} href="{{ url('account') }}">
                                <i class="icon-home"></i> {{ t('Personal Home') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="collapse-box">
				<div class="panel-collapse collapse show" id="MyClassified">
					<ul class="acc-list">
						<li>
							<a {!! ($pagePath=='working-hours') ? 'class="active"' : '' !!} href="{{ url('account/working-hours') }}">
								<i class="icon-clock"></i> {{ t('working_hour_setting_page') }}
							</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="collapse-box">
				<div class="panel-collapse collapse show" id="MyClassified">
					<ul class="acc-list">
						<li>
							<a {!! ($pagePath=='shipping_address') ? 'class="active"' : '' !!} href="{{ url('account/shipping-address') }}">
                            <i class="fas fa-shipping-fast"></i> {{ t('shipping_address') }}
							</a>
						</li>
					</ul>
				</div>
			</div>
			<!-- /.collapse-box  -->
			
            <div class="collapse-box">
                <h5 class="collapse-title no-border">
                    {{ t('company_details') }}&nbsp;
                    <a href="#MyCompany" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a>
                </h5>
                <div class="panel-collapse collapse show" id="MyCompany">
                    <ul class="acc-list">
                        <li>
                            <a {!! $pagePath == 'company-profile' ? 'class="active"' : '' !!} href="{{ url('account/company-profile') }}">
                                <i class="fas fa-briefcase"></i> {{ t('company_page') }}
                            </a>
                        </li>
                        @if (auth()->check() && auth()->user()->company)
                            <li>
                                <a {!! $pagePath == 'company_address' ? 'class="active"' : '' !!} href="{{ url('account/company-address') }}">
                                    <i class="far fa-building"></i> {{ t('company_address') }}
                                </a>
                            </li>
                            <li>
                                <a {!! $pagePath == 'company-payment' ? 'class="active"' : '' !!} href="{{ url('account/company-payment') }}">
                                    <i class="icon-money"></i> {{ t('payment_details') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <!-- /.collapse-box  -->
@php
    $pagePath = $pagePath ?? 'all-orders'; // Default value if not set
@endphp
            <div class="collapse-box">
                <h5 class="collapse-title">
                    {{ t('all_orders') }}
                    <a href="#AllOrders" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a>
                </h5>
                <div class="panel-collapse collapse show" id="AllOrders">
                    <ul class="acc-list">
                        <li>
                            <a {!! $pagePath == 'all-orders' ? ' class="active"' : '' !!} href="{{ url('account/all-orders') }}">
                                <i class="fas fa-shopping-cart"></i> {{ t('Orders') }}&nbsp;
                                <span class="badge badge-pill">
                                    {{ isset($countOrders) ? \App\Helpers\Number::short($countOrders) : 0 }}
                                </span>
                            </a>
                        </li>
                        <li>
                            <a {!! $pagePath == 'notifications' ? ' class="active"' : '' !!} href="{{ url('account/notifications') }}">
                                <i class="fas fa-bell"></i> {{ t('notifications') }}&nbsp;
                                <span class="badge badge-pill">
                                    {{ isset($countNotifications) ? \App\Helpers\Number::short($countNotifications) : 0 }}
                                </span>
                            </a>
                        </li>
                        <li>
                            <a {!! $pagePath == 'order-payments' ? ' class="active"' : '' !!} href="{{ url('account/order-payments') }}">
                                <i class="fas fa-credit-card"></i> {{ t('payments') }}&nbsp;
                                <span class="badge badge-pill">
                                    {{ isset($countOrders) ? \App\Helpers\Number::short($countOrders) : 0 }}
                                </span>
                            </a>
                        </li>
                        <li>
                            <a {!! $pagePath == 'shipping' ? ' class="active"' : '' !!} href="{{ url('account/shipping') }}">
                                <i class="fas fa-truck"></i> {{ t('Shipping') }}&nbsp;
                                <span class="badge badge-pill">
                                    {{ isset($countShipping) ? \App\Helpers\Number::short($countShipping) : 0 }}
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.collapse-box  -->

            <div class="collapse-box">
                <h5 class="collapse-title">
                    {{ t('my_ads') }}
                    <a href="#MyAds" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a>
                </h5>
                <div class="panel-collapse collapse show" id="MyAds">
                    <ul class="acc-list">
                        <li>
                            <a {!! $pagePath == 'my-posts' ? ' class="active"' : '' !!} href="{{ url('account/my-posts') }}">
                                <i class="icon-docs"></i> {{ t('my_ads') }}&nbsp;
                                <span class="badge badge-pill">
                                    {{ isset($countMyPosts) ? \App\Helpers\Number::short($countMyPosts) : 0 }}
                                </span>
                            </a>
                        </li>
                        <li>
                            <a {!! $pagePath == 'favourite' ? ' class="active"' : '' !!} href="{{ url('account/favourite') }}">
                                <i class="icon-heart"></i> {{ t('favourite_ads') }}&nbsp;
                                <span class="badge badge-pill">
                                    {{ isset($countFavouritePosts) ? \App\Helpers\Number::short($countFavouritePosts) : 0 }}
                                </span>
                            </a>
                        </li>
                        <li>
                            <a {!! $pagePath == 'saved-search' ? ' class="active"' : '' !!} href="{{ url('account/saved-search') }}">
                                <i class="icon-star-circled"></i> {{ t('Saved searches') }}&nbsp;
                                <span class="badge badge-pill">
                                    {{ isset($countSavedSearch) ? \App\Helpers\Number::short($countSavedSearch) : 0 }}
                                </span>
                            </a>
                        </li>
                        <li>
                            <a {!! $pagePath == 'pending-approval' ? ' class="active"' : '' !!} href="{{ url('account/pending-approval') }}">
                                <i class="icon-hourglass"></i> {{ t('pending_approval') }}&nbsp;
                                <span class="badge badge-pill">
                                    {{ isset($countPendingPosts) ? \App\Helpers\Number::short($countPendingPosts) : 0 }}
                                </span>
                            </a>
                        </li>
                        <li>
                            <a {!! $pagePath == 'archived' ? ' class="active"' : '' !!} href="{{ url('account/archived') }}">
                                <i class="icon-folder-close"></i> {{ t('archived_ads') }}&nbsp;
                                <span class="badge badge-pill">
                                    {{ isset($countArchivedPosts) ? \App\Helpers\Number::short($countArchivedPosts) : 0 }}
                                </span>
                            </a>
                        </li>
                        <li>
                            <a {!! $pagePath == 'messenger' ? 'class="active" ' : '' !!}href="{{ url('account/messages') }}">
                                <i class="icon-mail-1"></i> {{ t('messenger') }}&nbsp;
                                <span class="badge badge-pill count-threads-with-new-messages hide">0</span>
                            </a>
                        </li>
                        <li>
                            <a {!! $pagePath == 'transactions' ? ' class="active"' : '' !!} href="{{ url('account/transactions') }}">
                                <i class="icon-money"></i> {{ t('Transactions') }}&nbsp;
                                <span class="badge badge-pill">
                                    {{ isset($countTransactions) ? \App\Helpers\Number::short($countTransactions) : 0 }}
                                </span>
                            </a>
                        </li>
                        @if (config('plugins.api.installed'))
                            <li>
                                <a {!! $pagePath == 'api-dashboard' ? ' class="active"' : '' !!} href="{{ url('account/api-dashboard') }}">
                                    <i class="icon-cog"></i>
                                    {{ trans('api::messages.Clients & Applications') }}&nbsp;
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <!-- /.collapse-box  -->

            <div class="collapse-box">
                <h5 class="collapse-title">
                    {{ t('Terminate Account') }}&nbsp;
                    <a href="#TerminateAccount" data-toggle="collapse" class="pull-right"><i
                            class="fa fa-angle-down"></i></a>
                </h5>
                <div class="panel-collapse collapse show" id="TerminateAccount">
                    <ul class="acc-list">
                        <li>
                            <a {!! $pagePath == 'close' ? 'class="active"' : '' !!} href="{{ url('account/close') }}">
                                <i class="icon-cancel-circled "></i> {{ t('Close account') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.collapse-box  -->

        </div>
    </div>
    <!-- /.inner-box  -->
</aside>