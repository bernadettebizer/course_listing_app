<?php

class GroupCollection {
	
	protected $group_data;
	protected $groups;

	public function __construct($group_data) {
		$this->group_data = $group_data;
		$this->groups = $this->parse_group_data();
	}

	public function validate_group_data() {
		if($this->group_data->group){
			return TRUE;
		}
	}

	private function parse_group_data() {
		$groups = [];

		foreach ($this->group_data->group as $group) {
			$group_data = [
				'group_title' => $group->title,
				'group_nid' => $group->id,
			];
			$groups[] = $group_data;
		}
		return $groups;
	}

	public function get_groups() {
		return $this->groups;
	}
}

