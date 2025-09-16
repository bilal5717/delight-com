<?php

namespace App\Http\Controllers\Admin;

use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\PostTypeRequest as StoreRequest;
use App\Http\Requests\Admin\PostTypeRequest as UpdateRequest;

class ProductTypeController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\ProductType');
		$this->xPanel->setRoute(admin_uri('product_types'));
		$this->xPanel->setEntityNameStrings(trans('admin.product type'), trans('admin.product types'));
		//$this->xPanel->denyAccess(['create', 'delete']);
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'  => "id",
			'label' => "ID",
		]);
		$this->xPanel->addColumn([
			'name'  => "name",
			'label' => trans('admin.Name'),
		]);

        $this->xPanel->addColumn([
            'name'  => "description",
            'label' => trans('admin.Description'),
        ]);

		// FIELDS
		$this->xPanel->addField([
			'name'       => "name",
			'label'      => trans('admin.Name'),
			'type'       => "text",
			'attributes' => [
				'placeholder' => trans('admin.Name'),
			],
		]);

        $this->xPanel->addField([
            'name'       => 'description',
            'label'      => trans('admin.Description'),
            'type'       => 'textarea',
            'attributes' => [
                'placeholder' => trans('admin.Description'),
            ],
        ]);
	}
	
	public function store(UpdateRequest $request)
	{
		return parent::storeCrud();
	}
	
	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
