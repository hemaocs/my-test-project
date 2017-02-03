<?php

namespace Appsolute\Api\Models\Transformer;

use RedbeanPHP\OODBBean;
use League\Fractal;
use Appsolute\Api\Models\Reserves;

class ProductsTransformer extends Fractal\TransformerAbstract {

	public function transform(OODBBean $product) { //print"<pre>";print_r($product);exit();
		$reserve = new Reserves();
		
		if (!empty($product->created_at)) {
			$createAt = date('Y-m-d\TH:i:s\Z', strtotime($product->created_at));
		} else {
			$createAt = null;
		}

		if (file_exists(UPLOAD_FOLDER.'products/'.$product->image) && !empty($product->image)) {
			$image = UPLOAD_URL.'products/'.$product->image;
		} else {
			$image = NULL;
		}

	    $resource = [
            'id'      	   => (int) $product->id,
			'name'         => $product->name,
			'image'        => $image,
			'created_at'   => $createAt,
			'is_default'   => $product->is_default
		];

		return $resource;
	}
}