<?php

require_once('oauth_storage.php');
require_once('App_OauthStorage.class.php');

use PHPUnit\Framework\TestCase;

class RemoteConnectTest extends TestCase {

    protected $storage;
    protected $db_connection;

	public function setup() :void 
	{
		$this->storage = OauthStorageFactory::create();
		$this->db_connection = $this->storage->get_database_connection();
	}

	public function test_get_no_stored_token() {
		$fake_uid = 'abcdef';
		$this->assertEmpty($this->db_connection->getAccessTokens($fake_uid));
	}

	public function test_get_stored_token() {
		$test_uid = '12345';
		$test_secret = 'qwerty';
		$test_key = 'asdfg';
		$this->db_connection->saveRequestTokens($test_uid, $test_key, $test_secret);
		
		//asserts this was not stored as an Access Token
		$this->assertEmpty($this->db_connection->getAccessTokens($test_uid));

		//asserts this is stored correctly as a Request Token
		$this->assertSame($this->db_connection->getRequestTokens($test_uid)['token_key'], $test_key);
	}
}