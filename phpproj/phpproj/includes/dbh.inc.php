<?php

class Dbh {
  private $servername;
  private $username;
  private $password;
  private $dbname;

  protected function connect() {
    $this->servername = "athena.ecs.csus.edu";
    $this->username = "team_aies_user";
    $this->password = "aieswins1";
    $this->dbname = "team_aies";

    $conn = new mysqli($this->servername, $this->username,
      $this->password, $this->dbname);

    if (mysqli_connect_error()) {
      die('Connect Error ('.mysqli_connect_errno().') '.mysqli_connect_error());
    }

    echo 'Connected successfully.';

    return $conn;
  }
}

?>
