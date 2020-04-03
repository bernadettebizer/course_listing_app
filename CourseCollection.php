<?php

class CourseCollection {
	
	protected $course_data;
	protected $sections;

	public function __construct($course_data) {
		$this->course_data = $course_data;
		$this->sections = $this->parse_course_data();
	}

	public function validate_course_data() {
		if($this->course_data->section){
			return TRUE;
			//add more validation here
		}
	}

	private function parse_course_data() {
		$courses = [];

		foreach ($this->course_data->section as $section) {
			$section_data = [
				'course_title' => $section->course_title,
				'section_title' => $section->section_title,
				'section_path' => 'https://magicdistrict.schoology.com/course/' . $section->id,
				'section_nid' => $section->id,
				'course_nid' => $section->course_id,
			];
			$courses[] = $section_data;
		}

		return $courses;
	}

	public function get_sections() {
		return $this->sections;
	}

	// public function get_sections_in_course($course_title) {
	// 	$course_list = $this->get_parsed_course_list();
	// 	foreach($course_list->section as $section) {

	// 	}
	// }

	// public function get_course_by_section($section_title) {

	// }

	// public function get_number_of_sections() {

	// }

	// public function iterate_through_sections() {

	// }

}

