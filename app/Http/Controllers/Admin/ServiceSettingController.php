<?php
/**
 * LaraClassified - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
 */

namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\ServiceSettingRequest as StoreRequest;
use App\Http\Requests\Admin\ServiceSettingRequest as UpdateRequest;

class ServiceSettingController extends PanelController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        $this->xPanel->setModel('App\Models\ServiceSettings');
        $this->xPanel->setRoute(admin_uri('service_settings'));
        $this->xPanel->setEntityNameStrings(trans('admin.service_configuration_Setting'), trans('admin.service_configuration_Setting'));


        // Filters
        // -----------------------
        $this->xPanel->disableSearchBar();
        $this->xPanel->removeButton('delete');
        $this->xPanel->removeButton('create');
        // -----------------------
        $this->xPanel->addFilter([
            'name'  => 'setting_key',
            'type'  => 'text',
            'label' => mb_ucfirst(trans('admin.setting_key')),
        ],
            false,
            function ($value) {
                $this->xPanel->addClause('where', function ($query) use ($value) {
                    $query->where('setting_key', 'LIKE', "%$value%");
                });
            });
        // -----------------------
        $this->xPanel->addFilter([
            'name'  => 'status',
            'type'  => 'dropdown',
            'label' => trans('admin.Status'),
        ], [
            1 => trans('admin.Activated'),
            2 => trans('admin.Unactivated'),
        ], function ($value) {
            if ($value == 1) {
                $this->xPanel->addClause('where', 'active', '=', 1);
            }
            if ($value == 2) {
                $this->xPanel->addClause('where', function ($query) {
                    $query->where(function ($query) {
                        $query->where('active', '!=', 1)->orWhereNull('active');
                    });
                });
            }
        });

        /*
        |--------------------------------------------------------------------------
        | COLUMNS AND FIELDS
        |--------------------------------------------------------------------------
        */
        // COLUMNS
        $this->xPanel->addColumn([
            'name'  => 'id',
            'label' => '',
            'type'  => 'checkbox',
            'orderable' => false,
        ]);
        $this->xPanel->addColumn([
            'name'          => 'setting_key',
            'label'         => trans('admin.setting_key'),
        ]);
        $this->xPanel->addColumn([
            'name'  => 'setting_value',
            'label' => mb_ucfirst(trans('admin.setting_value')),
        ]);
        $this->xPanel->addColumn([
            'name'          => 'active',
            'label'         => trans('admin.Active'),
            'type'          => "model_function",
            'function_name' => 'getActiveHtml',
            'on_display'    => 'checkbox',
        ]);

        $this->xPanel->addField([
            'name'       => 'setting_key',
            'label'      => mb_ucfirst(trans('admin.setting_key')),
            'type'       => 'hidden',
            'attributes' => [
                'placeholder' => mb_ucfirst(trans('admin.setting_key')),
            ],
        ]);

        $wysiwygEditor = config('settings.other.wysiwyg_editor');
        $wysiwygEditorViewPath = '/views/vendor/admin/panel/fields/' . $wysiwygEditor . '.blade.php';
        $this->xPanel->addField([
            'name'       => 'setting_value',
            'label'      => trans('admin.setting_value'),
            'type'       => ($wysiwygEditor != 'none' && file_exists(resource_path() . $wysiwygEditorViewPath))
                ? $wysiwygEditor
                : 'textarea',
            'attributes' => [
                'placeholder' => trans('admin.setting_value'),
                'id'          => 'settingValue',
                'rows'        => 20,
            ],
        ]);

        $this->xPanel->addField([
            'name'  => 'active',
            'label' => trans('admin.Active'),
            'type'  => 'checkbox',
        ]);
    }

    public function store(StoreRequest $request)
    {
        return parent::storeCrud();
    }

    public function update(UpdateRequest $request)
    {
        return parent::updateCrud();
    }
}