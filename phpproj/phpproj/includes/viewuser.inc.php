<?php

class ViewUser extends User {

  public function showAllUsers() {
      $datas = $this->getAllUsers();
      foreach ($datas as $data) {
          echo $data['user_id']."<br>";
          echo $data['firstName']."<br>";
          echo $data['lastName']."<br>";
          echo $data['email']."<br>";
          echo $data['passwordHash']."<br>";
      }
  }
}
