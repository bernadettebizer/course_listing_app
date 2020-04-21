<?php

class ConfigSettings {

 protected $consumer_key;
 protected $consumer_secret;

 public function __construct() {
		$this->consumer_key = 'a6694d0888207de681da8b99039f28d205e6e948b';
		$this->consumer_secret = 'ea51feadb0f2885bd0a5cebd162d38a1';
	}

 public function get_app_consumer_key() {
 	return $this->consumer_key;
 }

 public function get_app_consumer_secret() {
 	return $this->consumer_secret;
 }

}