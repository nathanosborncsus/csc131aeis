<?php
  DEFINE('DB_USERNAME', 'team_aies_user');
  DEFINE('DB_PASSWORD', 'aieswins1');
  DEFINE('DB_HOST', 'athena.ecs.csus.edu');
  DEFINE('DB_DATABASE', 'team_aies');

  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

  echo 'Connected successfully.';

  //if (mysqli_connect_error()) {
  //  die('Connect Error ('.mysqli_connect_errno().') '.mysqli_connect_error());
  //}

/*
class Dbh {
  private $servername;
  private $username;
  private $password;
  private $dbname;

  protected function connect() {
    $this->servername = "athena.ecs.csus.edu";
    $this->username = "team_aies_user";
    $this->password = "team_aies_db";
    $this->dbname = "team_aies";

    $conn = new mysqli($this->servername, $this->username,
      $this->password, $this->dbname);
    return $conn;
  }
}



interface database_access
  {
    // Transaction Processing
    public function trans_open();
    public function trans_commit();
    public function trans_rollback();

  }
*/
