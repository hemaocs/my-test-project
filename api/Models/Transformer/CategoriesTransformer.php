<?php

namespace Appsolute\Api\Models\Transformer;

use RedbeanPHP\OODBBean;
use League\Fractal;

class CategoriesTransformer extends Fractal\TransformerAbstract {

	public function transform(OODBBean $category) {
		
		if (!empty($category->created_at)) {
			$createAt = date('Y-m-d\TH:i:s\Z', strtotime($category->created_at));
		} else {
			$createAt = null;
		}

		if (file_exists(UPLOAD_FOLDER.'category/'.$category->image) && !empty($category->image)) {
			$image = UPLOAD_URL.'category/'.$category->image;
		} else {
			$image = NULL;
		}

		return [
			'id'      	   => (int) $category->id,
			'name'         => $category->name,
			'image'        => $image,
			'created_at'   => $createAt,
	    ];
	}
}