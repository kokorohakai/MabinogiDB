<?
if ($user->loggedIn()){
?>
<script src="/js/admin/races.js"></script>

<table id="admin_races"></table>
<div id="admin_pager"></div>
<?
} else {
?>
You are not authorized to view this page. Please log in.
<?
}
?>