<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class DeleteTeamTest extends TestCase
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

  public function test_delete_team()
  {
    require("../actions/delete_team.php");

    // not logged in
    output();
    $this->assertEquals("../index.php", $output["redirect"]);

		//logged in, no team
		$_SESSION["id"] = 33;
		output();
		$this->assertEquals("../index.php", $output["redirect"]);

    //invalid team
    $_SESSION["id"] = 33;
		$_GET["team"] = 1;
    $output = output();
    $this->assertEquals("../index.php", $output["redirect"]);

		// not the coach
		$_SESSION["id"] = 21;
		$_GET["team"] = 37;
		$output = output();
		$team = find_team_by_id($_GET["team"]);
		$this->assertEquals(33, $team["coach_id"]);
		$this->assertEquals("../index.php", $output["redirect"]);

    //succesfull delete
		$_SESSION["id"] = 33;
		$_GET["team"] = 37;
		$output = output();
		$team = find_team_by_id($_GET["team"]);
		$this->assertNull($team);
		$this->assertEquals("../CoachedTeams.php", $output["redirect"]);
  }
}
?>
