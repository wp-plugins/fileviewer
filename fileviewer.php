<?php
/*
Plugin Name: Fileviewer
Plugin URI: http://www.vievern.com/wordpress_plugins
Description: Fileviewer it is plugin for Wordpress that adds file-manager in your wp-admin panel.
Version: 1.0b
Author: Vievern
Author URI: http://vievern.com
*/

function viev_fileviewer_blog ()
{
?><br>
<iframe name="viev_fv" src="<?= get_option('siteurl'); ?>/fileviewer.php?s=<?= get_option('vievs_fileviewer_key'); ?>" marginwidth="1" marginheight="1" height="530" width="620" title="Blog" border="0" frameborder="0">
Iframe Error
</iframe>
<?
}

function viev_fileviewer_site ($url)
{
?><br>
<iframe name="viev_fv" src="<?= $url; ?>/fileviewer.php?s=<?= get_option('vievs_fileviewer_key'); ?>" marginwidth="1" marginheight="1" height="530" width="620" title="<?= $url; ?>" border="0" frameborder="0">
Iframe Error
</iframe>
<?
}

function viev_fileviewer_setup ()
{

if(!empty($_POST['submit'])){
update_option('vievs_fileviewer_site1',$_POST['vievs_fileviewer_site1']);
update_option('vievs_fileviewer_site2',$_POST['vievs_fileviewer_site2']);
update_option('vievs_fileviewer_site3',$_POST['vievs_fileviewer_site3']);
echo '<h2>Saving...</h2><script type="text/javascript">top.location.href="?page=fileviewer_setup";</script>';
}
else
{

?>
<form action="" method="POST">
<h2>Setup Fileviewer:</h2>
<br><br>
<b>Your security key - [&nbsp;&nbsp;&nbsp;<?= get_option('vievs_fileviewer_key'); ?>&nbsp;&nbsp;&nbsp;]</b><br><br>
<br><br>
<b>Extra Site 1:</b><br>
<input style="width: 400px;" name="vievs_fileviewer_site1" type="text" value="<?= get_option('vievs_fileviewer_site1'); ?>" alt="Extra Site 1" title="Extra Site 1"> <span style="font-size: 12px; color: #AAAAAA;">example: <b><?= get_option('siteurl'); ?></b></span><br><br>
<b>Extra Site 2:</b><br>
<input style="width: 400px;" name="vievs_fileviewer_site2" type="text" value="<?= get_option('vievs_fileviewer_site2'); ?>" alt="Extra Site 2" title="Extra Site 2"> <span style="font-size: 12px; color: #AAAAAA;">example: <b><?= get_option('siteurl'); ?></b></span><br><br>
<b>Extra Site 3:</b><br>
<input style="width: 400px;" name="vievs_fileviewer_site3" type="text" value="<?= get_option('vievs_fileviewer_site3'); ?>" alt="Extra Site 3" title="Extra Site 3"> <span style="font-size: 12px; color: #AAAAAA;">example: <b><?= get_option('siteurl'); ?></b></span><br><br>
<input type="hidden" name="submit" value="yes">
<span style="font-size: 12px; color: #AAAAAA;"><b>TIP1:</b> Also, you can activate "Delete-Mode": just change <b>$fileviewer_delo = false;</b> to <b>$fileviewer_delo = true;</b> in config file.<br>
<b>TIP2:</b> Place <b>"fileviewer.php"</b> and <b>"fileviewer_conf.php"</b> to your another site's root directory, then change site url in setup.</span><br><br>
<button style="padding: 6px;">Save Options</button>
</form>
<br><br>
Fileviewer by <a href="http://www.vievern.com/" target="_blank">Vievern</a><br>
Figue Icons by <a href="http://p.yusukekamiyamane.com/" target="_blank">Yusuke Kamiyamane</a>
<?
}

}

function viev_fileviewer_site1 ()
{
viev_fileviewer_site (get_option('vievs_fileviewer_site1'));
}
function viev_fileviewer_site2 ()
{
viev_fileviewer_site (get_option('vievs_fileviewer_site2'));
}
function viev_fileviewer_site3 ()
{
viev_fileviewer_site (get_option('vievs_fileviewer_site3'));
}

function viev_fileviewer_menu ()
{
add_menu_page('Fileviewer','Fileviewer', 'edit_themes', 'fileviewer', 'viev_fileviewer_blog', plugin_dir_url( __FILE__ ).'/icons/folder.png');

// ЦИКЛ НАЧИНАЕТСЯ ЗДЕСЬ

if($viev_go = get_option('vievs_fileviewer_site1')){
add_submenu_page('fileviewer',$viev_go, $viev_go, 'edit_themes', 'fileviewer_site1', 'viev_fileviewer_site1');
}

if($viev_go = get_option('vievs_fileviewer_site2')){
add_submenu_page('fileviewer',$viev_go, $viev_go, 'edit_themes', 'fileviewer_site2', 'viev_fileviewer_site2');
}

if($viev_go = get_option('vievs_fileviewer_site3')){
add_submenu_page('fileviewer',$viev_go, $viev_go, 'edit_themes', 'fileviewer_site3', 'viev_fileviewer_site3');
}

// ЦИКЛ ЗАКАНЧИВАЕТСЯ ЗДЕСЬ

add_submenu_page('fileviewer','Options', 'Options', 'edit_themes', 'fileviewer_setup', 'viev_fileviewer_setup');

}

function vievs_fileviewer_act ()
{
$vfv_key = md5(time().rand(0,100));
update_option('vievs_fileviewer_key',$vfv_key);
$vfv_confurl = '..' . str_replace(get_option('siteurl'),'',plugin_dir_url(__FILE__));
$fp = fopen('../fileviewer_conf.php', 'w'); 
fwrite($fp, '<?php '.chr(13).chr(10).'$fileviewer_icons = \''.plugin_dir_url(__FILE__).'icons/\'; '.chr(13).chr(10).'$fileviewer_key = \''.$vfv_key.'\'; '.chr(13).chr(10).'$fileviewer_delo = false; '.chr(13).chr(10).'?>'); 
fclose($fp);

copy($vfv_confurl.'fileviewer_api.php','../fileviewer.php');

unset($vfv_key,$vfv_confurl);

update_option('vievs_fileviewer_site1','');
update_option('vievs_fileviewer_site2','');
update_option('vievs_fileviewer_site3','');
}

function vievs_fileviewer_dea ()
{
delete_option('vievs_fileviewer_key');
unlink('../fileviewer_conf.php');
unlink('../fileviewer.php');

delete_option('vievs_fileviewer_site1');
delete_option('vievs_fileviewer_site2');
delete_option('vievs_fileviewer_site3');
}

add_action('admin_menu', 'viev_fileviewer_menu');

register_activation_hook( __FILE__, 'vievs_fileviewer_act' );
register_deactivation_hook( __FILE__, 'vievs_fileviewer_dea' );

?>