<?php

namespace Appsolute\Api\Models\Resource;

use ReflectionClass;
use RedBeanPHP\SimpleModel;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;

class Resource extends SimpleModel {

	private $data;
	private $modelName;

	public function __construct( $data = null, $modelName = null ) {
		$this->data = $data;
		$this->modelName = $modelName;
	}

	public function toArray( $collection = false ) {
		$manager = new Manager();
		if (isset($_GET['include'])) {
		    $manager->parseIncludes($_GET['include']);
		}
		
		$manager->setSerializer(new ArraySerializer());
		if(!$collection){
			$transformerName = explode('\\', get_called_class());
			$transformerClass = (new ReflectionClass('\\Appsolute\\Api\\Models\\Transformer\\'.end($transformerName).'Transformer'))->newInstance();
			$resource = new Item($this->bean, $transformerClass);
		} else {
			$transformerClass = (new ReflectionClass('\\Appsolute\\Api\\Models\\Transformer\\'.$this->modelName.'Transformer'))->newInstance();
			$resource = new Collection($this->data, $transformerClass);
		}
		return $manager->createData($resource)->toArray();

	}

}