<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class JoinContestTest extends TestCase
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

	public function test_join_contest_get()
	{
		require_once("../join_contest.php");

    // no input
    $output = output();
    $this->assertArrayHasKey("redirect", $output);

    // contest doesn't exits
    $_GET["contest"] = 1;
    $output = output();
    $this->assertArrayHasKey("redirect", $output);

    // not logged in
    $_GET["contest"] = 13;
    $output = output();
    $this->assertArrayHasKey("redirect", $output);

    // Individual contest and no team
    $_GET["contest"] = 13;
    $_SESSION["id"] = 33;
    $_SESSION["team_id"] = 0;
    $output = output();
    $this->assertTrue($output["showSubmit"]);
    $this->assertFalse($output["inTeam"]);

    // Individual with team
    $_GET["contest"] = 13;
    $_SESSION["id"] = 22;
    $_SESSION["team_id"] = 37;
    $output = output();
    $this->assertTrue($output["showSubmit"]);
    $this->assertTrue($output["inTeam"]);

    // team contest with no team
    $_GET["contest"] = 20;
    $_SESSION["id"] = 33;
    $_SESSION["team_id"] = 0;
    $output = output();
    $this->assertFalse($output["showSubmit"]);
    $this->assertFalse($output["inTeam"]);
	}

  public function test_join_contest_post()
  {
    require_once("../join_contest.php");
    $contest_13 = find_contest_by_id(13);
    $contest_20 = find_contest_by_id(20);

    // no submission
    $_GET["contest"] = 13;
    $_SESSION["id"] = 36;
    $_SESSION["team_id"] = 37;
    post($contest_13);

    $relation = find_contestant_in_contest($_SESSION["id"], $_GET["contest"]);
    $this->assertNull($relation);


    // join as an individual
    $_GET["contest"] = 13;
    $_SESSION["id"] = 36;
    $_SESSION["team_id"] = 37;
    $_POST["submit"] = 1;
    post($contest_13);

    $relation = find_contestant_in_contest($_SESSION["id"], $_GET["contest"]);
    $this->assertEquals($relation["contest_id"], 13);
    $this->assertEquals($relation["contestant_id"], 36);
    $this->assertEquals($_SESSION["message"], "You joined the contest successfully.");

    // already joined
    post($contest_13);

    $relation = find_contestant_in_contest($_SESSION["id"], $_GET["contest"]);
    $this->assertEquals($relation["contest_id"], 13);
    $this->assertEquals($relation["contestant_id"], 36);
    $this->assertEquals($_SESSION["message"], "You already joined this contest.");


    // join as a team
    $_GET["contest"] = 20;
    $_SESSION["id"] = 36;
    $_SESSION["team_id"] = 37;
    $_POST["submit"] = 1;
    post($contest_20);

    $relation = find_contestant_in_contest($_SESSION["team_id"], $_GET["contest"]);
    $this->assertEquals($relation["contest_id"], 20);
    $this->assertEquals($relation["contestant_id"], 37);
    $this->assertEquals($_SESSION["message"], "You joined the contest successfully.");

    // already joined as a team
    post($contest_20);

    $relation = find_contestant_in_contest($_SESSION["team_id"], $_GET["contest"]);
    $this->assertEquals($relation["contest_id"], 20);
    $this->assertEquals($relation["contestant_id"], 37);
    $this->assertEquals($_SESSION["message"], "You already joined this contest.");


    // doesn't have a team
    unset($_SESSION["message"]);
    $_GET["contest"] = 20;
    $_SESSION["id"] = 33;
    $_SESSION["team_id"] = 0;
    $_POST["submit"] = 1;
    post($contest_20);

    global $error;
    $relation = find_contestant_in_contest($_SESSION["id"], $_GET["contest"]);
    $this->assertNull($relation);
    $relation = find_contestant_in_contest($_SESSION["team_id"], $_GET["contest"]);
    $this->assertNull($relation);
    $this->assertEquals($error[0], "You must be in a team to join this contest.");
  }
}
?>
