<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/validation_functions.php");
require_once("../includes/functions.php");

class ValidationTest extends TestCase
{
	public function test_has_presence()
	{
		$value = null;	//false - true
		$result = has_presence($value);
		$this->assertFalse($result);
		
		$value = "";	//true - false
		$result = has_presence($value);
		$this->assertFalse($result);
		
		$value = "value";	//true - true
		$result = has_presence($value);
		$this->assertTrue($result);
	}
	
	public function test_validate_presences()
	{
		global $errors;
		$fields = ["one", "two"];
		
		validate_presences($fields);
		$this->assertCount(2, $errors);
		$this->assertEquals($errors["one"], "One can't be blank");
		$this->assertEquals($errors["two"], "Two can't be blank");
		$errors = array();
		
		$_POST["one"] = [];
		$result = validate_presences($fields);
		$this->assertCount(2, $errors);
		$this->assertEquals($errors["one"], "One can't be blank");
		$this->assertEquals($errors["two"], "Two can't be blank");
		$errors = array();
		
		$_POST["one"] = ["test"];
		$result = validate_presences($fields);
		$this->assertCount(1, $errors);
		$this->assertEquals($errors["two"], "Two can't be blank");
		$errors = array();
		
		$_POST["two"] = "test";
		$result = validate_presences($fields);
		$this->assertCount(0, $errors);
		$errors = array();
		
		$_POST = array();
	}
	
	public function test_validate_arrays()
	{
		global $errors;
		$fields = ["one"];
		
		validate_arrays($fields);
		$this->assertCount(1, $errors);
		$this->assertEquals($errors["one"], "One can't be blank");
		$errors = array();
		
		$_POST["one"] = [];
		validate_arrays($fields);
		$this->assertCount(1, $errors);
		$this->assertEquals($errors["one"], "One must have at least one entry");
		$errors = array();
		
		$_POST["one"] = [""];
		validate_arrays($fields);
		$this->assertCount(1, $errors);
		$this->assertEquals($errors["one"], "One must have at least one entry");
		$errors = array();
		
		$_POST["one"] = ["test"];
		validate_arrays($fields);
		$this->assertCount(0, $errors);
		$errors = array();
		
		$_POST = array();
	}
	
	public function test_has_max_length()
	{
		$max = 3;
		$value = "test";
		$result = has_max_length($value, $max);
		$this->assertFalse($result);
		
		$max = 5;
		$value = "test";
		$result = has_max_length($value, $max);
		$this->assertTrue($result);
	}
	
	public function test_validate_max_lengths()
	{
		global $errors;
		
		$fields = ["one" => 5, "two" => 5];
		$_POST["one"] = "test";
		$_POST["two"] = "test123";
		validate_max_lengths($fields);
		$this->assertCount(1, $errors);
		$this->assertEquals($errors["two"], "Two is too long");
		
		// $errors["two"] is still set;
		validate_max_lengths($fields);
		$this->assertEquals($errors["two"], "Two is too long");
		
		$errors = [];
		$_POST = [];
	}
	
	public function test_has_min_length()
	{
		$min = 3;
		$value = "test";
		$result = has_min_length($value, $min);
		$this->assertTrue($result);
		
		$min = 5;
		$value = "test";
		$result = has_min_length($value, $min);
		$this->assertFalse($result);
	}
	
	public function test_validate_min_lengths()
	{
		global $errors;
		
		$fields = ["one" => 5, "two" => 5];
		$_POST["one"] = "test123";
		$_POST["two"] = "test";
		validate_min_lengths($fields);
		$this->assertCount(1, $errors);
		$this->assertEquals($errors["two"], "Two is too short");
		
		// $errors["two"] is still set;
		validate_min_lengths($fields);
		$this->assertEquals($errors["two"], "Two is too short");
		
		$errors = [];
		$_POST = [];
	}
	
	public function test_does_match()
	{
		global $errors;
		
		$s1 = "test";
		$s2 = "test2";
		does_match($s1, $s2);
		$this->assertCount(1, $errors);
		$this->assertEquals($errors["password"], "Passwords don't match");
		$errors = [];
		
		$s1 = "test";
		$s2 = "test";
		does_match($s1, $s2);
		$this->assertCount(0, $errors);
	}
	
	public function test_is_a_number()
	{
		$s = "test";
		$result = is_a_number($s);
		$this->assertFalse($result);

		$s = "123";
		$result = is_a_number($s);
		$this->assertTrue($result);
	}
	
	public function test_validate_numbers()
	{
		global $errors;
		$fields = ["one", "two"];
		
		$_POST["one"] = "one";
		$_POST["two"] = "123";
		validate_numbers($fields);
		$this->assertCount(1, $errors);
		$this->assertEquals($errors["one"], "One should have a number");
		$errors = [];
	}
	
	public function test_validate_unique()
	{
		global $errors;
		
		$fields = ["one" => null, "two" => array("two" => "username")];
		validate_unique($fields);
		$this->assertCount(1, $errors);
		$this->assertEquals($errors["two"], "username is already taken.");
		$errors = [];
	}
	
	public function test_validate_email()
	{
		global $errors;
		
		$email = "a@a.com";
		validate_email($email);
		$this->assertCount(0, $errors);
		
		$email = "a@com";
		validate_email($email);
		$this->assertCount(1, $errors);
		$this->assertEquals($errors["email"], "Email is invalid.");
		$errors = [];
	}
	
	public function test_validate_date_time()
	{
		global $errors;
		
		$date = "10 September 2000";
		validate_date_time($date);
		$this->assertCount(0, $errors);
		
		$date = "test";
		validate_date_time($date);
		$this->assertCount(1, $errors);
		$this->assertEquals($errors["date"], "Date is invalid.");
		$errors = [];
	}
}
?>