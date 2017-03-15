<?php

$errors = array();

// * presence
// use trim() so empty spaces don't count
// use === to avoid false positives
// empty() would consider "0" to be empty
function has_presence($value) 
{
	return isset($value) && $value !== "";
}

function validate_presences($required_fields) 
{
	global $errors;
	// Expects an assoc. array
	foreach($required_fields as $field) 
	{
		if(is_array($_POST[$field]))
		{
			if(empty($_POST[$field]))
				$errors[$field] = fieldname_as_text($field) . " can't be blank";
			continue;
		}
		$value = trim($_POST[$field]);
	 	if (!has_presence($value)) 
	 	{
	    		$errors[$field] = fieldname_as_text($field) . " can't be blank";
	  	}
	}
}

function validate_arrays($fields)
{
	global $errors;

	foreach ($fields as $field) 
	{
		if(empty($_POST[$field]))
			$errors[$field] = fieldname_as_text($field) . " can't be blank";

		$found = false;
		foreach ($_POST[$field] as $element) 
		{
			if(trim($element) != "")
				$found = true;
		}
		if(!$found)
			$errors[$field] = fieldname_as_text($field) . " must have at least one entry";
	}
}

// * string length
// max length
function has_max_length($value, $max) 
{
	return strlen($value) <= $max;
}

function validate_max_lengths($fields_with_max_lengths) 
{
	global $errors;
	// Expects an assoc. array
	foreach($fields_with_max_lengths as $field => $max) 
	{
		$value = trim($_POST[$field]);
	  if (!has_max_length($value, $max) && !isset($errors[$field])) 
	  {
	    $errors[$field] = fieldname_as_text($field) . " is too long";
	  }
	}
}

function has_min_length($value, $min)
{
	return strlen($value) >= $min;
}

function validate_min_lengths($fields_with_min_lengths) 
{
	global $errors;
	
	foreach($fields_with_min_lengths as $field => $min) 
	{
		$value = trim($_POST[$field]);
	  if (!has_min_length($value, $min) && !isset($errors[$field])) 
	  {
	    $errors[$field] = fieldname_as_text($field) . " is too short";
	  }
	}
}

function does_match($string1, $string2)
{
	global $errors;

	if($string1 !== $string2)
		$errors["password"] = "Passwords don't match";
}

function is_a_number($string)
{
	return is_numeric($string);
}

function validate_numbers($fields_with_numbers) 
{
	foreach ($fields_with_numbers as $field) 
	{
		$value = trim($_POST[$field]);
		if(!is_a_number($field))
			$errors[$field] = fieldname_as_text($field) . " should have a number";
	}
}

function validate_unique($values)
{
	global $errors;

	foreach($values as $field => $value)
	{
		if($value)
			$errors[$field] = $value[$field] . " is already taken.";
	}
}

function validate_email($email)
{
	global $errors;

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
	{
  		$errors["email"] = "Email is invalid.";
  	}
}

function validate_date_time($date_time)
{
	global $errors;

	if(strtotime($date_time) === false)
		$errors["date"] = "Date is invalid.";
}
?>
