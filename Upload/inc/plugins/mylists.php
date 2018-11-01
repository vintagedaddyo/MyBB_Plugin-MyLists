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
 
 // Future features:
 // * Individual user todo list
 // * Todo categories
 // * Search todo's.

if(!defined("IN_MYBB")) {
    die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook('global_start', 'mylists_usercp_start');

// MyLists information, this is displayed on the plugins page

function mylists_info() {

   global $lang;

    $lang->load("mylists");
    
    $lang->mylists_Desc = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="float:right;">' .
        '<input type="hidden" name="cmd" value="_s-xclick">' . 
        '<input type="hidden" name="hosted_button_id" value="AZE6ZNZPBPVUL">' .
        '<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">' .
        '<img alt="" border="0" src="https://www.paypalobjects.com/pl_PL/i/scr/pixel.gif" width="1" height="1">' .
        '</form>' . $lang->mylists_Desc;

    return Array(
        'name' => $lang->mylists_Name,
        'description' => $lang->mylists_Desc,
        'website' => $lang->mylists_Web,
        'author' => $lang->mylists_Auth,
        'authorsite' => $lang->mylists_AuthSite,
        'version' => $lang->mylists_Ver,
        'compatibility' => $lang->mylists_Compat
    );
}

// All the activation processes go here

function mylists_activate()
{
	global $db, $lang;

    $lang->load("mylists");
	
		
	$db->query("CREATE TABLE `".TABLE_PREFIX."my_lists` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`title` TEXT NOT NULL ,
`name` TEXT NOT NULL ,
`nameid` TEXT NOT NULL ,
`priority` TEXT NOT NULL
)  ");
  
    require MYBB_ROOT.'/inc/adminfunctions_templates.php';
    
    // Add the menu item to the bottom of the usercp nav_menu
    
    find_replace_templatesets("usercp_nav_misc", '#'.preg_quote('{$lang->ucp_nav_view_profile}').'#' , '{$lang->ucp_nav_view_profile}</a></td></tr>
    <tr><td class="trow1 smalltext"><a href="mylists.php" class="usercp_nav_item usercp_nav_fsubscriptions">{$lang->mylists_usercp_nav}');
	
	// Create settings
	
     $mylists_group = array(
        'gid'            => '0',
        "name"           => "mylists",
        'title'          => ''.$lang->mylists_option_0_Title.'', 
        'description'    => ''.$lang->mylists_option_0_Description.'', 
        'disporder'      => '1',
        'isdefault'      => '0'
    );
    
    $db->insert_query("settinggroups", $mylists_group);
 
    $gid = $db->insert_id(); // This will get the id of the just added record in the db
	
	$mylists_setting_1 = array(
        'sid'            => '0',
        'name'           => 'mylists_setting1',
        'title'          => ''.$lang->mylists_option_1_Title.'', 
        'description'    => ''.$lang->mylists_option_1_Description.'', 
        'optionscode'    => 'yesno',
        'value'          => '1',
        'disporder'      => '1',
        'gid'            => intval($gid)
    );
    
	$db->insert_query("settings", $mylists_setting_1);
 
// rebuild_settings();
	
	$mylists_setting_2 = array(
        'sid'            => '0',
        'name'           => 'mylists_setting2',
        'title'          => ''.$lang->mylists_option_2_Title.'', 
        'description'    => ''.$lang->mylists_option_2_Description.'', 
        'optionscode'    => 'yesno',
        'value'          => '0',
        'disporder'      => '2',
        'gid'            => intval($gid)
    );
    
	$db->insert_query("settings", $mylists_setting_2);

// rebuild_settings();
	
	$mylists_setting_3 = array(
        'sid'            => '0',
        'name'           => 'mylists_setting3',
        'title'          => ''.$lang->mylists_option_3_Title.'', 
        'description'    => ''.$lang->mylists_option_3_Description.'', 
        'optionscode'    => 'text',
        'value'          => '7',
        'disporder'      => '3',
        'gid'            => intval($gid)
    );
    
	$db->insert_query("settings", $mylists_setting_3);

// rebuild_settings();
	
	$mylists_setting_4 = array(
        'sid'            => '0',
        'name'           => 'mylists_setting4',
        'title'          => ''.$lang->mylists_option_4_Title.'', 
        'description'    => ''.$lang->mylists_option_4_Description.'', 
        'optionscode'    => 'text',
        'value'          => '',
        'disporder'      => '4',
        'gid'            => intval($gid)
    );
    
	$db->insert_query("settings", $mylists_setting_4);

// rebuild_settings();
	
	$mylists_setting_5 = array(
        'sid'            => '0',
        'name'           => 'mylists_setting5',
        'title'          => ''.$lang->mylists_option_5_Title.'', 
        'description'    => ''.$lang->mylists_option_5_Description.'', 
        'optionscode'    => 'text',
        'value'          => '4',
        'disporder'      => '5',
        'gid'            => intval($gid)
    );
    
	$db->insert_query("settings", $mylists_setting_5);

// rebuild_settings();
	
	$mylists_setting_6 = array(
        'sid'            => '0',
        'name'           => 'mylists_setting6',
        'title'          => ''.$lang->mylists_option_6_Title.'', 
        'description'    => ''.$lang->mylists_option_6_Description.'', 
        'optionscode'    => 'text',
        'value'          => ''.$lang->mylists_option_6_Value.'', 
        'disporder'      => '6',
        'gid'            => intval($gid)
    );
    
	$db->insert_query("settings", $mylists_setting_6);

 rebuild_settings();

}

// All deactivation processes go here

function mylists_deactivate()
{
	global $db;

	$db->drop_table("my_lists");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='mylists_setting1'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='mylists_setting2'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='mylists_setting3'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='mylists_setting4'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='mylists_setting5'");
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE name='mylists_setting6'");
    $db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE name='mylists'");

 rebuild_settings();
	
	require MYBB_ROOT.'/inc/adminfunctions_templates.php';
	
	// Remove the menu item to the bottom of the usercp nav_menu

	find_replace_templatesets("usercp_nav_misc", '#'.preg_quote('{$lang->ucp_nav_view_profile}</a></td></tr>
    <tr><td class="trow1 smalltext"><a href="mylists.php" class="usercp_nav_item usercp_nav_fsubscriptions">{$lang->mylists_usercp_nav}').'#' , '{$lang->ucp_nav_view_profile}',0);
}

function mylists_usercp_start()
{
    global $mybb, $lang;

    // Load the language file for usercp nav item   

    $lang->load("mylists");   
}

?>