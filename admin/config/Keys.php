<?php

namespace Appsolute\Config;

Class Keys implements ConfigManagerInterface {

	private $passphrases = array(
		'com.project.projectapp' =>	array(
			'dev'	=> 'project123',
			'prod'	=> 'project123'
		),
        'fr.appsolute.projectinapp'	=>	array(
			'dev'	=> 'U3BlYWtIb3RlbA',
			'prod'	=> 'U3BlYWtIb3RlbA'
		),
        'fr.appsolute.projectinapp.jean'	=>	array(
			'dev'	=> '38UuyGOUpP%CwI0Ta',
			'prod'	=> '38UuyGOUpP%CwI0Ta'
		)
	);

	private $gcm_key = "";

	public function getPassphraseIOS($appId, $keyName){
		if(array_key_exists($appId, $this->passphrases)){
			return $this->passphrases[$appId][$keyName];
		} else {
			throw new \Exception("The '{$keyName}' (app_id : {$appId}) certificate is not registered.");
		}
	}

	public function getGcmKey(){
		return $this->gcm_key;
	}

}