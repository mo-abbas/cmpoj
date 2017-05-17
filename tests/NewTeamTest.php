<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class NewTeamActionTest extends TestCase
{
	private static $connection = null;
	public static function setUpBeforeClass()
	{
    if(!defined("DB_SERVER"))
    {
      define("DB_SERVER", 'localhost');
      define("DB_USER", 'root');
      define("DB_PASS", '');
      define("DB_NAME", 'cmpoj');
    }
		self::$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

		// Test if connection succeeded
		if(mysqli_connect_errno()) {
			die("Database connection failed: " .
				mysqli_connect_error() .
				" (" . mysqli_connect_errno() . ")"
	    );
	  }
	}

	public static function tearDownAfterClass()
	{
		if(isset(self::$connection))
		{
			mysqli_close(self::$connection);
		}
	}

	public function setUp()
	{
		$this->setOutputCallback(function() {});

		$GLOBALS['connection'] = self::$connection;
		mysqli_query(self::$connection, "BEGIN");
	}

	public function tearDown()
	{
		mysqli_query(self::$connection, "ROLLBACK");
	}

  public function test_new_team()
  {
    require("../actions/new_team.php");
    global $errors;

    // no post
    $output = output();
    $this->assertEquals("../Create_Team.php", $output["redirect"]);
    $errors = [];
    $_SESSION = [];

		// not logged in
		$_POST["submit"] = 1;
		$output = output();
		$this->assertEquals("../Create_Team.php", $output["redirect"]);
    $errors = [];
    $_SESSION = [];

    // Team taken
		$_POST["submit"] = 1;
    $_SESSION["id"] = 33;
    $_POST["team_name"] = "Testers";
    $_POST["members"] = ["test1", "test2", "test3", "test4"];
		$output = output();
		$this->assertEquals("../Create_Team.php", $output["redirect"]);
    $this->assertEquals("The handle '" . $_POST["team_name"] . "' is already taken.", $_SESSION["errors"]["team"]);
    $errors = [];
    $_SESSION = [];

    // more than 4
    $_POST["submit"] = 1;
    $_SESSION["id"] = 33;
    $_POST["team_name"] = "Testers 2";
    $_POST["members"] = ["test1", "test2", "test3", "test4", "test5"];
		$output = output();
		$this->assertEquals("../Create_Team.php", $output["redirect"]);
    $this->assertFalse(isset($_SESSION["errors"]));
    $errors = [];
    $_SESSION = [];

    // user doesn't exist, add myself, user has a team, empty member
    $_POST["submit"] = 1;
    $_SESSION["id"] = 33;
    $_POST["team_name"] = "Testers 2";
    $_POST["members"] = ["alii", "blabla", "mohamed", ""];
		$output = output();
		$this->assertEquals("../Create_Team.php", $output["redirect"]);
    $this->assertContains("blabla doesn't exist.", $_SESSION["errors"]);
    $this->assertContains("mohamed already has a team.", $_SESSION["errors"]);
    $this->assertContains("You must enter four members.", $_SESSION["errors"]);
    $this->assertContains("You can't add yourself to a team.", $_SESSION["errors"]);
    $errors = [];
    $_SESSION = [];

    // duplicate member, members less than 4
    $_POST["submit"] = 1;
    $_SESSION["id"] = 33;
    $_POST["team_name"] = "Testers 2";
    $_POST["members"] = ["test1", "test1"];
		$output = output();
		$this->assertEquals("../Create_Team.php", $output["redirect"]);
    $this->assertContains("You must enter four members.", $_SESSION["errors"]);
    $this->assertContains("'test1' you can't add a member twice.", $_SESSION["errors"]);
    $errors = [];
    $_SESSION = [];

    // valid input
    $_POST["submit"] = 1;
    $_SESSION["id"] = 33;
    $_POST["team_name"] = "Testers 2";
    $_POST["members"] = ["test1", "test2", "test3", "test4"];
		$output = output();
		$this->assertEquals("../index.php", $output["redirect"]);
    $this->assertFalse(isset($_SESSION["errors"]));
    $errors = [];
    $_SESSION = [];
  }
}
?>
