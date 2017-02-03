<?php

namespace Appsolute\Backend\Controllers\Products;

use Slim\Slim;
use Appsolute\Config;
use Appsolute\Config\Keys;
use Appsolute\Config\Database;
use Appsolute\Backend\Controllers;
use Appsolute\Backend\Models\Products;
use Appsolute\Backend\Models\Users;
use Appsolute\Backend\Models\Categories;
use Appsolute\Backend\Classes\Utility;

use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Recipient\Device;
use paragraph1\phpFCM\Notification;

require_once BASE_FOLDER.'vendor/appsolute/push/src/Push.php';
require_once BASE_FOLDER.'vendor/autoload.php';

use Appsolute\Push;
use Appsolute\Push\Interfaces;

Push\Push::autoloader();

Class PostController extends Controllers\Controller {

	public function add() {
		$res = $result = array();
		$users = new Users($this->configManager('Database'));
		$products = new Products($this->configManager('Database'));
		$categories = new Categories($this->configManager('Database'));
		$certs = new Database();
		$keys = new Keys();
		$utility = new Utility\Utility();
		$months = $utility->doGetMonths();
		if (!empty($months)) {
            $i = 1;
            foreach ($months as $key => $value) {
            	$res['month_no'] = $i;
            	$res['month_en'] = $key;
            	$res['month_fr'] = $value;
            	$i++;
            	$result[] = $res;
            }
		}
		$this->data['months'] = $result;
		$this->data['categories'] = $categories->getAll();
		$this->data['parentChildCats'] = $categories->getParentChildCats();
		$certificates = $certs->getAllCertificates();
		$this->data['retry'] = $this->app->request->post();
		$insertedProducts = $products->insert($this->app->request->post());
		if(!empty($insertedProducts)) {
			$apnsDeviceTokens = $users->getAllDeviceTokens();
			if (!empty($apnsDeviceTokens)) {
                foreach($apnsDeviceTokens as $apnsDevice) {
                	$receiverDetails = $users->getuserName($apnsDevice['user_id']);
            	    $username = $receiverDetails['firstname'].''.$receiverDetails['lastname'];
            	    $senderAry[] = $username;
            	   if (!empty($senderAry)) {
	            	    $msg = $username.' vous a envoyé un message';
	            	    $locKey = "PUSH_MESSAGE_NOTIFICATION_KEY";
	            	} else {
	            		$msg = $username.' vous a envoyé un toc toc';
	            		$locKey = "PUSH_TOTTOC_NOTIFICATION_KEY";
	            	}

                    if ($apnsDevice['platform'] == 'ios') {
                    	if (in_array($apnsDevice['app_id'], $certificates)) {
							try {
								$notification = new Push\Notification();
								$notification->setTitle("Project");
								$notification->setBody($msg);
								$notification->setSound("my-sound.aiff");
								$notification->setBadge("1");
								
								$notification->setLocKey($locKey);
								$notification->setlocArgs($senderAry);
								
								$options = new Push\Options("3600"); //Optionnal
								//You must implements the Database interface and call it in adapter
								$device = new Push\Adapters\ApnsAdapter(new Interfaces\DatabaseInterface());
								
								//Here you should get the app id of the token
							    //Use that app id to select the correct folder
							    $app_id = $apnsDevice['app_id'];
								
								// Get the passphrase for the current app id
								$devPassphrase = $keys->getPassphraseIOS($app_id, 'dev');
								$prodPassphrase = $keys->getPassphraseIOS($app_id, 'prod');

								// Send the push by choosing the correct certificate according to the app id
								$device->addService('dev', CERT_FOLDER.$app_id.'/apns-dev.pem', $devPassphrase, 'dev');
								$device->addService('prod', CERT_FOLDER.$app_id.'/apns-prod.pem', $prodPassphrase, 'prod');
								
								$device->registerToken( $apnsDevice['token'], $apnsDevice['mode'] );
								
								$push = new Push\Push();
								$push->setPayload(array(
									'push_type'   => 'message',
									'receiver'    => $apnsDevice['user_id'], 
									'productname' => $insertedProducts['id'];
									'username'    => $username
								));
								$push->setNotification($notification);
								$push->setOptions($options);
								$push->setAdapter($device);
								$push->send();
							} catch(\Appsolute\Push\Exceptions\PushException $e){
								//Handle exceptions here
							}
					    }
				    } elseif ($apnsDevice['platform'] == 'android') {
					    $client = new Client();
						$client->setApiKey(API_KEY);
						$client->injectHttpClient(new \GuzzleHttp\Client());

						$note = new Notification('Project', $msg);
						$note->setBadge("1");

						$note->setLocKey($locKey);
						$note->setLocArgs($senderAry);

						$message = new Message();
						$message->addRecipient(new Device($apnsDevice['token']));
					
						$message->setNotification($note)
						    ->setData(array(
						    	'push_type'  => 'message', 
						    	'receiver'   => $apnsDevice['user_id'], 
						    	'productId'  => $insertedProducts['id'];
						    	'username'   => $username
						));
						$response = $client->send($message);
				    }
                }
            }
		}
		$this->app->errors += $products->getErrors();
	}

	public function changeStatus() {
		$products = new Products($this->configManager('Database'));
		$changeProducts = $products->getDefaultChange($this->args['default'],$this->args['id']);
		echo $changeProducts; exit();
	}

	public function uploadImage() {
		$products = new Products();
		$this->data['upload_image'] = $products->doUploadImage();
	}

	public function cropImage() {
		$products = new Products();
		$this->data['upload_image'] = $products->doUploadCropImage($this->app->request->post());	
	}

}