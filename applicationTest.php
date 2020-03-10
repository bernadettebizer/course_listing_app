<?php

require_once('CoursesAndGroups.php');

use PHPUnit\Framework\TestCase;

class RemoteConnectTest extends TestCase {

	public function test_api_call_for_courses() {
		$request = [];
		$request['data_type'] = 'courses';
		$request['endpoint'] = '/users/12345/sections';
		$requests = [$request];

		$api_result = new stdClass;
		$api_result->result = new stdClass;
		$section = [];
		$section['course_title'] = 'Title of course';
		$section['section_title'] = 'Title of section';
		$api_result->result->sections = $section;
		
        $stub = $this->createStub(SchoologyApi::class);

        $stub->expects($this->once())
        	->method('api')
        	->willReturn($api_result);

        $coursesAndGroups = new CoursesAndGroups;

        $this->assertSame('Title of course', $coursesAndGroups->execute_api_requests($requests, $stub)['courses']->sections['course_title']);
	}

	public function test_user_api_information_form_validate() {
		$uid = '12345';
		$key = '67890';
		$secret = '1234567890';
		$form_data = ['uid'=>$uid, 'key'=>$key, 'secret'=>$secret];
		$coursesAndGroups = new CoursesAndGroups;
		$this->assertTrue($coursesAndGroups->user_api_information_form_validate($form_data));
	}

	public function test_user_api_information_form_validate_incomplete_form_data() {
		$uid = '12345';
		$key = '';
		$secret = '1234567890';
		$form_data = ['uid'=>$uid, 'key'=>$key, 'secret'=>$secret];
		$coursesAndGroups = new CoursesAndGroups;
		$this->assertFalse($coursesAndGroups->user_api_information_form_validate($form_data));
	}

	public function test_correct_get_request_is_made() {
		$uid = '12345';
		$key = '67890';
		$secret = '1234567890';
		$courses = TRUE;
		$groups = FALSE;
		$form_data = ['uid'=>$uid, 'key'=>$key, 'secret'=>$secret, 'courses'=>$courses, 'groups'=>$groups];
		$coursesAndGroups = new CoursesAndGroups;

		$requests = $coursesAndGroups->user_api_information_form_submit($form_data);
		$this->assertSame($requests[0]['data_type'],'courses');
	}

}