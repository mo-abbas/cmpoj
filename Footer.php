
		<div id="footer">Copyright &copy; <?php echo date("Y"); ?></div>
	</body>
</html>

<?php
	if(isset($connection))
	{
		mysqli_close($connection);
	}
?>
