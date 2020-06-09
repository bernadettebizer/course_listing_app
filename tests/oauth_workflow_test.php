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
    protected $oauth;

	public function setup() :void 
	{
		$this->storage = OauthStorageFactory::create();
		$this->config = new ConfigSettings();

		$post = [
			'user_id' => '12345::0987654321',
			'launch_presentation_return_url' => 'https://test.schoology.com/external_tool',	
		];
		$this->user = new UserAccount(NULL, $post, NULL);
		$this->oauth = new OauthWorkflow($this->storage, $this->config, $this->user);
	}

	public function test_have_access_token() {
		$oauth_workflow = new OauthWorkflow($this->storage, $this->config, $this->user);
		$this->assertFalse($oauth_workflow->have_access_token());
	}

	public function test_have_access_token_without_uid() {
		$post = [
			'launch_presentation_return_url' => 'https://test.schoology.com/external_tool',	
		];
		$user = new UserAccount(NULL, $post, NULL);

		$oauth_workflow = new OauthWorkflow($this->storage, $this->config, $user);
		$this->assertFalse($oauth_workflow->have_access_token());
	}

	public function test_auth_url_creation() {
		$test_request_token = [
			'oauth_token' => 'zxcvbnm',
		];

		$expected_auth_url = 'https://test.schoology.com/oauth/authorize?oauth_callback=https%3A%2F%2Ff4d0bccf.ngrok.io&oauth_token=zxcvbnm&user_id=12345';

		$this->oauth->prepare_auth_url($test_request_token);
		$this->assertSame($this->oauth->get_auth_url(), $expected_auth_url);
	}

	public function test_oauth_attributes() {
		$this->assertClassHasAttribute('access_token', OauthWorkflow::class)
		$this->assertClassHasAttribute('schoology_auth', OauthWorkflow::class);
	}

}