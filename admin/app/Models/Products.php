<?php

namespace Appsolute\Backend\Models;

use R;
use Appsolute\Backend\Classes\Auth\Auth as AuthClass;
use Appsolute\Backend\Models\Validation\ValidationException;
use Appsolute\Backend\Classes\Utility;
use Appsolute\Backend\Classes\Resize;

Class Products extends Database {

	public function getProdByCat($catId) {
		try {
			$products = R::getAll( 'SELECT T.id,T.`name`,T.`image`,TT.`product_id`, TT.`category_id`
		                            FROM '.$this->config->getTable('products').' T
									INNER JOIN '.$this->config->getTable('product_category').' TT
									ON T.id=TT.product_id
									AND T.`deleted_at` IS NULL
									AND TT.`category_id` IN (SELECT id FROM '.$this->config->getTable('categories').' WHERE `id` = :id)
									GROUP BY T.id',array(':id' => $catId));
			$res = $result = array();
			if(!empty($products)) {
				foreach($products as $product) {
					$res['product_id']= $product['product_id'];
					$res['name']= $product['name'];
					if(!empty($product['image']) && file_exists(UPLOAD_FOLDER.'products/'.$product['image'])) {
						$res['image']	=	UPLOAD_URL.'products/'.$product['image'];
					} else {
						$res['image']	=	null;
					}
					$result[] = $res;
				}
			}
			return $result;
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }

    public function getAll($filter=null) { 
		try { 
			if(isset($filter['filterby']) && !empty($filter['filterby'])){
				$products = R::getAll( 'SELECT T.id,T.`name`,T.`image`,T.`is_default`,TT.`product_id`, TT.`category_id`
			                            FROM '.$this->config->getTable('products').' T
										INNER JOIN '.$this->config->getTable('product_category').' TT
										ON T.id=TT.product_id
										AND T.`deleted_at` IS NULL
										AND TT.`deleted_at` IS NULL
										AND TT.`category_id` IN (SELECT id FROM '.$this->config->getTable('categories').' WHERE `id` = :id)
										GROUP BY T.id',array(':id' => $filter['filterby']));
			} else {
				$products = R::getAll("SELECT `id`,`name`,`image`,`is_default` FROM `".$this->config->getTable('products')."` WHERE `deleted_at` IS NULL");
			}
			$res = $result = array();
			if(!empty($products)) {
				foreach($products as $product) {
					if(isset($filter['filterby']) && !empty($filter['filterby'])){
                        $prodId = $product['product_id'];
					} else {
						$prodId = $product['id'];
					}
					$category = R::getRow("SELECT A.`id`, A.`name` FROM `categories` AS A, `product_category` AS B 
						                   WHERE A.`id` = B.`category_id` AND B.`product_id` = :prodId 
						                   AND A.`deleted_at` IS NULL AND B.`deleted_at` IS NULL", array( ':prodId' => $prodId ));
					$res['id']=  $product['id'];
					$res['name']= $product['name'];
					$res['is_default'] = $product['is_default'];
					$res['category'] = $category['name'];
					if(!empty($product['image']) && file_exists(UPLOAD_FOLDER.'products/'.$product['image'])) {
						$res['image'] = UPLOAD_URL.'products/'.$product['image'];
					} else {
						$res['image'] = null;
					}
					$result[] = $res;
				}
			}
			return $result;
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }


    public function insert( $entry ) {
		try {
			$utility = new Utility\Utility();
			$products = R::xdispense( $this->config->getTable('products') );
			$products->name = $entry['name'];
			if(isset($entry['is_default'])) {
				$products->is_default = "1";
			} else {
				$products->is_default = "0";
			}
			if(!empty($_FILES['picture']['name']) && $_FILES['picture']['error']==0) {
				$getImgSize = getimagesize($_FILES["picture"]['tmp_name']);
				if (($getImgSize[0] >= 456) && ($getImgSize[1] >= 456)) {
					$extension = explode('.', $_FILES['picture']['name']);
					$file_name = uniqid().'.'.end($extension);
					move_uploaded_file($_FILES['picture']['tmp_name'], UPLOAD_FOLDER.'products/'.$file_name);
			        $resizeObj = new Resize(UPLOAD_FOLDER.'products/'.$file_name);
					$resizeObj -> resizeImage(456, 456, 'auto');
					$resizeObj -> saveImage(UPLOAD_FOLDER.'products/'.$file_name,50);
					$products->image = $file_name;
			    } else {
					throw new ValidationException( 'Image width and height should be 456px or greater than 456px.' );
				}
			} else {
				throw new ValidationException( 'You have to provide image.' );
			}		
			$products->created_at = date('Y-m-d H:i:s', strtotime('now'));
			R::store( $products );

			if (!empty($products->id)) {
                if((isset($entry['frmCategoryId'])) && !empty($entry['frmCategoryId'])) {
                    $categoryProd = R::xdispense( $this->config->getTable('product_category'));
					$categoryProd->product_id = $products->id;
					$categoryProd->category_id = $entry['frmCategoryId'];
					$categoryProd->created_at = date('Y-m-d H:i:s', strtotime('now'));
					R::store( $categoryProd );
				}
			}
			$product['id'] = $products['id'];
			$product['name'] = $products['name'];
			return $product;

		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }
	
    public function findOneById( $id ) {
		try {
			$prod = $result = array();
			$product = R::load( $this->config->getTable('products'), $id );
			if(!empty($product)) { 
				$prod['id'] = $product->id;
				$prod['name'] = $product->name;
				if(!empty($product->image) && file_exists(UPLOAD_FOLDER.'products/'.$product->image)) {
					$prod['image'] = UPLOAD_URL.'products/'.$product->image;
					$prod['imgName'] = $product->image;
				}
				$prod['is_default'] = $product->is_default;
				$result = $prod;
			}
			return $result;
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }

    public function update( $id, $entry ) { 
		try {
			$productUpdate = R::load( $this->config->getTable('products'), $id );
			$productUpdate->name = $entry['name'];
			if(isset($entry['is_default'])) {
				$productUpdate->is_default = "1";
			} else {
				$productUpdate->is_default = "0";
			}
            $file_name = null;
			if(!empty($_FILES['picture']['name']) && $_FILES['picture']['error']==0) {
                $getImgSize = getimagesize($_FILES["picture"]['tmp_name']);
				if (($getImgSize[0] >= 456) && ($getImgSize[1] >= 456)) {
	                if (file_exists(UPLOAD_FOLDER.'products/'.$productUpdate->image) && !empty($productUpdate->image)) {
				    	@unlink(UPLOAD_FOLDER.'products/'.$productUpdate->image);
					}
					if (file_exists(UPLOAD_FOLDER.'products/thumb/'.$productUpdate->image) && !empty($productUpdate->image)) {
				    	@unlink(UPLOAD_FOLDER.'products/thumb/'.$productUpdate->image);
					}
					$extension = explode('.', $_FILES['picture']['name']);
					$file_name = uniqid($productUpdate->id).'.'.end($extension);
					move_uploaded_file($_FILES['picture']['tmp_name'], UPLOAD_FOLDER.'products/'.$file_name);
			        $resizeObj = new Resize(UPLOAD_FOLDER.'products/'.$file_name);
					$resizeObj -> resizeImage(456, 456, 'auto');
					$resizeObj -> saveImage(UPLOAD_FOLDER.'products/'.$file_name,50);
					$productUpdate->image = $file_name;
			    } else {
					throw new ValidationException( 'Image width and height should be 456px or greater than 456px.' );
				}
			} elseif(!empty($entry['frmHiddImg'])) {
				$productUpdate->image = $entry['frmHiddImg'];
			} else {
				throw new ValidationException( 'You have to provide image.' );
			}
			$productUpdate->updated_at = date('Y-m-d H:i:s', strtotime('now'));
			R::store( $productUpdate );

			if (!empty($productUpdate->id)) {
                if(!empty($entry['frmCategoryId']) && (isset($entry['frmCategoryId']))) {
                    $categoryProd = R::findOne( $this->config->getTable('product_category'), 'product_id = ? AND `deleted_at` IS NULL' , [ $productUpdate->id ]);
					if (!empty($categoryProd)) {
						$categoryProd->product_id = $productUpdate->id;
						$categoryProd->category_id = $entry['frmCategoryId'];
						$categoryProd->updated_at = date('Y-m-d H:i:s', strtotime('now'));
						R::store( $categoryProd );
				    } else {
				    	$prodCats = R::xdispense( $this->config->getTable('product_category') );
				    	$prodCats->product_id = $productUpdate->id;
						$prodCats->category_id = $entry['frmCategoryId'];
						$prodCats->created_at = date('Y-m-d H:i:s', strtotime('now'));
						R::store( $prodCats );
				    }
				}
			}

		} catch(ValidationException $e) {
			
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }


	 public function delete( $id ) {
		try {
			$productdelete = R::findOne( $this->config->getTable('products'), 'id = ? AND deleted_at IS NULL', [$id] );
			if(!empty($productdelete)){
				$productdelete->deleted_at =  date('Y-m-d H:i:s', strtotime('now'));
				if (file_exists(UPLOAD_FOLDER.'products/'.$productdelete->image) && !empty($productdelete->image)) {
			    	@unlink(UPLOAD_FOLDER.'products/'.$productdelete->image);
				}
				R::store( $productdelete );
			} else {
				throw new ValidationException("This product doesn't exist.");
			}
			
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }

    public function getCatByProduct( $id ) {
		try {
			$result = array();
			$category  = R::findAll( $this->config->getTable('product_category'), ' product_id = :id AND deleted_at IS NULL ', [ ':id' => $id ] );
			$result_cat = R::exportAll($category);
			$results = NULL;
			foreach ($result_cat as $categories) {
			    $result['id'] = $categories['category_id'];
			    $results[] = $result;
		    }
			return $results;
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }

    public function getAllProCats($id) { 
		try {
			$product = R::findOne( $this->config->getTable('product_category'), 'product_id = ? AND deleted_at IS NULL', [ $id ] );
			return $product;
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }

    public function getDefaultChange($default,$id) {
		try {
			$product = R::findOne( $this->config->getTable('products'), 'id = ? AND deleted_at IS NULL', [$id] );
			if(!empty($product)) {
				$product->is_default = $default;
				R::store($product);
			}
			return $product->is_default;
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }

    public function doUploadImage() {
		if(isset($_FILES['file'])) {
			if(!empty($_FILES['file']['name']) && $_FILES['file']['error']==0) {
				$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
				$image_details = getimagesize($_FILES['file']['tmp_name']);
				if(!empty($image_details)) {
					$file_name = time().uniqid().".".$extension;
					move_uploaded_file($_FILES['file']['tmp_name'],	UPLOAD_FOLDER.'tmp/'.$file_name);
					$result['url'] = UPLOAD_URL.'tmp/'.$file_name;
					$result['image_path'] = UPLOAD_FOLDER.'tmp/'.$file_name;
					$result['image_name'] = $file_name;
					return $result;
				}
			}
		}
	}

	public function doUploadCropImage($entry) {
		$img = $entry['data_val'];
		$img = str_replace('data:image/png;base64,', null, $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file_name = date('YmdHis') . '_edited.png';
		$file = UPLOAD_FOLDER."tmp/" . $file_name;
		$file_url = UPLOAD_FOLDER."tmp/" . $file_name;
		$success = file_put_contents($file, $data);

		if(!empty($file_name) && file_exists(UPLOAD_FOLDER.'tmp/'.$file_name)) {
			echo json_encode(array("image_url"=>UPLOAD_URL.'tmp/'.$file_name,"image_name"=>$file_name));
		}
	}

	/*public function getSeasonByProd( $id ) {
		try {
			$prod = $result = array();
			$seasonProds = R::getAll('SELECT * FROM `'.$this->config->getTable('seasonProducts').'` WHERE `product_id` = :prodId ', array('prodId' => $id));
			if(!empty($seasonProds)) {
				foreach ($seasonProds as $season) {
					$prod['product_id'] = $season['product_id'];
					$prod['season_id'] = $season['season_id'];
					$result[] = $prod;
				}
			}
			return $result;
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }*/


}