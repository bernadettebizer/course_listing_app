<?php

class Groups {
	
	protected $group_data;

	public function __construct($group_data) {
		$this->group_data = $group_data;
	}

	public function validate_group_data() {
		if($this->group_data->group){
			return TRUE;
		}
	}

	public function parse_group_data() {
		$groups = [];

		foreach ($this->group_data->group as $group) {
			$group_data = [
				'group_title' => $group->title,
			];
			$groups[] = $group_data;
		}

		return $groups;
	}
}

