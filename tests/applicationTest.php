<?php

require_once('CourseCollection.php');
require_once('GroupCollection.php');
require_once('GetDataForm.php');

use PHPUnit\Framework\TestCase;

class RemoteConnectTest extends TestCase {
	protected $courseCollection;
	protected $groupCollection;
	protected $api_result;

	protected function setUp() : void
		{
			$result = new stdClass;
			$result->courses = new stdClass;
			$result->groups = new stdClass;

			$section_array = [
				'course_title' => 'Title of course',
				'section_title' => 'Title of section',
				'id' => '12345',
				'course_id' => '67890',
			];

			$section = json_decode(json_encode($section_array));

			$group_array = [
				'title' => 'Title of group',
				'id' => '45678',
			];

			$group = json_decode(json_encode($group_array));

			$result->courses->section[] = $section;
			$result->groups->group[] = $group;

			$this->api_result = $result;
		    
		    $this->courseCollection = new CourseCollection($this->api_result->courses);
		   	$this->groupCollection = new GroupCollection($this->api_result->groups);
		}

	public function test_get_section() {
		$section = [
			'course_title' => 'Title of course',
	        'section_title' => 'Title of section',
	        'section_path' => 'https://magicdistrict.schoology.com/course/12345',
	        'section_nid' => '12345',
	        'course_nid' => '67890',
		];

		$this->assertSame($this->courseCollection->get_sections()[0], $section);
	}

	public function test_validate_course_data() {
		$this->assertTrue($this->courseCollection->validate_course_data());
	}

	public function test_get_group() {
		$group = [
			'group_title' => 'Title of group',
    		'group_path' => 'https://magicdistrict.schoology.com/group/45678',
    		'group_nid' => '45678',
		];

		$this->assertSame($this->groupCollection->get_groups()[0], $group);
	}

	public function test_validate_group_data() {
		$this->assertTrue($this->groupCollection->validate_group_data());
	}

	public function test_api_call_for_courses() {
		$requests = [
    		[
        		'data_type' => 'courses',
        		'endpoint' => '/users/12345/sections',
    		],
		];

		$api_result = new stdClass;
		$api_result->result = new stdClass;

		$section = [
			'course_title' => 'Title of course',
			'section_title' => 'Title of section',
		];

		$api_result->result->section = $section;
		
        $stub = $this->createStub(SchoologyApi::class);

        $stub->method('api')
        	->willReturn($api_result);

        $this->assertSame('Title of course', execute_api_requests($requests, $stub)['courses']->section['course_title']);
	}

	public function test_correct_get_request_is_made() {
		$uid = '12345';
		$key = '67890';
		$secret = '1234567890';
		$courses = TRUE;
		$groups = FALSE;
		$form_data = [
			'uid'=>$uid, 
			'key'=>$key, 
			'secret'=>$secret, 
			'courses'=>$courses, 
			'groups'=>$groups
		];

		$requests = user_api_information_form_submit($form_data);
		$this->assertSame($requests[0]['data_type'],'courses');
	}

}