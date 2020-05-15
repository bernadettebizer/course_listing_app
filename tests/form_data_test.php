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

	public function test_get_request_data() {	
		$section = [
			'course_title' => 'Title of course',
			'section_title' => 'Title of section',
		];

		$api_result = new stdClass;
		$api_result->result = new stdClass;
		$api_result->result->section = $section;
        $schoology = $this->createStub(SchoologyApi::class);

        $form_data = $this->getMockBuilder('FormData')
             ->disableOriginalConstructor()
             ->getMock();

		$form_data->schoology = $schoology;
		$form_data->uid = $this->post['uid'];
		$form_data->domain = $this->post['domain'];
		$form_data->config = $this->config;
		$form_data->courses = $this->post['courses'];
		var_dump($form_data);
		//is there a way to set private or protected properties in a class if there are not separate methods for setting those properties

		$result = $form_data->get_requested_data_from_api();
		$schoology->method('api')
        	->willReturn($api_result);

        //not working because this is not setting the class properties, so result is null
		$this->assertSame($result->result->section['course_title'],'Title of course');
	}
}