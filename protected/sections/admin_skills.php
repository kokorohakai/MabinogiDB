<?
if ($user->loggedIn()){
?>
<script src="/js/admin/skills.js"></script>
<script src="/js/admin/skills.talents.js"></script>
<script src="/js/admin/skills.AP.js"></script>
Select Race: <select id="Race_List"></select><br>
<table id="admin_skills"></table>
<div id="admin_pager"></div>
<?
} else {
?>
You are not authorized to view this page. Please log in.
<?
}
?><?
