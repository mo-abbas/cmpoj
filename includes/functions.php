<?php
	function redirect_to($new_location)
	{
		header("Location: " . $new_location);
		exit;
	}

	function mysql_prep($string)
	{
		global $connection;
		$escaped_string = mysqli_real_escape_string($connection, $string);
		return $escaped_string;
	}

	function confirm_query($result_set)
	{
		if (!$result_set) {
			die("Database query failed.");
		}
	}

	function fieldname_as_text($fieldname)
	{
		$fieldname = str_replace("_", " ", $fieldname);
		$fieldname = ucfirst($fieldname);
		return $fieldname;
	}

	function query_result_to_array($result)
	{
		$output = array();
		while($row = mysqli_fetch_assoc($result))
		{
			$output[] = $row;
		}
		return $output;
	}

	function form_errors($errors=array()) {
		$output = "";
		if (!empty($errors)) {
		  $output .= "<div class=\"error\">";
		  $output .= "Please fix the following errors:";
		  $output .= "<ul>";
		  foreach ($errors as $key => $error) {
		    $output .= "<li>";
		    $output .= htmlentities($error) . "</li>";
		  }
		  $output .= "</ul>";
		  $output .= "</div>";
		}
		return $output;
	}

	function find_team_by_id($id)
	{
		global $connection;

		$id = mysql_prep($id);

		$query  = "SELECT * ";
		$query .= "FROM team ";
		$query .= "WHERE id = {$id} ";
		$query .= "LIMIT 1";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		if ($team = mysqli_fetch_assoc($result))
		{
			return $team;
		}
		else
		{
			return null;
		}
	}

	function find_contestant_by_handle($handle)
	{
		global $connection;

		$safe_handle = mysql_prep($handle);

		$query  = "SELECT * ";
		$query .= "FROM contestant ";
		$query .= "WHERE handle = '{$safe_handle}' ";
		$query .= "LIMIT 1";
		$result = mysqli_query($connection, $query);
		confirm_query($result);

		if ($contestant = mysqli_fetch_assoc($result))
		{
			return $contestant;
		}
		else
		{
			return null;
		}
	}

	function find_contestant_by_id($id)
	{
		global $connection;

		$safe_id = mysql_prep($id);

		$query  = "SELECT * ";
		$query .= "FROM contestant ";
		$query .= "WHERE id = {$id} ";
		$query .= "LIMIT 1";
		$result = mysqli_query($connection, $query);
		confirm_query($result);

		if ($contestant = mysqli_fetch_assoc($result))
		{
			return $contestant;
		}
		else
		{
			return null;
		}
	}

	function find_account_by_handle($handle)
	{
		global $connection;

		$safe_handle = mysql_prep($handle);

		$query  = "SELECT * ";
		$query .= "FROM (contestant JOIN account ON contestant.id = account.id) ";
		$query .= "WHERE handle = '{$safe_handle}' ";
		$query .= "LIMIT 1";
		$result = mysqli_query($connection, $query);
		confirm_query($result);

		if ($account = mysqli_fetch_assoc($result))
		{
			return $account;
		}
		else
		{
			return null;
		}
	}

	function find_account_by_email($email)
	{
		global $connection;

		$safe_email = mysql_prep($email);

		$query  = "SELECT * ";
		$query .= "FROM account ";
		$query .= "WHERE email = '{$safe_email}' ";
		$query .= "LIMIT 1";
		$result = mysqli_query($connection, $query);
		confirm_query($result);

		if ($account = mysqli_fetch_assoc($result))
		{
			return $account;
		}
		else
		{
			return null;
		}
	}

	/*function find_account_by_id($id)
	{
		global $connection;

		$safe_id = mysql_prep($id);

		$query  = "SELECT handle ";
		$query .= "FROM contestant ";
		$query .= "WHERE id = '{$safe_id}' ";
		$query .= "LIMIT 1";
		$result = mysqli_query($connection, $query);
		confirm_query($result);

		if ($account = mysqli_fetch_assoc($result))
		{
			return $account;
		}
		else
		{
			return null;
		}
	}
*/
	function find_contest_by_id($id)
	{
		global $connection;

		$safe_id = mysql_prep($id);

		$query  = "SELECT * ";
		$query .= "FROM contest ";
		$query .= "WHERE id = {$id} ";
		$query .= "LIMIT 1";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		if($contest = mysqli_fetch_assoc($result))
		{
			return $contest;
		}
		else
		{
			return null;
		}
	}

	function find_problem_by_id($id)
	{
		global $connection;

		$safe_id = mysql_prep($id);

		$query  = "SELECT * ";
		$query .= "FROM problem ";
		$query .= "WHERE id = {$safe_id} ";
		$query .= "LIMIT 1";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		if($problem = mysqli_fetch_assoc($result))
		{
			return $problem;
		}
		else
		{
			return null;
		}
	}

	function find_submission_by_id($id)
	{
		global $connection;

		$safe_id = mysql_prep($id);

		$query  = "SELECT * ";
		$query .= "FROM submission ";
		$query .= "WHERE id = {$safe_id} ";
		$query .= "LIMIT 1";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		if($problem = mysqli_fetch_assoc($result))
		{
			return $problem;
		}
		else
		{
			return null;
		}
	}

	function find_compiler_by_id($id)
	{
		global $connection;

		$safe_id = mysql_prep($id);

		$query  = "SELECT * ";
		$query .= "FROM compiler ";
		$query .= "WHERE id = {$safe_id} ";
		$query .= "LIMIT 1";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		if($compiler = mysqli_fetch_assoc($result))
		{
			return $compiler;
		}
		else
		{
			return null;
		}
	}

	function find_compiler_by_code($code)
	{
		global $connection;

		$safe_code = mysql_prep($code);

		$query  = "SELECT * ";
		$query .= "FROM compiler ";
		$query .= "WHERE code = '{$safe_code}' ";
		$query .= "LIMIT 1";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		if($compiler = mysqli_fetch_assoc($result))
		{
			return $compiler;
		}
		else
		{
			return null;
		}
	}

	function find_contestant_in_contest($contestant_id, $contest_id)
	{
		global $connection;

		$contestant_id = mysql_prep($contestant_id);
		$contest_id = mysql_prep($contest_id);

		$query  = "SELECT * ";
		$query .= "FROM contestant_joins ";
		$query .= "WHERE contestant_id = {$contestant_id} ";
		$query .= "AND contest_id = {$contest_id} 	";
		$query .= "LIMIT 1";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		$contestant_joins = mysqli_fetch_assoc($result);
		mysqli_free_result($result);

		if($contestant_joins)
		{
			return $contestant_joins;
		}
		else
		{
			return null;
		}
	}

	function get_all_contestants()
	{
		global $connection;

		$query  = "SELECT * ";
		$query .= "FROM contestant ";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		return query_result_to_array($result);
	}

	function get_all_compilers()
	{
		global $connection;

		$query  = "SELECT * ";
		$query .= "FROM compiler ";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		return query_result_to_array($result);
	}

	function get_all_compilers_in_contest($id)
	{
		global $connection;

		$safe_id = mysql_prep($id);

		$query  = "SELECT * ";
		$query .= "FROM ";
		$query .= "available_compiler ";
		$query .= "JOIN ";
		$query .= "compiler ";
		$query .= "ON compiler_id = id ";
		$query .= "WHERE contest_id = {$safe_id}";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		return query_result_to_array($result);
	}
	function get_standings_in_contest($contest_id)
	{
		global $connection;

		$id = mysql_prep($contest_id);

		$query  = "SELECT contestant.id, contestant.handle, contestant_joins.score ";
		$query .= "FROM ";
		$query .= "contest JOIN contestant_joins ";
		$query .= "ON contest.id = {$id} AND contest.id = contestant_joins.contest_id ";
		$query .= "JOIN contestant ";
		$query .= "ON contestant.id = contestant_joins.contestant_id ";
		$query .= "ORDER BY contestant_joins.rank ";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		$contestants = query_result_to_array($result);
		$temp = array();
		foreach ($contestants as $contestant)
		{
			$temp[$contestant["id"]]=$contestant;
			$temp[$contestant["id"]]["problems"] = array();
		}

		$contestants = $temp;
		$temp = null;

		$query  = "SELECT solves.* ";
		$query .= "FROM ";
		$query .= "contest JOIN problem ";
		$query .= "ON contest.id = {$id} AND contest.id = problem.contest_id ";
		$query .= "JOIN solves ";
		$query .= "ON solves.problem_id = problem.id ";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		$contestant_problem_stats = query_result_to_array($result);

		foreach ($contestant_problem_stats as $problem)
		{
			$contestants[$problem["contestant_id"]]["problems"][$problem["problem_id"]] = $problem;
		}

		$standings = array();

		foreach ($contestants as $contestant)
		{
			$standings[] = $contestant;
		}

		return $standings;
	}

	function get_all_problems_in_contest($contest_id)
	{
		global $connection;

		$id = mysql_prep($contest_id);

		$query  = "SELECT * ";
		$query .= "FROM problem ";
		$query .= "WHERE contest_id = {$id} ";
		$query .= "ORDER BY problem.id ";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		return query_result_to_array($result);
	}

	function get_all_samples_in_problem($id)
	{
		global $connection;

		$safe_id = mysql_prep($id);

		$query  = "SELECT * ";
		$query .= "FROM samples ";
		$query .= "WHERE problem_id = {$safe_id}";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		return query_result_to_array($result);
	}

	function get_all_categories_in_problem($id)				//added
	{
		global $connection;

		$safe_id = mysql_prep($id);


		$query  = "SELECT category ";
		$query .= "FROM problem_category ";
		$query .= "WHERE problem_id = {$safe_id}";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		return query_result_to_array($result);
	}

	function get_problem_by_level($level)
	{
		global $connection;


		$query  = "SELECT title,id,level ";
		$query .= "FROM problem ";
		$query .= "WHERE level = {$level}";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		return query_result_to_array($result);

	}

	function get_problem_by_category($category)
	{
		global $connection;

		$category = mysql_prep($category);

		$query ="SELECT title,id,level ";
		$query .="FROM problem_category , problem ";
		$query .=" WHERE problem_id=id AND category = '{$category}';";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		return query_result_to_array($result);
	}

	//pending here is to get the submissions that aren't judged yet.
	function get_all_submissions_in_contest($id)
	{
		global $connection;

		$safe_id = mysql_prep($id);

		$query  = "SELECT S.*, C.handle, P.title ";
		$query .= "FROM ";
		$query .= "submission AS S ";
		$query .= "JOIN ";
		$query .= "problem AS P ";
		$query .= "ON S.problem_id = P.id ";
		$query .= "JOIN ";
		$query .= "contestant AS C ";
		$query .= "ON C.id = S.contestant_id ";
		$query .= "WHERE P.contest_id = {$id} ";
		$query .= "ORDER BY S.time DESC";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		return query_result_to_array($result);
	}

	function get_all_submissions_in_problem($id)
	{
		global $connection;

		$safe_id = mysql_prep($id);

		$query  = "SELECT S.*, C.handle ";
		$query .= "FROM ";
		$query .= "submission AS S ";
		$query .= "JOIN ";
		$query .= "contestant AS C ";
		$query .= "ON C.id = S.contestant_id ";
		$query .= "WHERE S.problem_id = {$id} ";
		$query .= "ORDER BY S.time ";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		return query_result_to_array($result);
	}
	function password_encrypt($password)
	{
		$hash_format = "$2y$10$";   // Tells PHP to use Blowfish with a "cost" of 10
		$salt_length = 22; 					// Blowfish salts should be 22-characters or more
		$salt = generate_salt($salt_length);
		$format_and_salt = $hash_format . $salt;
		$hash = crypt($password, $format_and_salt);
		return $hash;
	}

	function generate_salt($length)
	{
		// Not 100% unique, not 100% random, but good enough for a salt
		// MD5 returns 32 characters
		$unique_random_string = md5(uniqid(mt_rand(), true));

		// Valid characters for a salt are [a-zA-Z0-9./]
		$base64_string = base64_encode($unique_random_string);

		// But not '+' which is valid in base64 encoding
		$modified_base64_string = str_replace('+', '.', $base64_string);

		// Truncate string to the correct length
		$salt = substr($modified_base64_string, 0, $length);

		return $salt;
	}

	function password_check($password, $existing_hash)
	{
		// existing hash contains format and salt at start
		$hash = crypt($password, $existing_hash);
		if ($hash === $existing_hash)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	function logged_in()
	{
		if(isset($_SESSION["id"]))
			return true;
		else
			return false;
	}

	function check_logged_in()
	{
		if(!logged_in())
			redirect_to("login.php");
	}

	//____________________________ADDED_________________________
	// function get_problem_by_category($category)
	// {
		// global $connection;

		// $category = mysql_prep($category);

		// $query ="SELECT title,id,level ";
		// $query .="FROM problem_category  ,problem ";
		// $query .=" WHERE problem_id=id AND category = "."'{$category}';";

		// $result = mysqli_query($connection, $query);
		// confirm_query($result);

		// return query_result_to_array($result);
	// }
	function get_solved_problem_by_id($id)
	{
		global $connection;
		$category = mysql_prep($id);
		$query ="SELECT * ";
		$query .="FROM solves AS S ";
		$query .="JOIN problem AS P ";
		$query .=" ON S.problem_id = P.id ";
		$query .="WHERE S.contestant_id = {$id} AND S.ac_time<>'1000-01-01 00:00:00';";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		return query_result_to_array($result);
	}
	function get_team_handle_by_account_id($id)
	{
		global $connection;
		$category = mysql_prep($id);

		$query = "SELECT handle,team_id ";
		$query .= "FROM contestant c, account a ";
		$query .= "WHERE a.id={$id} AND c.id=a.team_id ;";

		$result = mysqli_query($connection, $query);
		confirm_query($result);
		$team_id=mysqli_fetch_assoc($result);
		return $team_id;
	}

	function get_teams_member($id)
	{
		global $connection;
		$category = mysql_prep($id);

		$query = "SELECT * ";
		$query .= "FROM contestant c, account a ";
		$query .= "WHERE  a.team_id ={$id}  AND a.id =c.id ;";
		$result = mysqli_query($connection, $query);
		confirm_query($result);

		$team = query_result_to_array($result);
		return $team ? $team : null;
	}
	function get_team_handle($id)
	{
		global $connection;
		$category = mysql_prep($id);

		$query = "SELECT c.handle ";
		$query .= "FROM team t ,contestant c ";
		$query .= "WHERE  t.id ={$id} AND c.id=t.id  ;";

		$result = mysqli_query($connection, $query);
		confirm_query($result);
		$team_handle=mysqli_fetch_assoc($result);
		if($team_handle)
			return $team_handle["handle"];

		return null;
	}

	function get_contestant_rating()
	{
		global $connection;
		$query = "SELECT  a.id ,c.handle ,SUM(acc_problems) AS Score  ";
		$query .= "FROM account a,contestant c,contestant_joins j ";
		$query .= "where a.id=c.id AND a.id=j.contestant_id ";
		$query .= "GROUP BY a.id ORDER BY Score DESC, c.handle LIMIT 20; ";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		return query_result_to_array($result);
	}
	function get_team_rating()
	{
		global $connection;
		$query = "SELECT  t.id ,c.handle ,SUM(acc_problems) AS Score  ";
		$query .= "FROM account a,contestant c,team t ,contestant_joins j	";
		$query .= "where t.id=c.id AND t.id=j.contestant_id ";
		$query .= "GROUP BY t.id ORDER BY score DESC, c.handle LIMIT 20; ";

		$result = mysqli_query($connection, $query);
		confirm_query($result);

		return query_result_to_array($result);
	}

	function get_status_submissions($id ,$status)
	{
		global $connection;
		$category = mysql_prep($id);

		$query = "SELECT * ";
		$query .= "FROM submission ";
		$query .= " WHERE problem_id={$id}   AND status ="."'{$status}';";
		$result = mysqli_query($connection, $query);

		confirm_query($result);

		return query_result_to_array($result);
	}
	function get_stat_problem_in_contest($id,$status)
	{

		global $connection;
		$category = mysql_prep($id);

		$query = "SELECT * ";
		$query .= "FROM  problem p JOIN submission s ON s.problem_id=p.id ";
		$query .= " WHERE 	p.contest_id={$id} AND status ="."'{$status}';";
		$result = mysqli_query($connection, $query);

		confirm_query($result);

		return query_result_to_array($result);
	}

	function get_categories_sloved_by_contestant($id)
	{

		global $connection;
		$category = mysql_prep($id);

		$query  = "select category, count(*) AS count  ";
		$query .= " from  problem_category as p join solves as s on s.problem_id=p.problem_id   ";
		$query .= " WHERE  s.contestant_id ={$id} AND s.ac_time <> '1000-01-01 00:00:00' ";
		$query .= " GROUP BY category ; ";
		$result = mysqli_query($connection, $query);

		confirm_query($result);

		return query_result_to_array($result);
	}

	function get_all_contestant_submissions($id)
	{
		global $connection;

		$safe_id=mysql_prep($id);

		$query  ="SELECT * ";
		$query .="FROM submission ";
		$query .="WHERE contestant_id={$id}";

		$result=mysqli_query($connection,$query);
		confirm_query($result);

		return query_result_to_array($result);
	}
?>
