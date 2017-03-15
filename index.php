<?php
include("Base.php");
?>
<div id="rightPan">
	<div class="divName">
		<span> ONLINE JUDGE </span>
	</div>
	<?php
		$error = errors();
		echo form_errors($error);
		echo message();
	?>

<div id="tfheader">
		<form id="tfnewsearch" method="GET" action="Searchresult.php">
		    <input type="text" class="tftextinput" name="Searchprob" size="21" maxlength="120">
			<input type="submit" value="search Problem by category" name="getByCategory" class="tfbutton">
		</form>
	<div class="tfclear"></div>
</div>
<br/>
<div id="tfheader">
		<form id="tfnewsearch" method="GET" action="Searchresult.php">
		    <input type="text" class="tftextinput" name="Searchprob" size="21" maxlength="120">
			<input type="submit" value="search Problem by Level" name="getByLevel" class="tfbutton">
		</form>
	<div class="tfclear"></div>
</div>




<?php
include("Footer.php");
?>