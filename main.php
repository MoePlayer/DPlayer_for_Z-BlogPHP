<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
if (!$zbp->CheckRights('root')) {$zbp->ShowError(6);exit();}
if (!$zbp->CheckPlugin('DPlayer')) {$zbp->ShowError(48);die();}
$blogtitle="DPlayer for Z-BlogPHP - 插件配置";
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';

if(isset($_POST['seturl'])){
	foreach($_POST as $k => $v){$$k = $v;}
	if($seturl != ''){
		if ($seturl == ($zbp->Config('DPlayer')->seturl)){
			$tips = '本站地址未更改；';
		}else{
			$zbp->Config('DPlayer')->seturl = $seturl;
			$tips = '本站地址设置成功；';
		}
	}else{
		$zbp->ShowHint('bad', '设置未生效，本站地址不允许为空！');
		$tips = '';
	}
	if($dmserver != ''){
		if ($dmserver == ($zbp->Config('DPlayer')->dmserver)){
			$tips .= '弹幕服务器地址未更改；';
		}else{
			$zbp->Config('DPlayer')->dmserver = $dmserver;
			$tips .= '弹幕服务器地址设置成功；';
		}
	}else{
		$zbp->Config('DPlayer')->dmserver = '';
		$zbp->ShowHint('bad', '弹幕服务器地址为空，弹幕设置将失效');
	}
	$zbp->Config('DPlayer')->theme = $_POST['theme'];
	$zbp->SaveConfig('DPlayer');
	if(isset($tips)){$zbp->ShowHint('good', $tips);}
}
?>
<style>
input.text{background:#FFF;border:1px double #aaa;font-size:1em;padding:0.25em;}
p{line-height:1.5em;padding:0.5em 0;}
.tc{border: solid 2px #E1E1E1;width: 50px;height: 23px;float: left;margin: 0.25em;cursor: pointer}
.tc:hover,.active{border: 2px solid #2694E8;}
</style>
<script type="text/javascript" src="farbtastic.js"></script>
<link rel="stylesheet" href="farbtastic.css" type="text/css" />
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {$('#picker').farbtastic('#color');});
</script>
<div id="divMain">
    <div class="divHeader"><?php echo $blogtitle;?></div>
	<div id="divMain2">
	<form id="form1" name="form1" method="post">
    <table width="100%" style='padding:0px;margin:0px;' cellspacing='0' cellpadding='0' class="tableBorder">
        <tr>
            <th width='20%'><p align="center">设置</p></th>
            <th width='70%'><p align="center">内容</p></th>
        </tr><tr>
            <td><b><label><p align="center">本站地址</p></label></b></td>
            <td><p align="left"><input name="seturl" type="text" size="100%" value="<?php echo $zbp->Config('DPlayer')->seturl;?>" /></p></td>
        </tr><tr>
            <td><b><label><p align="center">弹幕后端服务器</p></label></b></td>
            <td><p align="left"><input name="dmserver" type="text" size="100%" value="<?php echo $zbp->Config('DPlayer')->dmserver;?>" /></p></td>
        </tr>  
</table>
<table width="100%" border="1" width="100%" class="tableBorder">
	<tr>
		<th scope="col" height="32" width="150px">颜色配置</th>
		<th scope="col" width="120px">
		<div style="float:left;margin: 0.25em">推荐颜色：</div>
		</th>
		<th>
		    <div id="loadconfig">
		    <div class="tc" onclick='$("#color").val("#FADFA3");$("#color").css("background-color","#FADFA3");' style="background-color:#FADFA3"></div>
			<div class="tc" onclick='$("#color").val("#7addeb");$("#color").css("background-color","#7addeb");' style="background-color:#7addeb"></div>
			<div class="tc" onclick='$("#color").val("#dab3db");$("#color").css("background-color","#dab3db");' style="background-color:#dab3db"></div>
			<div class="tc" onclick='$("#color").val("#e69184");$("#color").css("background-color","#e69184");' style="background-color:#e69184"></div>
			<div class="tc" onclick='$("#color").val("#acec8e");$("#color").css("background-color","#acec8e");' style="background-color:#acec8e"></div>
			<div class="tc" onclick='$("#color").val("#ffffff");$("#color").css("background-color","#ffffff");' style="background-color:#ffffff"></div>
			</div>
		</th>
	</tr>
	<tr>
		<td>播放器色调</td>
		<td><input type="text" id="color" name="theme" value="<?php echo $zbp->Config('DPlayer')->theme;?>"/></div></td>
		<td><div id="picker"></div></td>
	</tr>
</table>
<br />
<input name="" type="Submit" class="button" value="保存"/>
</form>
</div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>