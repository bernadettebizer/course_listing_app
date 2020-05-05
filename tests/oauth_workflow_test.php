<?php

require_once('oauth_workflow.php');
require_once('oauth_storage.php');
require_once('config.php');
require_once('users.php');

use PHPUnit\Framework\TestCase;

class RemoteConnectTest extends TestCase {

    protected $storage;
    protected $config;
    protected $user;

	public function setup() :void 
	{
		$this->storage = new OauthStorage();;
		$this->config = new ConfigSettings();

		$post = [
			'user_id' => '12345::0987654321',
			'launch_presentation_return_url' => 'https://test.schoology.com/external_tool',	
		];
		$this->user = new UserAccount(NULL, $post, NULL);
	}

	public function test_have_access_token() {
		$oauth_workflow = new OauthWorkflow($this->storage, $this->config, $this->user);
		$this->assertFalse($oauth_workflow->have_access_token());
	}
}