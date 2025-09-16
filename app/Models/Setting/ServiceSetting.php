<?php

namespace App\Models\Setting;

use App\Models\Setting\Traits\WysiwygEditorsTrait;

class ServiceSetting
{
    use WysiwygEditorsTrait;

    public static function getValues($value, $disk)
    {
        if (empty($value)) {

            $value['calendar_help_text'] = '';
            $value['time_help_text'] = '';
            $value['buffer_help_text'] = '';
            $value['inventory_help_text'] = '';
            $value['cancellation_help_text'] = '';
            $value['package_type_help_text'] = '';

        } else {

            if (!isset($value['calendar_help_text'])) {
                $value['calendar_help_text'] = '';
            }
            if (!isset($value['time_help_text'])) {
                $value['time_help_text'] = '';
            }
            if (!isset($value['buffer_help_text'])) {
                $value['buffer_help_text'] = '';
            }
            if (!isset($value['inventory_help_text'])) {
                $value['inventory_help_text'] = '';
            }
            if (!isset($value['cancellation_help_text'])) {
                $value['cancellation_help_text'] = '';
            }
            if (!isset($value['package_type_help_text'])) {
                $value['package_type_help_text'] = '';
            }

        }

        return $value;
    }

    public static function setValues($value, $setting)
    {
        return $value;
    }

    public static function getFields($diskName)
    {
        $fields = [
            [
                'name' => 'calendar_help_text',
                'label' => trans('admin.calendar_help_text'),
                'type' => 'textarea',
                'attributes' => [
                    'rows' => '6',
                ],
                'hint' => trans('admin.calendar_help_text'),
            ],
            [
                'name' => 'time_help_text',
                'label' => trans('admin.time_help_text'),
                'type' => 'textarea',
                'attributes' => [
                    'rows' => '6',
                ],
                'hint' => trans('admin.time_help_text'),
            ],
            [
                'name' => 'buffer_help_text',
                'label' => trans('admin.buffer_help_text'),
                'type' => 'textarea',
                'attributes' => [
                    'rows' => '6',
                ],
                'hint' => trans('admin.buffer_help_text'),
            ],
            [
                'name' => 'inventory_help_text',
                'label' => trans('admin.inventory_help_text'),
                'type' => 'textarea',
                'attributes' => [
                    'rows' => '6',
                ],
                'hint' => trans('admin.inventory_help_text'),
            ],
            [
                'name' => 'cancellation_help_text',
                'label' => trans('admin.cancellation_help_text'),
                'type' => 'textarea',
                'attributes' => [
                    'rows' => '6',
                ],
                'hint' => trans('admin.cancellation_help_text'),
            ],
            [
                'name' => 'package_type_help_text',
                'label' => trans('admin.package_type_help_text'),
                'type' => 'textarea',
                'attributes' => [
                    'rows' => '6',
                ],
                'hint' => trans('admin.package_type_help_text'),
            ],
        ];

        return $fields;
    }
}
