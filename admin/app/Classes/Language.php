<?php
namespace Appsolute\Backend\Classes;

class Language 
{
	protected $language;

	public function __construct($default){
		if (!is_string($default)) {
			throw new Exception('Not a string.');
		}
		$this->language = $default;
	}

	public function __toString(){
		return $this->get();
	}

	public function get(){
		return $this->language;
	}

	public function set($language){
		if (!is_string($language)) {
			throw new Exception('Not a string.');
		}
		$this->language = $language;
	}
}