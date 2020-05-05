<?php

require_once('users.php');

use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase {

	protected $get;
	protected $cookie;
	protected $user_id;
	protected $return_url;
	protected $uid;

	public function setUp() : void 
	{
		$this->get = NULL;
		$this->cookie = NULL;
		$this->user_id = '12345::0987654321';
		$this->return_url = 'https://test.schoology.com/external_tool';
		$this->uid = '12345';
		$this->domain = 'https://test.schoology.com';
	}

	public function test_get_uid_with_user_id() {
		$post = [
			'launch_presentation_return_url' => $this->return_url,
			'user_id' => $this->user_id,
		];
		$user = new UserAccount($this->get, $post, $this->cookie);
		$this->assertSame($this->uid, $user->get_uid());
	}

	public function test_get_uid_without_user_id() {
		$post = [
			'launch_presentation_return_url' => $this->return_url,
		];
		$user = new UserAccount($this->get, $post, $this->cookie);
		$this->assertSame('0', $user->get_uid());
	}

	public function test_get_domain_with_domain_in_post() {
		$post = [
			'user_id' => $this->user_id,
			'launch_presentation_return_url' => $this->return_url,	
		];

		$user = new UserAccount($this->get, $post, $this->cookie);
		$this->assertSame($this->domain, $user->get_domain());
	}

	public function test_get_domain_with_no_domain_in_post() {
		$post = [
			'user_id' => $this->user_id,
		];

		$user = new UserAccount($this->get, $post, $this->cookie);
		$this->assertSame('https://app.schoology.com', $user->get_domain());
	}
}