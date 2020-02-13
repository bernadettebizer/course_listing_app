<?php

require_once('lib.php');

use PHPUnit\Framework\TestCase;

class RemoteConnectTest extends TestCase {

	public function test_user_api_information_form_validate() {
		$uid = '12345';
		$key = '67890';
		$secret = '1234567890';
		$form_data = ['uid'=>$uid, 'key'=>$key, 'secret'=>$secret];
		
		$this->assertTrue(user_api_information_form_validate($form_data));
	}

	public function test_user_api_information_form_validate_incomplete_form_data() {
		$uid = '12345';
		$key = '';
		$secret = '1234567890';
		$form_data = ['uid'=>$uid, 'key'=>$key, 'secret'=>$secret];
	
		$this->assertFalse(user_api_information_form_validate($form_data));
	}
}