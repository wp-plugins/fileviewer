<?php
/*
Plugin Name: Fileviewer
Plugin URI: http://www.vievern.com/wordpress_plugins
Description: Fileviewer it is plugin for Wordpress that adds file-manager to your wp-admin panel. From 2.0 version - totally rewrited, added file uploading and deleting.
Version: 2.0
Author: Vievern
Author URI: http://vievern.com
*/

if(basename($_SERVER['SCRIPT_FILENAME']) == 'filewatcher.php' ){
die();
}

function viev_fw_getroot($dir=0){
$fol = str_replace(site_url(),'',$_SERVER["PHP_SELF"]);
if($dir == 0){
return $fol;
}
else
{
$c = substr_count($fol,'/');
$fol = '';
while($c > 1){
$fol .= '../';
$c--;
}
return $fol;
}
}

function viev_fw_dirget($folder,$type=0,$thisfold,$viev_fw_icons){
$dir = opendir($folder);
while($log = readdir($dir)){
$logt = explode('.',$log);
if(viev_fw_dircheck($type, $log, $logt)){

$link = ($type==0 ? $_SERVER["PHP_SELF"].'?page=filewatcher&f=' : '').$thisfold.'/'.$log;

echo '
<tr class="viev_fw_hoverer">
	<td width="20"><a class="viev_fw_urler" href="'.$link.'"'.($type==0 ? '': ' target="_blank"').'><img src="'.$viev_fw_icons.( ($type == 0) ? 'folder.png' : (file_exists('../'.str_replace(site_url(),'',$viev_fw_icons).$logt[1].'.png') ? $logt[1] : 'unknown' ).'.png' ).'"></a></td>
	<td width="480" style="overflow: hidden;"><a class="viev_fw_urler" href="'.$link.'"'.($type==0 ? '': ' target="_blank"').'>'.$log.'</a></td>';

	if($type == 0){
	echo '<td width="200">&nbsp;</td>';
	} else {
	echo '<td width="100" align="right"><a href="'.$_SERVER["PHP_SELF"].'?page=filewatcher&f='.$thisfold.'&d='.$log.'"><img src="'.$viev_fw_icons.'deleter.png"></a>';
	}
	
echo '</tr>';
}
}
closedir;
}

function viev_fw_dircheck($type, $log, $logt){
if($type == 0){
if($log != '.' AND $log != '..' AND empty($logt[1])){ return true; } else { return false; }
}
else
{
if($log != '.' AND $log != '..' AND !empty($logt[1]) AND $logt[1] != 'htaccess' AND $logt[1] != 'wp-styles' AND $logt[1] != 'wp-scripts' AND $logt[1] != 'dev'  AND $logt[1] != 'wp-dependencies'){ return true; } else { return false; }
}
}

