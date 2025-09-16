<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MonthlyImageRequest as UpdateRequest;
use Larapen\Admin\app\Http\Controllers\PanelController;

class SliderImageController extends PanelController
{
    public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\SliderImage');
		
		$this->xPanel->setRoute(admin_uri('monthly-image'));
		$this->xPanel->setEntityNameStrings('Monthly Image', 'Monthly Images');
		$this->xPanel->denyAccess(['create', 'delete']);
		$this->xPanel->disableSearchBar();
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'  => 'id',
			'label' => "ID",
		]);
		$this->xPanel->addColumn([
			'name'  => 'month',
			'label' => 'Month Name',
		]);
		$this->xPanel->addColumn([
			'name'          => 'url',
			'label'         => 'Uploaded Image',
			'type'          => 'model_function',
			'function_name' => 'getUploadedImageHtml',
		]);
		
		// FIELDS
		$this->xPanel->addField([
			'name'              => 'month',
			'label'             => 'Month',
			'type'              => 'text',
			'attributes'        => [
				'placeholder' => 'Month',
				'readonly' => true,
			],
			'wrapperAttributes' => [
				'class' => 'col-md-12',
			],
		]);

		$this->xPanel->addField([
			'name'              => 'image_flag',
			'label'             => 'Upload File',
			'type'              => 'checkbox_switch',
			'wrapperAttributes' => [
				'class' => 'col-md-12 mt-3',
				'id' 		=> 'image_flag',
				'onchange'  => 'toggleUploadField(this)'
			]
		]);


		$entity = $this->xPanel->getModel()->find(request()->segment(3));
		if (!empty($entity)) {
			$this->xPanel->addField([
				'name'  => 'javascript',
				'type'  => 'custom_html',
				'value' => '<script>
				function toggleUploadField(image_flag)
				{
					if(confirm("Are you sure you want to switch the upload image type?"))
					{
						if(!image_flag.querySelector("input[type=checkbox]").checked)
						{
							var upload_image = document.querySelector("#upload_image");
							document.querySelector("input[name=url]").name = "upload_image";
							upload_image.style = "display:none;";
							var url_image = document.querySelector("#url_image");
							document.querySelector("input[name=url_image]").name = "url";
							url_image.style = "";
							
						}
						else
						{
							var url_image = document.querySelector("#url_image");
							document.querySelector("input[name=url]").name = "url_image";
							url_image.style = "display:none;";
							var upload_image = document.querySelector("#upload_image");
							document.querySelector("input[name=upload_image]").name = "url";
							upload_image.style = "";
							
						}
					}
					else
					{
						image_flag.querySelector("input[type=checkbox]").checked = !image_flag.querySelector("input[type=checkbox]").checked;
					}
				}
				</script>
				'
		]);
			$upload_image = ['url', ''];
			$url_image = ['url_image', 'display:none;'];
			if ((!$entity->image_flag)) {
				$upload_image = ['upload_image', 'display:none;'];
				$url_image = ['url', ''];
			}
			
			$this->xPanel->addField([
				'name'              => $url_image[0],
				'label'             => 'Image URL',
				'type'              => 'text',
				'attributes'        => [
					'placeholder' => 'Image URL',
				],
				'wrapperAttributes' => [
					'class' => 'col-md-12',
					'id' => 'url_image',
					'style' => $url_image[1]
				],
			]);

			$this->xPanel->addField([
				'name'   => $upload_image[0],
				'label'  => 'Image'. ' (Supported file extensions: jpg, jpeg, png, gif)',
				'type'   => 'image',
				'upload' => true,
				'disk'   => 'public',
				'wrapperAttributes' => [
					'style' => $upload_image[1],
					'id' => 'upload_image'
				],
			]);

			$this->xPanel->addField([
				'name'              => 'height',
				'label'             => 'Height',
				'type'              => 'number',
				'attributes'        => [
					'placeholder' => '450px',
					'min'         => 50,
					'max'         => 2000,
					'step'        => 1,
				],
				'hint'              => 'Enter a value greater than 50px - Example 400px',
				'wrapperAttributes' => [
					'class' => 'col-md-12',
				],
			]);
		}

	}
	
	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
