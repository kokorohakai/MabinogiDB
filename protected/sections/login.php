<?
if ($user->loggedIn()){
	?>
	<h2>Welcome <?=$_SESSION['username'];?></h2>
	You are now logged in. You can log out by clicking <a href="/login?logout=true">logout</a> at anytime.
	<?
} else {
	?>
	<script src="js/login.js"></script>
	<link type="text/css" rel="stylesheet" href="/css/login.css"/>
	<form action="" method="post">
		<fieldset class="login">
			<legend>Please Sign in to Continue</legend>
			<table>
				<tr>
					<td class="heading right">Username:</td>
					<td><input type="text" name="username" id="login_username"></td>
				</tr>
				<tr>
					<td class="heading right">Password:</td>
					<td><input type="password" name="password" id="login_password"></td>
				</tr>
				<tr>
					<td colspan=2 class="right">
						<button id="login_button">Login</button>
					</td>
				</tr>
			</table>
		</fieldset>
	</form>
	<?
}
?>