function viev_fw_manage(){
$viev_fw_icons = plugin_dir_url( __FILE__ ).'icons/';

echo '
<style>
.viev_fw_urler {
text-decoration: none;
color: #000000;
font-size: 12px;
}
.viev_fw_diver {
overflow: hidden;
width: 280px;
height: 16px;
}
.viev_fw_liner {
background: url("'.$viev_fw_icons.'liner.png");
}
.viev_fw_hoverer{
height: 16px;
vertical-align: middle;
}
.viev_fw_hoverer:hover {
background: url("'.$viev_fw_icons.'liner2.png");
}
.viev_fw_greeny {
background: #d7ead5;
}
.viev_fw_redy {
background: #ead5d5;
}
.viev_fw_now td {
padding-bottom: 5px;
}
.viev_fw_upload {
position: fixed;
bottom: 50px;
right: 20px;
}
</style>
';

$folder = $_GET['f'];

$folder = '../'.$folder;

echo '<table style="padding-top: 10px;" border="0" width="600" cellspacing="0" cellpadding="0" height="16">';

//============ FILE DELETING ============

$d = $_GET['d'];

if(!empty($d)){
$file_delete = str_replace('//','/',$folder.'/'.$d);
if(file_exists($file_delete)){
unlink($file_delete);
$dell = 'File "'.site_url().'/'.str_replace('../','',$file_delete).'" deleted successful.';
$dell_c = 'greeny';
}
else
{
$dell = 'File "'.site_url().'/'.str_replace('../','',$file_delete).'" not found.';
$dell_c = 'redy';
}

echo '
<tr class="viev_fw_'.$dell_c.'" id="viev_fw_filedeleted">
	<td width="20"><script>jQuery("#viev_fw_filedeleted").delay(3000).fadeOut(400);</script>
	&nbsp;</td>
	<td colspan="2" width="580"><span style="font-size: 10px;">'.$dell.'</span></td>
</tr>
';

}

//=======================================

//============ FILE UPLOADING ===========

$u = $_GET['u'];

if($u == 'true'){

if ($_FILES['viev_fw_file']['error'] > 0)
{
$dell = 'File "'.$_FILES['viev_fw_file']['name'].'" not uploaded';
$dell_c = 'redy';
}
else
{
$dell = 'File "'.$_FILES['viev_fw_file']['name'].'" uploaded successful.';
$dell_c = 'greeny';
move_uploaded_file($_FILES['viev_fw_file']['tmp_name'],str_replace('//','/',$folder.'/'.$_FILES['viev_fw_file']['name']));
}

echo '
<tr class="viev_fw_'.$dell_c.'" id="viev_fw_fileuploaded">
	<td width="20"><script>jQuery("#viev_fw_fileuploaded").delay(3000).fadeOut(400);</script>
	&nbsp;</td>
	<td colspan="2" width="580"><span style="font-size: 10px;">'.$dell.'</span></td>
</tr>
';

}

//=======================================

echo '
<tr class="viev_fm_now">
	<td width="20" align="center"> &gt; </td>
	<td colspan="2" width="580"><span style="font-size: 10px;">'.site_url().str_replace('../','',$folder).'</span></td>
</tr>
';

if($folder != '../'){
$thisfold = str_replace('../','',$folder);

$previous = explode('/',$thisfold);
$previous['size'] = sizeof($previous);
$previous['last'] = $previous[$previous['size']-1];
$previous['last'] = str_replace('/'.$previous['last'],'',$thisfold);
$previous['link'] = $_SERVER["PHP_SELF"].'?page=filewatcher&f='.( $previous['last'] == '..' ? '' : $previous['last'] );

echo '
<tr class="viev_fw_hoverer">
	<td width="20"><a class="viev_fw_urler" href="'.$previous['link'].'"><img src="'.$viev_fw_icons.'back.png" width="16" height="16"></a></td>
	<td width="480"><a class="viev_fw_urler" href="'.$previous['link'].'">.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a></td>
	<td width="100">&nbsp;</td>
</tr>
';

}
else
{
$thisfold = '';
}

viev_fw_dirget($folder,0,$thisfold,$viev_fw_icons);
viev_fw_dirget($folder,1,$thisfold,$viev_fw_icons);

echo '
<div class="viev_fw_upload" id="viev_fw_uploadform">
<form method="POST" action="'.$_SERVER["PHP_SELF"].'?page=filewatcher&u=true&f='.$thisfold.'" enctype="multipart/form-data">
<input type="file" id="viev_fw_file" name="viev_fw_file"><input type="submit">
</form>
</div>

</table>';

}

//function viev_fw_setup(){
// SETUP PAGE
//echo viev_fw_getroot(0).'<br>'.viev_fw_getroot(1);
//}

function viev_fw_activate(){

}

function viev_fw_deactivate(){

}

function viev_fw_menu(){
add_menu_page('Fileviewer','Fileviewer', 'edit_themes', 'filewatcher', 'viev_fw_manage', plugin_dir_url( __FILE__ ).'icons/folder.png');
//add_submenu_page('filewatcher','Options', 'Options', 'edit_themes', 'filewatcher_setup', 'viev_fw_setup');
}

// ACTIONS AND HOOKS ACTIVATION
add_action('admin_menu', 'viev_fw_menu');
register_activation_hook( __FILE__, 'viev_fw_activate' );
register_deactivation_hook( __FILE__, 'viev_fw_deactivate' );

?>