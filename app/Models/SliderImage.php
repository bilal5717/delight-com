<?php

namespace App\Models;

use App\Helpers\Files\Storage\StorageDisk;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Larapen\Admin\app\Models\Traits\Crud;

class SliderImage extends BaseModel
{
    use Crud, HasFactory;
    protected $fillable = ['month', 'url', 'image_flag', 'height'];

    public function getUploadedImageHtml()
	{
		$out = null;
		if (!empty($this->url)) {
			$style = ' style="max-width:200px; max-height:90px;"';
			$image_url = $this->url;
			if ($this->attributes['image_flag']) {
				$image_url = asset('storage/' . $this->url);
			}
			// Get logo
			$out = '<img src="' . $image_url . '" data-bs-toggle="tooltip" title="' . $this->month . '"' . $style . '>';
		}
		
		
		return $out;
	}
	
	public function getUrlAttribute()
	{
		if (!isset($this->attributes) || !isset($this->attributes['url'])) {
			return null;
		}
		
		$value = $this->attributes['url'];
		if($this->attributes['image_flag'])
		{
			$path = $value;
			$value = str_replace(env('APP_URL') .'/storage', "", $path);
			if (!file_exists(str_replace(env('APP_URL') .'/', "", $path))) {
				$value = config('larapen.core.picture.default');
			}
		}
		
		return $value;
	}

    public function setUrlAttribute($value)
	{
		$attribute_name = 'url';
		if (!$this->attributes['image_flag']) {
			$this->attributes[$attribute_name] = $value;
			return $this->attributes[$attribute_name];
		}

		
		$disk = StorageDisk::getDisk();
		
		if (!isset($this->month) || !isset($this->id)) {
			$this->attributes[$attribute_name] = null;
			
			return false;
		}
		
		// Path
		$destination_path = 'files/' . strtolower($this->month) . '/' . $this->id;
		
		// If the image was erased
		if (empty($value)) {
			// delete the image from disk
			if (!Str::contains($this->{$attribute_name}, config('larapen.core.picture.default'))) {
				$disk->delete($this->{$attribute_name});
			}
			
			// set null in the database column
			$this->attributes[$attribute_name] = null;
			
			return false;
		}
		
		// Check the image file
		if ($value == url('/')) {
			$this->attributes[$attribute_name] = null;
			
			return false;
		}
		
		// If laravel request->file('filename') resource OR base64 was sent, store it in the db
		try {
			if (fileIsUploaded($value)) {
				// Get file extension
				$extension = getUploadedFileExtension($value);
				if (empty($extension)) {
					$extension = 'jpg';
				}
				
				// Image quality
				$imageQuality = 100;
				
				// Image default dimensions
				$width = (int)config('larapen.core.picture.otherTypes.bgHeader.width', 2000);
				$height = (int)config('larapen.core.picture.otherTypes.bgHeader.height', 1000);
				
				// Init. Intervention
				$image = Image::make($value);
				
				// Get the image original dimensions
				$imgWidth = (int)$image->width();
				$imgHeight = (int)$image->height();
				
				// Fix the Image Orientation
				if (exifExtIsEnabled()) {
					$image = $image->orientate();
				}
				
				// If the original dimensions are higher than the resize dimensions
				// OR the 'upsize' option is enable, then resize the image
				if ($imgWidth > $width || $imgHeight > $height) {
					// Resize
					$image = $image->resize($width, $height, function ($constraint) {
						$constraint->aspectRatio();
					});
				}
				
				// Encode the Image!
				$image = $image->encode($extension, $imageQuality);
				
				// Generate a filename.
				$filename = md5($value . time()) . '.' . $extension;
				if($image->mime() == "image/gif") {
					// Store the image on disk.
					$disk->put($destination_path . '/' . $filename,  base64_decode(Str::replaceFirst('data:image/gif;base64,', '', $value)));
				} else {
					// Store the image on disk.
					$disk->put($destination_path . '/' . $filename, $image->stream()->__toString());
				}
				
				// Save the path to the database
				$this->attributes[$attribute_name] = asset('storage/' . $destination_path . '/' . $filename);

				return $this->attributes[$attribute_name];
			}
		} catch (\Exception $e) {
			flash($e->getMessage())->error();
			$this->attributes[$attribute_name] = null;
			
			return false;
		}
	}

}
