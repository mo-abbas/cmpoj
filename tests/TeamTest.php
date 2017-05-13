<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/validation_functions.php");
require_once("../includes/functions.php");
require_once("../includes/session.php");

class TeamTest extends TestCase
{
	private static $connection = null;
	public static function setUpBeforeClass()
	{
		define("DB_SERVER", 'localhost');
		define("DB_USER", 'root');
		define("DB_PASS", '');
		define("DB_NAME", 'cmpoj');
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
		mysqli_query(self::$connection, "BEGIN");
	}

	public function tearDown()
	{
		mysqli_query(self::$connection, "ROLLBACK");
	}

	public function test_find_team_by_id()
	{
		$GLOBALS['connection'] = self::$connection;

		$id = 37;
		$team = find_team_by_id($id);
		$this->assertEquals($team["id"], 37);
		$this->assertEquals($team["coach_id"], 33);

		$id = 1;
		$team = find_team_by_id($id);
		$this->assertNull($team);
	}

	public function test_get_team_handle_by_account_id()
	{
		$GLOBALS['connection'] = self::$connection;

		$id = 22;
		$team = get_team_handle_by_account_id($id);
		$this->assertEquals($team["team_id"], 37);
		$this->assertEquals($team["handle"], "Testers");

		$id = 33;
		$team = get_team_handle_by_account_id($id);
		$this->assertNull($team);
	}

	public function test_get_teams_member()
	{
		$GLOBALS['connection'] = self::$connection;

		$id = 37;
		$team = get_teams_member($id);
		$this->assertCount(4, $team);
		$members = [22, 32, 35, 36];
		foreach($team as $member)
		{
			$this->assertContains($member["id"], $members);
			unset($members[$member["id"]]);
		}

		$id = 35;
		$team = get_teams_member($id);
		$this->assertNull($team);
	}

	public function test_get_team_handle()
	{
		$GLOBALS['connection'] = self::$connection;

		$id = 37;
		$handle = get_team_handle($id);
		$this->assertEquals($handle, "Testers");

		$id = 35;
		$handle = get_team_handle($id);
		$this->assertNull($handle);
	}

	public function test_Team()
	{
		$GLOBALS['connection'] = self::$connection;
		require_once("../Team.php");

		$output = output();
		$this->assertEquals("index.php", $output["redirect"]);

		// team doesn't exist
		$_GET["id"] = 1;
		$output = output();
		$this->assertEquals("index.php", $output["redirect"]);

		$_GET["id"] = 37;
		$output = output();
		$this->assertEquals("Testers", $output["team"]);
	}
}
?>
