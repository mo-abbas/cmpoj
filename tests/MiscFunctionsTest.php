<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/validation_functions.php");
require_once("../includes/functions.php");
require_once("../includes/session.php");

class ValidationTest extends TestCase
{
	public function test_fieldname_as_text()
	{
		$value = "test";
		$result = fieldname_as_text($value);
		$this->assertEquals($result, "Test");

		$value = "test_case";
		$result = fieldname_as_text($value);
		$this->assertEquals($result, "Test case");
	}

	public function test_form_errors()
	{
		$errors = null;
		$result = form_errors($errors);
		$this->assertEquals($result, "");

		$errors = ["The password is wrong"];
		$result = form_errors($errors);
		$expected = "<div class=\"error\">";
		$expected .= "Please fix the following errors:";
		$expected .= "<ul><li>The password is wrong</li></ul></div>";
		$this->assertEquals($result, $expected);
	}

	public function test_password_check()
	{
		$password = "123456";
		$hash = "\$2y\$10\$NmM3ZDQ5MzJlMTY2OGZhMeFGvPFfPz0eRs5QsdkwSTYKHe5PPpeui";
		$result = password_check($password, $hash);
		$this->assertTrue($result);

		$hash = "\$2y\$10\$NmM3ZDQ5MzJlMTY2OGZhMeFGvPFfPz0eRs5QsdkwSTYKHe1111111";
		$result = password_check($password, $hash);
		$this->assertFalse($result);
	}

	/**
	*	@depends test_password_check
	*/
	public function test_password_encrypt()
	{
		$password = "123456";
		$hash = password_encrypt($password);
		$result = password_check($password, $hash);
		$this->assertTrue($result);
	}

	public function test_generate_salt()
	{
		$length = 10;
		$salt = generate_salt($length);
		$this->assertEquals($length, strlen($salt));
	}

	public function test_logged_in()
	{
		$result = logged_in();
		$this->assertFalse($result);

		$_SESSION["id"] = "dummy";
		$result = logged_in();
		$this->assertTrue($result);
		
		$_SESSION = [];
	}
}
?>
