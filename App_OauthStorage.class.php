<?php

class App_OauthStorage implements SchoologyApi_OauthStorage
{
  private $db;
 
  // Constructor
  public function __construct($db)
  {
    $this->db = $db;
 
    // Test query
    $query = $this->db->prepare("SELECT 1=1");
    $query_status = $query->execute();
    if(!$query_status || $query->fetchColumn() != 1){
      throw new Exception('The database did not respond as expected. Please check your database and try again.');
    }
  }
 
  // Retrieve access tokens for a given user ID
  public function getAccessTokens($uid){
    // Check to see if we have oauth tokens for this user
    $query = $this->db->prepare("SELECT uid, token_key, token_secret FROM oauth_tokens WHERE uid = :uid AND token_is_access = 1 LIMIT 1");
    $query->execute(array(':uid' => $uid));
    return $query->fetch(PDO::FETCH_ASSOC);
  }
 
  // Store request tokens for a given user ID
  public function saveRequestTokens($uid, $token_key, $token_secret){
    $query = $this->db->prepare("REPLACE INTO oauth_tokens (uid, token_key, token_secret, token_is_access) VALUES (:uid, :key, :secret, 0)");
    $query->execute(array(
              ':uid' => $uid,
              ':key' => $token_key,
              ':secret' => $token_secret,
    ));
  }
 
  // Get request tokens for a given ID
  public function getRequestTokens($uid){
    $query = $this->db->prepare("SELECT uid, token_key, token_secret FROM oauth_tokens WHERE uid = :uid AND token_is_access = 0");
    $query->execute(array(':uid' => $uid));
    return $query->fetch(PDO::FETCH_ASSOC);
  }
 
  // Replace request tokens with authorized access tokens for a given ID
  public function requestToAccessTokens($uid, $token_key, $token_secret){
    $query = $this->db->prepare("UPDATE oauth_tokens SET token_key = :key, token_secret = :secret, token_is_access = 1 WHERE uid = :uid");
    $query->execute(array(
              ':key' => $token_key,
              ':secret' => $token_secret,
              ':uid' => $uid,
    ));
  }
 
  // Revoke tokens for a specific user
  public function revokeAccessTokens($uid){
    $query = $this->db->prepare("DELETE FROM schoology_oauth_tokens WHERE uid = :uid");
    $query->execute(array(
      ':uid' => $uid,
    ));
  }

  //get uid from oath_token
  public function getUidAndSecret($oauth_token){
    $query = $this->db->prepare("SELECT uid, token_key, token_secret FROM oauth_tokens WHERE token_key = :oauth_token AND token_is_access = 0");
    $query->execute(array(':oauth_token' => $oauth_token));
    return $query->fetch(PDO::FETCH_ASSOC);
  }

}