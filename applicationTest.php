<?php

use application.php;

class RemoteConnectTest extends PHPUnit_Framework_TestCase {

	public function setUp(){ }
  	public function tearDown(){ }

	public function test_format_lists_one_course() {
		$test_api_result = new StdClass;
		$test_api_result->result = new StdClass;
		$test_api_result->result->section = array();
		$section_obj = new StdClass;
		$section_obj->course_title = 'course1';
		$section_obj->section_title = 'section1';
		$test_api_result->result->section[] = $section_obj;

		$expected_list = '<ul><li>course1: section1</li></ul>';

		$list = format_lists($test_api_result, 'course');

		$this->assertTrue($list == $expected_list);
		exit;
	}

	public function test_format_lists_no_courses() {
		$test_api_result = new StdClass;
		$test_api_result->result = new StdClass;
		$test_api_result->result->section = array();

		$expected_list = "<ul><li>No course's were found for this user.</li></ul>";

		$list = format_lists($test_api_result, 'course');

		$this->assertTrue($list == $expected_list);
	}

	public function test_format_lists_many_courses() {
		$test_api_result = new StdClass;
		$test_api_result->result = new StdClass;
		$test_api_result->result->section = array();

		$section_obj1 = new StdClass;
		$section_obj->course_title = 'course1';
		$section_obj->section_title = 'section1';
		$test_api_result->result->section[] = $section_obj1;

		$section_obj2 = new StdClass;
		$section_obj->course_title = 'course2';
		$section_obj->section_title = 'section2';
		$test_api_result->result->section[] = $section_obj2;

		$section_obj3 = new StdClass;
		$section_obj->course_title = 'course3';
		$section_obj->section_title = 'section3';
		$test_api_result->result->section[] = $section_obj3;

		$expected_list = '<ul><li>course1: section1</li><li>course2: section2</li><li>course3: section3</li></ul>';

		$list = format_lists($test_api_result, 'course');

		$this->assertTrue($list == $expected_list);
	}


}