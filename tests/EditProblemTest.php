<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class EditProblemTest extends TestCase
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

	public function test_edit_problem_get()
	{
		require_once("../edit_problem.php");

    // no input
    $output = output();
    $this->assertArrayHasKey("redirect", $output);

    // problem doesn't exits
    $_GET["problem"] = 1;
    $output = output();
    $this->assertArrayHasKey("redirect", $output);

    // not logged in
    $_GET["problem"] = 10;
    $output = output();
    $this->assertArrayHasKey("redirect", $output);

    // not the judge
    $_GET["problem"] = 10;
    $_SESSION["id"] = 33;
    $output = output();
    $this->assertArrayHasKey("redirect", $output);

    $_GET["problem"] = 10;
    $_SESSION["id"] = 21;
    $output = output();
    $this->assertEquals($output["problem"]["title"], "Problem 1");
	}

  public function test_edit_problem_post()
	{
		require_once("../edit_problem.php");
    $problem = find_problem_by_id(10);

    //no input
    $_GET["problem"] = 10;
    $_SESSION["id"] = 21;
    post();

    $problem = find_problem_by_id(10);
    $this->assertThat(
      $problem["text"],
      $this->logicalNot(
        $this->equalTo("")
      )
    );

    // no text
    $_GET["problem"] = 10;
    $_SESSION["id"] = 21;
    $_POST["submit"] = 1;
    post();

    $problem = find_problem_by_id(10);
    $this->assertThat(
      $problem["text"],
      $this->logicalNot(
        $this->equalTo("")
      )
    );

    // no submit
    $_GET["problem"] = 10;
    $_SESSION["id"] = 21;
    unset($_POST["submit"]);
    $_POST["problem_text"] = "New text";
    post();

    $problem = find_problem_by_id(10);
    $this->assertThat(
      $problem["text"],
      $this->logicalNot(
        $this->equalTo("New text")
      )
    );

    // valid
    $_GET["problem"] = 10;
    $_SESSION["id"] = 21;
    $_POST["submit"] = 1;
    post();

    $problem = find_problem_by_id(10);
    $this->assertEquals($problem["text"], "New text");
	}
}
?>
