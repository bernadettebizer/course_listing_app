<?php

require_once('CourseCollection.php');
require_once('FormData.php');

use PHPUnit\Framework\TestCase;

class RemoteConnectTest extends TestCase {
	protected $courseCollection; 
	protected $api_result;

	protected function setUp() : void
		{
			$section_array = [
				'course_title' => 'Title of course',
				'section_title' => 'Title of section',
				'id' => '12345',
				'course_id' => '67890',
			];

			$section = (object)$section_array;
			$result = new stdClass;
			$result->courses = new stdClass;
			$result->courses->section[] = $section;

			$this->api_result = $result;
		    
		    $this->courseCollection = new CourseCollection($this->api_result->courses);
		}

	public function test_get_sections() {
		$section = [
			'course_title' => 'Title of course',
	        'section_title' => 'Title of section',
	        'section_nid' => '12345',
	        'course_nid' => '67890',
		];

		$this->assertSame($this->courseCollection->get_sections()[0], $section);
	}

	public function test_validate_course_data() {
		$this->assertTrue($this->courseCollection->course_data_is_valid());
	}

	public function test_validate_course_data_invalid() {
		$section_array = [
			'section_title' => 'Title of section',
			'id' => '12345',
			'course_id' => '67890',
		];

		$section = (object)$section_array;
		$courses = new stdClass;
		$courses->section[] = $section;

		$courseCollection = new CourseCollection($courses);
		
		$this->assertNull($courseCollection->course_data_is_valid());
	}
}