<?
if ($user->loggedIn()){
?>
<script src="/js/admin/talents.js"></script>

<table id="admin_talents"></table>
<div id="admin_pager"></div>
<?
} else {
?>
You are not authorized to view this page. Please log in.
<?
}
?><?
