<?php

namespace Appsolute\Backend\Models;

use R;
use Appsolute\Backend\Classes\Auth\Auth as AuthClass;
use Appsolute\Backend\Models\Validation\ValidationException;
use Appsolute\Backend\Classes\Utility;
use Appsolute\Backend\Classes\Resize;


Class Categories extends Database {

	public function getAll() {
		try {
			$categories = R::findAll( $this->config->getTable('categories'), 'deleted_at IS NULL ORDER BY id ASC' );			
			$result = null;
			if(!empty($categories)) {
				foreach($categories as $category) {
					if(!empty($category['image']) && file_exists(UPLOAD_FOLDER.'category/'.$category['image'])) {
						$category['image']	=	UPLOAD_URL.'category/'.$category['image'];
					} else {
						$category['image']	=	null;
					}
					$result[] = $category;
				}
			}			
			return $result;
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }

    function getParentCategories() {
	    $res = $result = array();
	    $categories = R::getAll("SELECT `id`, `name`, `parent_category` FROM `".$this->config->getTable('categories')."` WHERE `parent_category` IS NULL AND `deleted_at` IS NULL ");
		
		if (!empty($categories)) {
            foreach ($categories as $category) {
            	$res['id'] = $category['id'];
            	$res['name'] = $category['name'];
            	$res['parent_category'] = $category['parent_category'];
            	$result[] = $res;
            }
		}
	    return $result;
	}

    function getParentChildCats() {
	    $res = $result = array();
	    $categories = R::getAll("SELECT `id`, `name`, `parent_category` FROM `".$this->config->getTable('categories')."` WHERE `deleted_at` IS NULL ORDER BY COALESCE(`parent_category`, `id`), `parent_category` IS NOT NULL, `id`");
		
		if (!empty($categories)) {
            foreach ($categories as $category) {
            	$res['id'] = $category['id'];
            	$res['name'] = $category['name'];
            	$res['parent_category'] = $category['parent_category'];
            	if (!empty($category['parent_category'])) {
                    $res['type'] = 'child';
            	} else {
            		$res['type'] = 'parent';
            	}
            	$result[] = $res;
            }
		}
	    return $result;
	}

	public function findOneById( $id ) {
		try {
			$cat = $result = array();
			$category = R::load( $this->config->getTable('categories'), $id );
			if(!empty($category)) {
				$cat['id'] = $category->id;
				$cat['name'] = $category->name;
				if(!empty($category->image) && file_exists(UPLOAD_FOLDER.'category/'.$category->image)) {
					$cat['image'] = UPLOAD_URL.'category/'.$category->image;
					$cat['imgName'] = $category->image;
				}
				$cat['parent_category'] = $category->parent_category;
				$result = $cat;
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
			$categories = R::xdispense( $this->config->getTable('categories') );
			$categories->name = $entry['name'];
			$categories->parent_category = (isset($entry['frmMainCategory']) && !empty($entry['frmMainCategory'])) ? $entry['frmMainCategory'] : NULL;
			$categories->created_at = date('Y-m-d H:i:s', strtotime('now'));
            /*if(isset($entry['frm_image'])) {
				if(!empty($entry['frm_image'])) {
					if(file_exists(UPLOAD_FOLDER.'tmp/'.$entry['frm_image'])) {
						copy(UPLOAD_FOLDER.'tmp/'.$entry['frm_image'],UPLOAD_FOLDER.'category/'.$entry['frm_image']);
						@unlink(UPLOAD_FOLDER.'tmp/'.$entry['frm_image']);
						if(isset($entry['frm_image_original']) && file_exists($entry['frm_image_original'])) { 
							@unlink($entry['frm_image_original']); 
						}
					}
					if(file_exists(UPLOAD_FOLDER.'category/'.$entry['frm_image']) ) {
						$categories->image = $entry['frm_image'];
						if(isset($categories)) {
							$file 	  = UPLOAD_FOLDER.'category/'.$entry['frm_image'];
							$filesize = getimagesize($file);
							if($filesize[0]!=456 && $filesize[1]!=456){
								throw new ValidationException( 'Please crop the image to correct proportion' );
							}
						}
					}
				} else {
					throw new ValidationException( 'You have to provide image.' );
				}
			}*/ 
			if(!empty($_FILES['picture']['name']) && $_FILES['picture']['error']==0) {
				$getImgSize = getimagesize($_FILES["picture"]['tmp_name']);
				if (($getImgSize[0] >= 456) && ($getImgSize[1] >= 456)) {
					$extension = explode('.', $_FILES['picture']['name']);
					$file_name = uniqid().'.'.end($extension);
					move_uploaded_file($_FILES['picture']['tmp_name'], UPLOAD_FOLDER.'category/'.$file_name);
					$resizeObj = new Resize(UPLOAD_FOLDER.'category/'.$file_name);
					$resizeObj -> resizeImage(456, 456, 'auto');
					$resizeObj -> saveImage(UPLOAD_FOLDER.'category/'.$file_name,50);
					$categories->image = $file_name;
			    } else {
			    	throw new ValidationException( 'Image width and height should be 456px or greater than 456px.' );
			    }
			} else {
				throw new ValidationException( 'You have to provide image.' );
			}
			R::store( $categories );
            
		} catch(ValidationException $e) {
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }

    public function update( $id, $entry ) {
		try {
			
			$categoriesUpdate = R::load( $this->config->getTable('categories'), $id );
			$categoriesUpdate->name = $entry['name'];
			$categoriesUpdate->parent_category = (isset($entry['frmMainCategory']) && !empty($entry['frmMainCategory'])) ? $entry['frmMainCategory'] : NULL;		
            $file_name = null;
			/*if(isset($entry['frm_image'] ) && (!empty($entry['frm_image']))) {
				if(!empty($entry['frm_image']) && file_exists(UPLOAD_FOLDER.'tmp/'.$entry['frm_image'])) {
					copy(UPLOAD_FOLDER.'tmp/'.$entry['frm_image'],UPLOAD_FOLDER.'category/'.$entry['frm_image']);
					@unlink(UPLOAD_FOLDER.'tmp/'.$entry['frm_image']);	
					if(isset($entry['frm_image_original'])) { 
						if(!empty($entry['frm_image_original']) && file_exists($entry['frm_image_original'])) {
							unlink($entry['frm_image_original']);
						}
					}
				}
				if(!empty($entry['frm_image']) && file_exists(UPLOAD_FOLDER.'category/'.$entry['frm_image']) ) {
					if(isset($categoriesUpdate->image)) { 
						if(!empty($categoriesUpdate->image) && file_exists(UPLOAD_FOLDER.'category/'.$categoriesUpdate->image)) {
							@unlink(UPLOAD_FOLDER.'category/'.$categoriesUpdate->image);
						}
					}
					$categoriesUpdate->image = $entry['frm_image'];
					if(isset($categoriesUpdate)) {
						$file =  UPLOAD_FOLDER.'category/'.$entry['frm_image'];
						$filesize = getimagesize($file);
						if($filesize[0]!=456 && $filesize[1]!=456){
							throw new ValidationException( 'Please crop the image to correct proportion' );
						}
					}

				}
			} elseif(isset($entry['frmHiddenImg']) && !empty($entry['frmHiddenImg'])) { 
				$categoriesUpdate->image = $entry['frmHiddenImg'];
			} else {
				throw new ValidationException( 'You have to provide image.' );
			}*/
			if(!empty($_FILES['picture']['name']) && $_FILES['picture']['error']==0) {
                $getImgSize = getimagesize($_FILES["picture"]['tmp_name']);
				if (($getImgSize[0] >= 456) && ($getImgSize[1] >= 456)) {
	                if (file_exists(UPLOAD_FOLDER.'category/'.$categoriesUpdate->image) && !empty($categoriesUpdate->image)) {
				    	@unlink(UPLOAD_FOLDER.'category/'.$categoriesUpdate->image);
					}
					if (file_exists(UPLOAD_FOLDER.'category/thumb/'.$categoriesUpdate->image) && !empty($categoriesUpdate->image)) {
				    	@unlink(UPLOAD_FOLDER.'category/thumb/'.$categoriesUpdate->image);
					}
					$extension = explode('.', $_FILES['picture']['name']);
					$file_name = uniqid($categoriesUpdate->id).'.'.end($extension);
					move_uploaded_file($_FILES['picture']['tmp_name'], UPLOAD_FOLDER.'category/'.$file_name);
					$resizeObj = new Resize(UPLOAD_FOLDER.'category/'.$file_name);
					$resizeObj -> resizeImage(456, 456, 'auto');
					$resizeObj -> saveImage(UPLOAD_FOLDER.'category/'.$file_name,50);
					$categoriesUpdate->image = $file_name;
			    } else {
			    	throw new ValidationException( 'Image width and height should be 456px.' );
			    }
			} elseif(!empty($entry['frmHiddImg'])) {
				$categoriesUpdate->image = $entry['frmHiddImg'];
			} else {
				throw new ValidationException( 'You have to provide image.' );
			}
			$categoriesUpdate->updated_at = date('Y-m-d H:i:s', strtotime('now'));
			R::store( $categoriesUpdate );
		} catch(ValidationException $e) {			
			$this->errors[] = $e->getMessage();
			return FALSE;
		}
    }

    
public function delete( $id ) {
	try {
		$categorydelete = R::findOne( $this->config->getTable('categories'), 'id = ? AND deleted_at IS NULL ', [$id] );
		if(!empty($categorydelete)){
			$categorydelete->deleted_at =  date('Y-m-d H:i:s', strtotime('now'));
			if (file_exists(UPLOAD_FOLDER.'category/'.$categorydelete->image) && !empty($categorydelete->image)) {
		    	@unlink(UPLOAD_FOLDER.'category/'.$categorydelete->image);
			}
			R::store( $categorydelete );
			$productdelete = R::getAll( "SELECT * FROM `".$this->config->getTable('categories')."` AS A,`".$this->config->getTable('product_category')."` AS B WHERE A.`id`= B.`category_id` AND B.`category_id` = :catId AND B.`deleted_at` IS NULL", array("catId" => $id) );
			foreach ($productdelete as $prods) {
                $prodDelete = R::findOne( $this->config->getTable('product_category'), 'category_id = ? AND deleted_at IS NULL ', [$prods['category_id']] );
			    if (!empty($prodDelete)) {
			        if (file_exists(UPLOAD_FOLDER.'product/'.$prodDelete->image) && !empty($prodDelete->image)) {
				    	@unlink(UPLOAD_FOLDER.'product/'.$prodDelete->image);
					}
					R::trash($prodDelete);
			    }
			}
		
		} else {
			throw new ValidationException("This category doesn't exist.");
		}
		
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
	$file_name = "me_".date('YmdHis').'_edited.png';
	$file = UPLOAD_FOLDER.'tmp/'.$file_name;
	$file_url = UPLOAD_FOLDER.'tmp/'.$file_name;
	$success = file_put_contents($file, $data);
	if(!empty($file_name) && file_exists(UPLOAD_FOLDER.'tmp/'.$file_name)) {
		echo json_encode(array("image_url"=>UPLOAD_URL.'tmp/'.$file_name,"image_name"=>$file_name));
	}
}


  
}