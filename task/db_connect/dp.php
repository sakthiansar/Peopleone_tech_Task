<?php
  //echo'hello peopleone'
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "peopleone_test_dp";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>