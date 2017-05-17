<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class NewProblemActionTest extends TestCase
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

  public function test_new_problem()
  {
    require("../actions/new_problem.php");
    global $errors;

    // no post
    $output = output();
    $this->assertEquals("../index.php", $output["redirect"]);
    $errors = [];
    $_SESSION = [];

		// not logged in
		$_POST["submit"] = 1;
		$output = output();
		$this->assertEquals("../index.php", $output["redirect"]);
    $errors = [];
    $_SESSION = [];

    // no contest
		$_POST["submit"] = 1;
    $_SESSION["id"] = 21;
		$output = output();
		$this->assertEquals("../index.php", $output["redirect"]);
    $errors = [];
    $_SESSION = [];

    // invalid contest
    $_POST["submit"] = 1;
    $_SESSION["id"] = 21;
    $_GET["contest"] = 1;
		$output = output();
		$this->assertEquals("../index.php", $output["redirect"]);
    $errors = [];
    $_SESSION = [];

    // no problem text
    $_POST["submit"] = 1;
    $_SESSION["id"] = 21;
    $_GET["contest"] = 13;
    $_POST["problem_name"] = "Test problem";
    //$_POST["problem_text"] = "Test problem";
    $_POST["sample_input"] = ["input1", "input2"];
    $_POST["sample_output"] = ["output1", "output2"];
    $_POST["categories"] = ["cat1", "", "cat1"];
    $_POST["level"] = 1;
		$output = output();
    $this->assertEquals("Problem text can't be blank", $_SESSION["errors"]["problem_text"]);
		$this->assertEquals("../insert_prob.php?contest=" . $_GET["contest"], $output["redirect"]);
    $errors = [];
    $_SESSION = [];

    // valid input
    $_POST["submit"] = 1;
    $_SESSION["id"] = 21;
    $_GET["contest"] = 13;
    $_POST["problem_name"] = "Test problem";
    $_POST["problem_text"] = "Test problem";
    $_POST["sample_input"] = ["input1", "input2"];
    $_POST["sample_output"] = ["output1", "output2"];
    $_POST["categories"] = ["cat1", "", "cat1"];
    $_POST["level"] = 1;
		$output = output();
    $this->assertFalse(isset($_SESSION["errors"]));
		$this->assertEquals("../ContestProblems.php?contest=" . $_GET["contest"], $output["redirect"]);
    $errors = [];
    $_SESSION = [];

    $problems = get_all_problems_in_contest(13);
    $found = false;
    foreach ($problems as $problem)
    {
      if ($problem["title"] == "Test problem")
        $found = true;
    }

    $this->assertTrue($found);
  }
}
?>
