<div class="col-lg-6 col-md-12">
    <div class="card border-top border-primary shadow-sm">
        <div class="card-body">

            <div class="d-md-flex">
                <div>
                    <h4 class="card-title font-weight-bold">
                        <span class="lstick d-inline-block align-middle"></span>{{ trans('company-admin.companies') }}
                    </h4>
                </div>
            </div>

            <div class="table-responsive mt-3 no-wrap">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th class="border-0">{{ trans('company-admin.ID') }}</th>
                            <th class="border-0">{{ mb_ucfirst(trans('company-admin.name')) }}</th>
                            <th class="border-0">{{ mb_ucfirst(trans('company-admin.owner_name')) }}</th>
                            <th class="border-0">{{ trans('company-admin.revenue') }}</th>
                            <th class="border-0">{{ trans('company-admin.date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($companies->count() > 0)
                            @foreach ($companies as $company)
                                <tr>
                                    <td class="td-nowrap">{{ $company->id }}</td>
                                    <td class="td-nowrap">
                                        <a href="{{ admin_url('view-company/'. $company->id) }}">
                                            {!! $company->name !!}</a>
                                    </td>
                                    <td class="td-nowrap">{!! $company->user->name !!}</td>
                                    <td class="td-nowrap">{!! getRevenue($company->revenue) !!}</td>
                                    <td class="td-nowrap">
                                        <div class="sparkbar" data-color="#00a65a" data-height="20">
                                            {{ \App\Helpers\Date::format($company->created_at, 'datetime') }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">
                                    {{ trans('company-admin.No companies found') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

@push('dashboard_styles')
    <style>
        .td-nowrap {
            width: 10px;
            white-space: nowrap;
        }
    </style>
@endpush

@push('dashboard_scripts')
@endpush
