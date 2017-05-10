<?php

use PHPUnit\Framework\TestCase;
require_once("../includes/session.php");

class SessionTest extends TestCase
{
	/**
     * @covers message
     */
	public function testMessage()
	{
		$result = message();
		$this->assertNull($result);
		
		$message = "This is a message";
		$_SESSION["message"] = $message;
		$result = message();
		$this->assertEquals($result, "<div class=\"message\">" . $message . "</div>");
		$this->assertNull($_SESSION["message"]);
	}
	
	/**
     * @covers errors
     */
	public function testErrors()
	{
		$result = errors();
		$this->assertNull($result);
		
		$error = "This is an error";
		$_SESSION["errors"] = $error;
		$result = errors();
		$this->assertEquals($result, $error);
		$this->assertNull($_SESSION["errors"]);
	}
}
?>