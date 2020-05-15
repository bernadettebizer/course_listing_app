<?php

class CourseCollection {
	
	protected $course_data;
	protected $sections;

	public function __construct($course_data) {
		$this->course_data = $course_data;
		$this->sections = $this->parse_course_data();
	}

	public function course_data_is_valid() {
		if($this->course_data->section && isset($this->sections[0]['course_title'])){
			return TRUE;
		}
	}

	private function parse_course_data() {
		$courses = [];

		foreach ($this->course_data->section as $section) {
			if(isset($section->course_title) && isset($section->section_title) && isset($section->id) && isset($section->course_id)) {
				$section_data = [
					'course_title' => $section->course_title,
					'section_title' => $section->section_title,
					'section_nid' => $section->id,
					'course_nid' => $section->course_id,
				];
				$courses[] = $section_data;
			}
		}

		return $courses;
	}

	public function get_sections() {
		return $this->sections;
	}
}

