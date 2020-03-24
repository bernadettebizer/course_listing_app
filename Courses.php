<?php

class Courses {
	
	protected $course_data;

	public function __construct($course_data) {
		$this->course_data = $course_data;
	}

	public function validate_course_data() {
		if($this->course_data->section){
			return TRUE;
		}
	}

	public function parse_course_data() {
		$courses = [];

		foreach ($this->course_data->section as $section) {
			$section_data = [
				'course_title' => $section->course_title,
				'section_title' => $section->section_title,
			];
			$courses[] = $section_data;
		}

		return $courses;
	}
}

