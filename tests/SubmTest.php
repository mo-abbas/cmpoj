<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class SubmTest extends TestCase
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

	public function test_subm()
	{
		require_once("../subm.php");

    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    $_GET["id"] = "dummy";
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    //not logged in
    $_GET["id"] = 1;
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    //logged in, submission not found
    $_SESSION["id"] = 33;
    $_GET["id"] = 1;
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    //not my submission nor am i the judge
    $_SESSION["id"] = 33;
    $_GET["id"] = 388;
    $output = output();
    $this->assertEquals("index.php", $output["redirect"]);

    // I am the judge
    $_SESSION["id"] = 21;
    $_GET["id"] = 388;
    $output = output();
    $this->assertTrue($output["allowJudge"]);

    // I am the contestant
    $_SESSION["id"] = 23;
    $_GET["id"] = 388;
    $output = output();
    $this->assertFalse($output["allowJudge"]);

    // submission not pending
    $_SESSION["id"] = 21;
    $_GET["id"] = 389;
    $output = output();
    $this->assertFalse($output["allowJudge"]);
	}

  public function test_subm_post()
  {
    require_once("../subm.php");

    // no post
    $id = 389;
    $submission = find_submission_by_id($id);
    $output = post($submission);
    $this->assertEmpty($output);

    //not pending
    $output = post($submission);
    $this->assertEmpty($output);

    //invalid response
    $id = 388;
    $submission = find_submission_by_id($id);
    $_POST["status"] = "bla";
    $output = post($submission);
    $this->assertEquals("?id={$id}", $output["redirect"]);

    $_POST["status"] = "Accepted";
    $output = post($submission);
    $this->assertEquals("Verdict set successfully.", $_SESSION["message"]);
    $this->assertEquals("Accepted", $output["submission"]["status"]);
  }
}
?>
