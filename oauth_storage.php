<?php
require_once('App_OauthStorage.class.php');

class OauthStorage {
	protected $db_host;
	protected $db_user;
	protected $db_pass;
	protected $db_name;
	protected $storage;

	public function __construct() {
		$this->db_host = '127.0.0.1';
		$this->db_user = 'root';
		$this->db_pass = 'Bcb315!!';
		$this->db_name = 'auth';
		$db = new PDO('mysql:dbname='.$this->db_name.';host='.$this->db_host, $this->db_user, $this->db_pass);
		$this->storage = new App_OauthStorage($db);	
	}

	public function get_database_connection() {
		return $this->storage;
	}

}

class OauthStorageFactory {
	public static function create()
    {
        return new OauthStorage();
    }
}