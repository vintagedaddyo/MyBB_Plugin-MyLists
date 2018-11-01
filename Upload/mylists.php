<?php
/*
 * MyBB: MyLists
 *
 * File: mylists.php
 * 
 * Authors: Joe Cotton & Vintagedaddyo
 *
 * MyBB Version: 1.8
 *
 * Plugin Version: 1.3
 * 
 *
 */

define("IN_MYBB", 1);

require_once "./global.php";


$lang->load("mylists");


$errors = '';

if ($mybb->settings['mylists_setting2'] == '0') {
   if($mybb->user['uid'] == 0 || $mybb->usergroup['canusercp'] == 0)
   {
	error_no_permission();
   }
}

$perm_group = explode(",", $mybb->settings['mylists_setting5']);

for($x=0;$x<count($perm_group);$x++)
{
	if ($mybb->user['usergroup'] == $perm_group[$x]) {
	$addtodo = "<strong><a href='mylists.php?act=submit'>{$lang->mylists_add_todo}</a></strong>";
	}
}

$group = explode(",", $mybb->settings['mylists_setting3']);

for($x=0;$x<count($group);$x++)
{
	if ($mybb->user['usergroup'] == $group[$x]) {
		error_no_permission();
	}
}

if ($mybb->settings['mylists_setting1'] == '0') {
	error_no_permission();
}

$number = '1';

$act=$_GET['act'];
if ($act == '') {
	add_breadcrumb($mybb->settings['mylists_setting6'], "mylists.php");
	$get_mylists=$db->simple_select("my_lists","*");
	$i=1;
	while ($row = $db->fetch_array($get_mylists)) {

		if ($mybb->user['username'] == $mybb->settings['mylists_setting4']) {
			$remove_todo = "<td><a href='mylists.php?act=delete&id={$row['id']}'>{$lang->mylists_remove_todo}</a></td>";
		} else {
	$remove_todo = "<td><i>{$lang->mylists_none}</i></td>";
		}
			
		if ($row['priority'] == "{$lang->mylists_priority_high}"){
			$mylists.="<tr class='trow$i'><td align=center style='color:red;'>{$number}. </td><td style='color:red;'>{$row['title']}</td><td><a href='member.php?action=profile&uid={$row['nameid']}'>{$row['name']}</a></td>{$remove_todo}</tr>";
		} elseif($row['priority'] == "{$lang->mylists_priority_medium}") {
			$mylists.="<tr class='trow$i'><td align=center style='color:blue;'>{$number}. </td><td style='color:blue;'>{$row['title']}</td><td><a href='member.php?action=profile&uid={$row['nameid']}'>{$row['name']}</a></td>{$remove_todo}</tr>";
		} elseif($row['priority'] == "{$lang->mylists_priority_low}") {
			$mylists.="<tr class='trow$i'><td align=center style='color:green;'>{$number}. </td><td style='color:green;'>{$row['title']}</td><td><a href='member.php?action=profile&uid={$row['nameid']}'>{$row['name']}</a></td>{$remove_todo}</tr>";
		} else {
			$mylists.="<tr class='trow$i'><td align=center>{$number}. </td><td>{$row['title']}</td><td><a href='member.php?action=profile&uid={$row['nameid']}'>{$row['name']}</a></td>{$remove_todo}</tr>";
		}
		
		$i=($i == 1)?2:1;
		$number++;
	}
	if ($mylists == '') {
		$mylists.="<tr class='trow1'><td colspan='4'>{$lang->mylists_no_todo}</td></tr>";
	}
	$page="<html>
<head>
<title>{$mybb->settings['bbname']} - {$mybb->settings['mylists_setting6']}</title>
{$headerinclude}
</head>
<body>
{$header}

<table border=\"0\" cellspacing=\"{$theme['borderwidth']}\" cellpadding=\"{$theme['tablespace']}\" class=\"tborder\" style=\"clear: both;\">
<tr><td class=thead colspan=4><strong>{$mybb->settings['mylists_setting6']}</strong></td></tr>
<tr><td class=tcat style='width:30px;'>{$lang->mylists_action}</td><td class=tcat>{$lang->mylists_todo_title}</td><td class=tcat>{$lang->mylists_posted_by}</td><td class=tcat>{$lang->mylists_action}</td></tr>
{$mylists}
<tr class='trow1'><td colspan='3'>{$addtodo}</td><td style='float:right;width:190px;'>{$lang->mylists_administrator} {$mybb->settings['mylists_setting4']}</td></tr>
</table>
{$loggedin}
<br />
{$footer}
</body>
</html>";
	output_page($page);
}
elseif ($act == 'submit') {
	if ($mybb->user['uid'] == '') {
		error_no_permission();
	}
	
	// show the form
	
	if ($mybb->input['title'] == '') {
		add_breadcrumb($mybb->settings['mylists_setting6'], "mylists.php");
		add_breadcrumb("{$lang->mylists_brcmb_new_todo}", "mylists.php?act=submit");
		$page="<html>
	<head>
<title>{$mybb->settings['bbname']} - {$mybb->settings['mylists_setting6']} > New todo</title>
{$headerinclude}
</head>
<body>
	{$header}
	<table border=\"0\" cellspacing=\"{$theme['borderwidth']}\" cellpadding=\"{$theme['tablespace']}\" class=\"tborder\" style=\"clear: both;\">
<tr><td class=thead colspan=2><strong>{$lang->mylists_new_global_todo}</strong></td></tr>
<form action='' method='post'>
<tr class='trow1'><td style='width:100px;'>{$lang->mylists_title}</td><td><input type='text' name='title' style='width:200px;'/></td></tr>
<tr class='trow1'><td style='width:100px;'>{$lang->mylists_priority}</td><td><select name='priority'><option name='{$lang->mylists_priority_normal}'>{$lang->mylists_priority_normal}</option><option name='{$lang->mylists_priority_high}' style='color:red;'>{$lang->mylists_priority_high}</option><option name='{$lang->mylists_priority_medium}' style='color:blue;'>{$lang->mylists_priority_medium}</option><option name='{$lang->mylists_priority_low}' style='color:green;'>{$lang->mylists_priority_low}</option></select></td></tr>
<tr class='trow1'><td colspan='2'><input type='submit' value='{$lang->mylists_add_todo_btn}'/></td></tr>
</table>
{$footer}
</body>
</html>";

output_page($page);

	}
	else {
		$insert['nameid'] = $mybb->user['uid'];
		$insert['name'] = $mybb->user['username'];
		$insert['title'] = $db->escape_string($mybb->input['title']);
		$insert['priority'] = $db->escape_string($mybb->input['priority']);
		$db->insert_query("my_lists",$insert);
		redirect("mylists.php", "{$lang->mylists_add_success}");
	}
}
elseif ($act == 'delete') {
	$id = $_GET['id'];
	$db->query("DELETE FROM ".TABLE_PREFIX."my_lists WHERE id='" . $id . "'");
	redirect("mylists.php", "{$lang->mylists_remove_success}");
}

?>
