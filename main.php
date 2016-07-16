<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
if (!$zbp->CheckRights('root')) {$zbp->ShowError(6);exit();}
if (!$zbp->CheckPlugin('DPlayer')) {$zbp->ShowError(48);die();}
$blogtitle='<a href="https://app.zblogcn.com/?id=1033" target="_blank">DPlayer for Z-BlogPHP</a> - 插件配置';
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
	if (!$hidermmenu == ($zbp->Config('DPlayer')->hidermmenu)){
	    $dpjs = file_get_contents(dirname(__FILE__)."/DPlayer.min.js");
		if ($hidermmenu == '1') {
		    $dpjs = str_replace('<div class="dplayer-menu">','<!--<div class="dplayer-menu">',$dpjs);
		    $dpjs = str_replace('About DPlayer")+"</a></span></div>\n            </div>\n','About DPlayer")+"</a></span></div>\n            </div>-->\n',$dpjs);
		} else {
		    $dpjs = str_replace('<!--<div class="dplayer-menu">','<div class="dplayer-menu">',$dpjs);
		    $dpjs = str_replace('About DPlayer")+"</a></span></div>\n            </div>-->\n','About DPlayer")+"</a></span></div>\n            </div>\n',$dpjs);
		}
		file_put_contents(dirname(__FILE__)."/DPlayer.min.js",$dpjs);
		$zbp->Config('DPlayer')->hidermmenu = $hidermmenu;
		$tips .= '附加设置已应用，如有请刷新播放器js缓存以生效；';
	}
	if (!$fixcodekey == ($zbp->Config('DPlayer')->fixcodekey)){
	    $dpjs = file_get_contents(dirname(__FILE__)."/DPlayer.min.js");
		if ($fixcodekey == '1') {
		    $dpjs = str_replace('a.preventDefault(),e.toggle();','/*fixcodekey32*/',$dpjs);
		} elseif ($fixcodekey == '2') {
		    $dpjs = str_replace('a.preventDefault(),e.toggle();','/*fixcodekey32*/',$dpjs);
		    $dpjs = str_replace('a.preventDefault(),e.audio.currentTime=e.audio.currentTime-5;','/*fixcodekey37*/',$dpjs);
		    $dpjs = str_replace('a.preventDefault(),e.audio.currentTime=e.audio.currentTime+5;','/*fixcodekey39*/',$dpjs);
		    $dpjs = str_replace('a.preventDefault(),r=e.audio.volume+.1,r=r>0?r:0,r=r<1?r:1,e.updateBar("volume",r,"width"),e.audio.volume=r,e.audio.muted&&(e.audio.muted=!1),b();','/*fixcodekey38*/',$dpjs);
		    $dpjs = str_replace('a.preventDefault(),r=e.audio.volume-.1,r=r>0?r:0,r=r<1?r:1,e.updateBar("volume",r,"width"),e.audio.volume=r,e.audio.muted&&(e.audio.muted=!1),b()','/*fixcodekey40*/',$dpjs);
		} else {
		    $dpjs = str_replace('/*fixcodekey32*/','a.preventDefault(),e.toggle();',$dpjs);
		    $dpjs = str_replace('/*fixcodekey37*/','a.preventDefault(),e.audio.currentTime=e.audio.currentTime-5;',$dpjs);
		    $dpjs = str_replace('/*fixcodekey39*/','a.preventDefault(),e.audio.currentTime=e.audio.currentTime+5;',$dpjs);
		    $dpjs = str_replace('/*fixcodekey38*/','a.preventDefault(),r=e.audio.volume+.1,r=r>0?r:0,r=r<1?r:1,e.updateBar("volume",r,"width"),e.audio.volume=r,e.audio.muted&&(e.audio.muted=!1),b();',$dpjs);
		    $dpjs = str_replace('/*fixcodekey40*/','a.preventDefault(),r=e.audio.volume-.1,r=r>0?r:0,r=r<1?r:1,e.updateBar("volume",r,"width"),e.audio.volume=r,e.audio.muted&&(e.audio.muted=!1),b()',$dpjs);
		}
		file_put_contents(dirname(__FILE__)."/DPlayer.min.js",$dpjs);
		$zbp->Config('DPlayer')->fixcodekey = $fixcodekey;
		$tips .= '附加设置已应用，如有请刷新播放器js缓存以生效；';
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
<script type="text/javascript" src="farbtastic/farbtastic.js"></script>
<link rel="stylesheet" href="farbtastic/farbtastic.css" type="text/css" />
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
            <td><b><label><p align="center">弹幕服务器</p></label></b></td>
            <td><p align="left"><input name="dmserver" type="text" size="100%" value="<?php echo $zbp->Config('DPlayer')->dmserver;?>" /></p></td>
        </tr><tr>
            <td><b><label><p align="center">附加设置</p></label></b></td>
            <td><?php $useue = $zbp->Config('DPlayer')->useue;$hidermmenu = $zbp->Config('DPlayer')->hidermmenu;$fixcodekey = $zbp->Config('DPlayer')->fixcodekey; ?><p align="left">适配 UEditor：开发中</p><p align="left">---------------------------------------------------------------------</p><p align="left">去除右键菜单：<input type="radio" name="hidermmenu" value="1" <?php if ($hidermmenu == '1') echo 'checked="checked"'; ?>/>true&nbsp;&nbsp;&nbsp;<input type="radio" name="hidermmenu" value="0" <?php if ($hidermmenu == '0') echo 'checked="checked"'; ?>/>false</p><p align="left">---------------------------------------------------------------------</p><p align="left">去除全局按建检测：<input type="radio" name="fixcodekey" value="0" <?php if ($fixcodekey == '0') echo 'checked="checked"'; ?>/>不去除&nbsp;&nbsp;<input type="radio" name="fixcodekey" value="1" <?php if ($fixcodekey == '1') echo 'checked="checked"'; ?>/>仅空格&nbsp;&nbsp;<input type="radio" name="fixcodekey" value="2" <?php if ($fixcodekey == '2') echo 'checked="checked"'; ?>/>所有按键</p><p align="left">（切换其他状态前，请先设置为 “不去除” 并应用，再切换应用其他状态！）</p><p align="left">---------------------------------------------------------------------</p><p align="left">支持作者：</p><p align="left">*SATA: The Star And Thank Author License</p><p align="left">DPlayer 采用 *<a href="https://github.com/DIYgod/DPlayer/blob/master/LICENSE" target="_blank" >SATA</a> 授权协议，用前请先 <a href="https://github.com/DIYgod/DPlayer" target="_blank" >+1star</a> =-=</p></td>
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