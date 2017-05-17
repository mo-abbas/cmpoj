<?php
  // 1. Create a database connection
  global $connection;
  
  if (!isset($connection)) {
	  if(!defined("DB_SERVER"))
	  {
		define("DB_SERVER", 'localhost');
		define("DB_USER", 'root');
		define("DB_PASS", '');
		define("DB_NAME", 'cmpoj');
	  }
	  $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

	  // Test if connection succeeded
	  if(mysqli_connect_errno()) {
		die("Database connection failed: " .
			 mysqli_connect_error() .
			 " (" . mysqli_connect_errno() . ")"
		);
	  }
  }
?>
