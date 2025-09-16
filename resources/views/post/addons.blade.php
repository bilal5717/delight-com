{{-- * LaraClassified - Classified Ads Web Application --}}
@extends('layouts.master')

@section('content')
@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
<div class="main-container">
    <div class="container">
        <div class="row">
            @includeFirst([
                config('larapen.core.customizedViewPath') . 'post.inc.notification',
                'post.inc.notification',
            ])
            <div class="col-md-9 page-content">
                <div class="inner-box category-content">
                    <h2 class="title-2">
                        <strong><i class="fas fa-puzzle-piece"></i> {{ t('add_addon_packages') }}-</strong>
                        <a href="{{ \App\Helpers\UrlGen::post($post) }}" class="tooltipHere" title=""
                               data-placement="top" data-toggle="tooltip" data-original-title="{!! $post->title !!}">
                                {!! \Illuminate\Support\Str::limit($post->title, 45) !!}
                            </a>
                    </h2>

                    <!-- Form for Post Addons -->
                    <form class="form-horizontal" id="postForm" method="POST" action="{{ url()->current() }}" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <input name="_method" type="hidden" value="PUT">
                        <input type="hidden" name="post_id" value="{{ $post->id }}">

                        <fieldset>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr class="bg-primary"><td colspan="4" class="p-2"><h3 class="p-0">{{ t('default_Addons') }}</h3></td></tr>
                                        <tr >
                                            <th class="text-center">{{ t('activate') }}</th>
                                            <th class="text-center">{{ t('title') }}</th>
                                            <th class="text-center">{{ t('Amount') }}</th>
                                            <th class="text-center">{{ t('actions') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody id="addonsTableBody" data-post-id="{{$post->id}}">

                                        {{-- Default Addons --}}
                                        @if($defaultAddons->isNotEmpty())
                                            @foreach($defaultAddons as $addon)
                                               
                                                    <tr data-addon-id="{{ $addon->id }}" class="defaultAddons" row-id="{{$addon->id}}" data-url="{{ route('update-default.addons', ['id' => $addon->id]) }}">
                                                        <td class="text-center">
                                                            <input type="checkbox" class="default_checkbox" data-label="default" id="default_addons_status" name="defaultAddons[{{ $addon->id }}][active]" value="1" {{ $addon->status == 'active' ? 'checked' : '' }} disabled>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="defaultAddons[{{ $addon->id }}][title]" class="form-control text-center" value="{{ $addon->title }}" disabled>
                                                        </td>
                                                        <td class="text-center price">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">{!! config('currency')['symbol'] !!}</span>
                                                                </div>
                                                                <input name="defaultAddons[{{ $addon->id }}][price]" class="form-control" type="number" min="0" value="{{ $addon->price }}" disabled>
                                                            </div>
                                                        </td>
                                                        <td class="text-center actions">
                                                            <button type="button" class="btn btn-primary btn-sm btn-edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-sm remove-addon-row" data-addon-id="{{ $addon->id }}" disabled>
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                             
                                            @endforeach
                                        @else
                                        <tr><td colspan="4"><h2 class="text-center p-2">{{t('No_Default_Addons')}}</h2></td></tr>
                                        @endif

                                        {{-- Add New Addon Button --}}
                                        <tr class="border-none">
                                            <td colspan="4" class="border-0">
                                                <div class="form-group row float-left">
                                                    <div class="col-md-12 text-center">
                                                        <button type="button" id="addNewAddonRow"  class="btn btn-primary btn-lg mt-3">
                                                            <i class="fas fa-plus-circle"></i> {{ t('ad_new_addon') }}
                                                        </button>
                                                    </div>
                                                </div>    
                                            </td>
                                        </tr>

                                        {{-- Post Addons --}}
                                        @if($postAddons->isNotEmpty())
                                            <tr class="bg-primary"><td colspan="4" class="p-2"><h3 class="p-0">{{ t('custom_Addons') }}</h3></td></tr>
                                            <tr>
                                            <th class="text-center">{{ t('activate') }}</th>
                                            <th class="text-center">{{ t('title') }}</th>
                                            <th class="text-center">{{ t('Amount') }}</th>
                                            <th class="text-center">{{ t('actions') }}</th>
                                            </tr>
                                            @foreach($postAddons as $addon)
                                               
                                                    <tr row-id="{{$addon->id}}">
                                                        <td class="text-center">
                                                            <input type="checkbox" name="postAddons[{{ $addon->id }}][active]" value="{{ $addon->status == 'active' ? 'active' : 'inactive' }}" {{ $addon->status == 'active' ? 'checked' : '' }} disabled>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="postAddons[{{ $addon->id }}][title]" class="form-control text-center" value="{{ $addon->title }}" disabled>
                                                        </td>
                                                        <td class="text-center price">
                                                            <div class="input-group">
                                                                <div class="input-group-prepend">
                                                                    <span class="input-group-text">{!! config('currency')['symbol'] !!}</span>
                                                                </div>
                                                                <input name="postAddons[{{ $addon->id }}][price]" class="form-control" type="number" min="0" value="{{ $addon->amount }}" disabled>
                                                            </div>
                                                        </td>
                                                        <td class="text-center actions">
                                                            <button type="button" class="btn btn-primary btn-sm btn-edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-sm remove-addon-row" data-url="{{ route('post-addons.delete', ['id' => $addon->id]) }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                               
                                            @endforeach
                                        @endif
                                       
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group row pt-3">
                                            <div class="col-md-12 text-center">
                                                <a href="{{ url()->previous() }}"
                                                   class="btn btn-default btn-lg"> {{ t('Back') }}</a>
                                                   <button type="button" class="btn btn-primary btn-lg" id="saveAllAddons">{{ t('save') }}</button>
                                            </div>
                                        </div>
                        </fieldset>
                        
                    </form>
                </div>
            </div>

            <div class="col-md-3 reg-sidebar">
                @includeFirst([
                    config('larapen.core.customizedViewPath') . 'post.createOrEdit.inc.right-sidebar',
                    'post.createOrEdit.inc.right-sidebar',
                ], ['post' => $post, 'countPaymentMethods' => $countPaymentMethods, 'countPackages' => $countPackages])
            </div>
        </div>
    </div>
</div>


@includeFirst([
    config('larapen.core.customizedViewPath') . 'post.createOrEdit.inc.category-modal',
    'post.createOrEdit.inc.category-modal',
])

@endsection

@section('after_styles')
    <link rel="stylesheet" href="{{ url('assets/css/countries-intl.css?15') }}">
    <style>
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
            padding: 5px;
        }
        .price{
            width: 20%;
        }
        .actions{
            width: 15%;
        }
        .form-control {
            padding: 2px 5px;
            text-align: center;
        }
    </style>
@endsection
@section('after_scripts')
<script>
    $(document).ready(function () {
    const tableBody = $('#addonsTableBody');
    const saveAllButton = $('#saveAllAddons');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    // Disable all input fields by default, except price fields
    tableBody.find('input').not('[name$="[price]"]').prop('disabled', true);

    // Handle "Edit" button click
    tableBody.on('click', '.btn-edit', function () {
        const row = $(this).closest('tr');
        enableRowInputs(row);
        updateActionButtons(row, 'edit');
    });

    // Handle "Update" and "Cancel" button clicks
    tableBody.on('click', '.btn-update, .btn-cancel', function () {
        const row = $(this).closest('tr');
        if ($(this).hasClass('btn-update')) {
            updateAddon(row);
        } else {
            cancelEdit(row);
        }
    });

    // Handle "Save All" button click
    saveAllButton.on('click', function () {
        const allRowsData = getAllRowsData();
        if (allRowsData.length === 0) {
            alert('No rows have been modified.');
            return;
        }
        saveAllAddons(allRowsData);
    });

    // Add new row
    $('#addNewAddonRow').on('click', function () {
        const newRow = createNewRow();
        tableBody.append(newRow);
    });

    // Handle "Delete" button click for both existing and new rows
    tableBody.on('click', '.remove-addon-row, .remove-addon-new', function () {
        const row = $(this).closest('tr');
        if ($(this).hasClass('remove-addon-new')) {
            row.remove();
        } else {
            deleteAddon(row);
        }
    });

    // Enable inputs for a row
    function enableRowInputs(row) {
        row.find('input').prop('disabled', false);
        row.addClass('edited');
    }

    // Reset inputs and action buttons for a row
    function cancelEdit(row) {
        row.find('input').prop('disabled', true);
        row.removeClass('edited');
        resetActionButtons(row);
        alert('Changes have been discarded.');
    }

    // Update action buttons
    function updateActionButtons(row, mode) {
        const actionsCell = row.find('td:last-child');
        if (mode === 'edit') {
            actionsCell.html(`
                <button type="button" class="btn btn-success btn-sm btn-update">
                    <i class="fas fa-check"></i>
                </button>
                <button type="button" class="btn btn-secondary btn-sm btn-cancel">
                    <i class="fas fa-times"></i>
                </button>
            `);
        } else {
            resetActionButtons(row);
        }
    }

    // Reset action buttons to default
    function resetActionButtons(row) {
        const actionsCell = row.find('td:last-child');
        actionsCell.html(`
            <button type="button" class="btn btn-primary btn-sm btn-edit">
                <i class="fas fa-edit"></i>
            </button>
            <button type="button" class="btn btn-danger btn-sm remove-addon-row">
                <i class="fas fa-trash-alt"></i>
            </button>
        `);
    }

    // Update a single addon via AJAX
    function updateAddon(row) {
        const data = getRowData(row);
        if (!data) return;

        row.find('input').prop('disabled', true);
        resetActionButtons(row);

        $.ajax({
            url: '{{ route('store.addon') }}',
            method: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify(data),
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function (response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Failed to update addon.');
                }
            },
            error: function () {
                alert('An error occurred while updating the addon.');
            }
        });
    }

    // Get data for all rows (existing and new), only including edited rows
    function getAllRowsData() {
        const allRowsData = [];
        tableBody.find('tr.edited').each(function () {
            const row = $(this);
            const data = getRowData(row);
            if (data) allRowsData.push(data);
        });
        return allRowsData;
    }

    // Get data from a row
    function getRowData(row) {
        const id = row.attr('row-id');
        
        // Select the title and price inputs
        const titleInput = row.find('input[name$="[title]"]');
        const priceInput = row.find('input[name$="[price]"]');

        // Retrieve values and trim
        const title = titleInput.val();
        const price = priceInput.val();
        if (!title) {
        alert('fields must be filled in.');
        return null; // Return null to indicate invalid data
    }
        // Get the status and addon id
        const status = row.find('input[name$="[active]"]').is(':checked') ? 'active' : 'inactive';
        const defaultAddonId = row.data('addon-id') || 0;

        return {
            id: id || 0,
            post_id: tableBody.data('post-id'),
            default_addon_id: defaultAddonId,
            title,
            amount: price,
            status
        };
    }

    // Save all addons via AJAX
    function saveAllAddons(data) {
        $.ajax({
            url: '{{ route('store.addons') }}',
            method: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({ addons: data }),
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function (response) {
                if (response.success) {
                    alert('All changes have been saved.');
                    location.reload();
                } else {
                    alert(response.message || 'Failed to save changes.');
                }
            },
            error: function () {
                alert('An error occurred while saving the changes.');
            }
        });
    }

    // Delete an addon via AJAX
    function deleteAddon(row) {
        const url = row.find('.remove-addon-row').data('url');
        if (!url) return;

        $.ajax({
            url: url,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function () {
                row.remove();
            },
            error: function () {
                alert('Failed to delete the addon.');
            }
        });
    }

    // Create a new row for adding an addon
    function createNewRow() {
        
        return `
            <tr row-id="0" class="edited">
                <td class="text-center">
                    <input type="checkbox" name="addons[new][active]" value="1" checked>
                </td>
                <td>
                    <input type="text" name="addons[new][title]" class="form-control" placeholder="{{ t('title') }}">
                </td>
                <td class="text-center">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{!! config('currency')['symbol'] !!}</span>
                        </div>
                        <input name="addons[new][price]" class="form-control" type="number" min="0" placeholder="{{ t('price') }}">
                    </div>
                </td>
                <td class="text-center">
                <button type="button" class="btn btn-success btn-sm btn-update">
                    <i class="fas fa-check"></i>
                </button>
                    <button type="button" class="btn btn-danger btn-sm remove-addon-new">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        `;
    }
});

</script>

@endsection

