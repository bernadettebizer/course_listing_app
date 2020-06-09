<?php
require_once('CourseCollection.php');
require_once('GroupCollection.php');

use PHPUnit\Framework\TestCase;

class RemoteConnectTest extends TestCase {
	protected $groupCollection;
	protected $api_result;

	protected function setUp() : void
		{
			$group_array = [
				'title' => 'Title of group',
				'id' => '45678',
			];

			$group =(object)$group_array;
			$result = new stdClass;
			$result->groups = new stdClass;
			$result->groups->group[] = $group;

			$this->api_result = $result;
		    
		   	$this->groupCollection = new GroupCollection($this->api_result->groups);
		}

	public function test_get_group() {
		$group = [
			'group_title' => 'Title of group',
    		'group_nid' => '45678',
		];

		$this->assertSame($this->groupCollection->get_groups()[0], $group);
	}

	public function test_validate_group_data() {
		$this->assertTrue($this->groupCollection->group_data_is_valid());
	}

	public function test_validate_group_data_invalid() {
		$group_array = [
			'id' => '45678',
		];

		$group =(object)$group_array;
		$result = new stdClass;
		$result->groups = new stdClass;
		$result->groups->group[] = $group;

		$groupCollection = new GroupCollection($result->groups);
		
		$this->assertNull($groupCollection->group_data_is_valid());
	}

}