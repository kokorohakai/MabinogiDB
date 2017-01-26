<?
	chdir("../protected");
	require_once("utility_methods.php");
	require_once("modules/user.php");
	$user = new User;

	if ($section=="api"){
		require_once("modules/api.php");
		$api = new API;
	} else {
?>
<!DOCTYPE html>
<html>
	<meta charset="UTF-8"> 
	<head>
		<title>Mabinogi Database</title>
		<link type="text/css" rel="stylesheet" href="/js/jquery-ui/jquery-ui.min.css">
		<link type="text/css" rel="stylesheet" href="/js/jqGrid/css/ui.jqgrid.css">
		<link type="text/css" rel="stylesheet" href="/css/layout.css">
		<script src="/js/core/jquery.js"></script>
		<script src="/js/core/jqMigrate.js"></script>
		<script src="/js/jqGrid/js/i18n/grid.locale-en.js"></script>
		<script src="/js/jqGrid/js/jquery.jqGrid.min.js"></script>
		<script src="/js/jquery-ui/jquery-ui.min.js"></script>
		<script src="/js/core/CryptoJS/sha512.js"></script>
		<script src="/js/core/core.js"></script>
	</head>
	<body>
		<div class="body">
			<table class="body">
				<colgroup>
					<col class="body-nav-col">
					<col class="body-body-col">
				</colgroup>
				<thead class="header">
					<th colspan=2 class="header">
						<h1>Mabinogi Database</h1>
					</th>
				</thead>

				<tr class="body-row">
					<td class="navigator">
						<h2>Welcome</h2>
						<list>
							<a href="/">Home</a>
						</list>
						<h2>Applications</h2>
						<list>
							<a href="/">AP Calculator</a>
						</list>
						<?
						if ($user->loggedin()){
							?>
							<h2>Admin Panel</h2>
							<list>
								<a href="/admin/skills">Edit Skills</a><br>
								<a href="/admin/talents">Edit Talents</a><br>
								<a href="/admin/races">Edit Races</a><br>
								<a href="/login?logout=true">Logout</a>
							</list>
						<? }else{ ?>
							<h2>Admin Panel</h2>
							<list>
								<a href="/login">Login</a>
							</list>
							<br>
						<? } ?>
					</td>
					<td class="body-frame">
						<div id="body-errors" style="display:none">
						</div>
						<div class="body-content">
							<?
							if (file_exists("../protected/sections/".$section.".php")) {
								require("../protected/sections/".$section.".php");
							} else {
								require("../protected/sections/404.php");
							}
							?>
						</div>
					</td>
				</tr>
				<tr class="body-footer">
					<td></td>
					<td>
						<div class="legal">
							© 2015 Studio JAW. All rights reserved. Studio JAW, the Studio JAW logo, and the Maginogi Tools logo are conditioned 
							to trademarks and/or registered trademarks of Studio JAW. Mabinogi, and Mabinogi characters are property of Nexon Corp, 
							and were used in tasteful respect for the project. All other trademarks are property of their respective owners.<br>
							<br>
							<a href="//www.studiojaw.com/terms">Site Terms of use</a> | <a href="//www.studiojaw.com/privacy">Privacy Policy</a>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
<?
	}
?>