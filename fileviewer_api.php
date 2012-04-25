<?
// Viev's PHP File Viewer

include('fileviewer_conf.php');

$skey = $_GET['s'];
if($fileviewer_key != $skey){ die(); }

$folder = $_GET['f'];
$d = $_GET['d'];
if(empty($folder)){ $folder = './'; }

?>
<head>
<style>
.urler {
text-decoration: none;
color: #000000;
font-size: 12px;
}
.diver {
overflow: hidden;
width: 280px;
height: 16;
}
.liner {
background: url('<?= $fileviewer_icons; ?>liner.png');
}
.hoverer:hover {
background: #cce6cd; 
}
</style>
</head>
<body>
<table border="0" width="600" cellspacing="0" cellpadding="0" height="16">
<?

if($fileviewer_delo){

if(!empty($d)){

if(file_exists($d)){
unlink($d);
$dell = 'File "'.$d.'" deleted successful.';
}
else
{
$dell = 'File "'.$d.'" not found.';
}

echo '
<tr class="hoverer">
	<td width="20">&nbsp;</td>
	<td colspan="2" width="580"><span style="font-size: 10px;">'.$dell.'</span></td>
</tr>
';

}

}

if($folder != './'){
$thisfold = './'.$folder;
$thisfold = str_replace('././','./',$thisfold);

echo '
<tr class="hoverer">
	<td width="20"><a class="urler" href="fileviewer.php?s='.$skey.'"><img src="'.$fileviewer_icons.'back.png"></a></td>
	<td '.(($fileviewer_delo) ? 'width="280"' : 'colspan="2" width="580"').'><a class="urler" href="fileviewer.php?s='.$skey.'">.</a></td>';
if($fileviewer_delo){ echo '<td width="300">&nbsp;</td>'; }

	echo '
</tr>
';

}
else
{
$thisfold = '.';



}

$dir = opendir($folder);
while($log = readdir($dir)){
$logt = explode('.',$log);
if($log != '.' AND $log != '..' AND empty($logt[1])){
echo '
<tr class="hoverer">
	<td width="20"><a class="urler" href="fileviewer.php?s='.$skey.'&f='.$thisfold.'/'.$log.'"><img src="'.$fileviewer_icons.'folder.png"></a></td>
	<td '.(($fileviewer_delo) ? 'width="280"' : 'colspan="2" width="580"').'><div class="diver"><a class="urler" href="fileviewer.php?s='.$skey.'&f='.$thisfold.'/'.$log.'">'.$log.'</a></div></td>';
if($fileviewer_delo){ echo '<td width="300">&nbsp;</td>'; }

	echo '</tr>
';
}
}
closedir;

$dir = opendir($folder);
while($log = readdir($dir)){
$logt = explode('.',$log);
if($log != '.' AND $log != '..' AND !empty($logt[1]) AND $logt[1] != 'htaccess' AND $logt[1] != 'wp-styles' AND $logt[1] != 'wp-scripts' AND $logt[1] != 'dev'  AND $logt[1] != 'wp-dependencies'){
echo '
<tr class="hoverer">
	<td width="20"><a target="blank" class="urler" href="'.$thisfold.'/'.$log.'"><img src="'.$fileviewer_icons.$logt[1].'.png"></a></td>
	<td '.(($fileviewer_delo) ? 'width="280"' : 'colspan="2" width="580"').' class="liner"><div class="diver"><a target="blank" class="urler" href="'.$thisfold.'/'.$log.'">'.$log.'</a></div></td>';
if($fileviewer_delo){ echo '<td width="300" class="liner" align="right"><a href="fileviewer.php?s='.$skey.'&f='.$thisfold.'&d='.$thisfold.'/'.$log.'"><img src="'.$fileviewer_icons.'deleter.png"></a>'; }
echo '</td>
</tr>
';
}
}
closedir;

?>
</table>
</body>