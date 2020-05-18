<?php
require_once('Config.php');
require_once('FormData.php');
require_once('oauth_storage.php');

use PHPUnit\Framework\TestCase;

class RemoteConnectTest extends TestCase {
	protected $api_result;
	protected $storage;
	protected $config;
	protected $post;

	protected function setUp() : void
		{
			$uid = '12345';
			$key = '67890';
			$secret = '1234567890';
			$courses = TRUE;
			$groups = FALSE;
			$domain = 'magicdistrict.schoology.com';

			$this->post = [
				'uid'=>$uid, 
				'domain'=>$domain,
				'courses'=>$courses, 
				'groups'=>$groups,
			];

			$this->config = new ConfigSettings();
			$this->storage = OauthStorageFactory::create();
			
			$result = new stdClass;
			$result->courses = new stdClass;

			$section_array = [
				'course_title' => 'Title of course',
				'section_title' => 'Title of section',
				'id' => '12345',
				'course_id' => '67890',
			];

			$section = json_decode(json_encode($section_array));

			$result->courses->section[] = $section;

			$this->api_result = $result;
		}

	public function test_correct_get_request_is_made() {
		$uid = '12345';
		$key = '67890';
		$secret = '1234567890';
		$courses = TRUE;
		$groups = FALSE;
		$domain = 'magicdistrict.schoology.com';

		$post = [
			'uid'=>$uid, 
			'domain'=>$domain,
			'courses'=>$courses, 
			'groups'=>$groups,
		];
		
		$form_data = new FormData($post, $this->storage, $this->config);

		$requests = $form_data->get_request_data();
		$this->assertSame($requests[0]['data_type'],'courses');
	}

	public function test_correct_get_request_is_made_no_request() {
		$uid = '12345';
		$key = '67890';
		$secret = '1234567890';
		$courses = FALSE;
		$groups = FALSE;
		$domain = 'magicdistrict.schoology.com';

		$post = [
			'uid'=>$uid, 
			'domain'=>$domain,
		];
		
		$form_data = new FormData($post, $this->storage, $this->config);

		$requests = $form_data->get_request_data();

		$this->assertEmpty($requests);
	}
}