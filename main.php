<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
if (!$zbp->CheckRights('root')) {$zbp->ShowError(6);exit();}
if (!$zbp->CheckPlugin('DPlayer')) {$zbp->ShowError(48);die();}
require '../../../zb_system/admin/admin_header.php';
require '../../../zb_system/admin/admin_top.php';

if(isset($_POST['seturl'])){
	foreach($_POST as $k => $v) $$k = $v;
	
	if(empty($seturl)){
	    $zbp->ShowHint('bad', '本站地址不允许为空！');
	}else{
		if (!$seturl == ($zbp->Config('DPlayer')->seturl)){
			$zbp->Config('DPlayer')->seturl = $seturl;
			$tips = '本站地址设置成功；';
		}
	}
	if(empty($dmserver)){
		$zbp->Config('DPlayer')->dmserver = '';
		$tips .= '弹幕服务器地址为空，弹幕设置将失效;';
	}else{
	    if (!$dmserver == ($zbp->Config('DPlayer')->dmserver)){
			$zbp->Config('DPlayer')->dmserver = $dmserver;
			$tips .= '弹幕服务器地址设置成功;';
		}
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
		$tips .= '附加设置已应用，刷新播放器js缓存后生效;';
	}
	if (in_array('hotkey',$options)) $hotkey = 1; else $hotkey = 0;
	if (in_array('danmaku',$options)) $danmaku = 1; else $danmaku = 0;
	if (in_array('screenshot',$options)) $screenshot = 1; else $screenshot = 0;
	if (in_array('loop',$options)) $loop = 1; else $loop = 0;
	if (in_array('autoplay',$options)) $autoplay = 1; else $autoplay = 0;
	if ($hotkey != $zbp->Config('DPlayer')->hotkey) {
	    $zbp->Config('DPlayer')->hotkey = $hotkey;
	    $tips .= '设置已应用;';
	}
	if ($danmaku != $zbp->Config('DPlayer')->danmaku) {
	    $zbp->Config('DPlayer')->danmaku = $danmaku;
	    $tips .= '设置已应用;';
	}
	if ($screenshot != $zbp->Config('DPlayer')->screenshot) {
	    $zbp->Config('DPlayer')->screenshot = $screenshot;
	    $tips .= '设置已应用;';
	}
	if ($loop != $zbp->Config('DPlayer')->loop) {
	    $zbp->Config('DPlayer')->loop = $loop;
	    $tips .= '设置已应用;';
	}
	if ($autoplay != $zbp->Config('DPlayer')->autoplay) {
	    $zbp->Config('DPlayer')->autoplay = $autoplay;
	    $tips .= '设置已应用;';
	}
	if ($preload != $zbp->Config('DPlayer')->preload) {
	    $zbp->Config('DPlayer')->preload = $preload;
	    $tips .= '设置已应用;';
	}
	if ($lang != $zbp->Config('DPlayer')->lang) {
	    $zbp->Config('DPlayer')->lang = $lang;
	    $tips .= '设置已应用;';
	}
	if (empty($maximum)) {
	    $zbp->Config('DPlayer')->dmserver = 0;
	    $tips .= '设置已应用;';
	} else {
	    if ($maximum != $zbp->Config('DPlayer')->maximum) {
	        $zbp->Config('DPlayer')->maximum = $maximum;
	        $tips .= '设置已应用;';
	    }
	}
	if ($theme != $zbp->Config('DPlayer')->theme) {
	    $zbp->Config('DPlayer')->theme = $theme;
	    $tips .= '设置已应用;';
	}
	$zbp->SaveConfig('DPlayer');
	
	if ( isset($tips) ) {
	    $tips = explode(";",$tips);
	    for ($i=0;$i<count($tips)-1;$i++) {
	        $zbp->ShowHint('good', $tips[$i]);
	    }
	} else {
	    $zbp->ShowHint('bad', '设置未更改');
	}
}
?>
<style>input.text{background:#FFF;border:1px double #aaa;font-size:1em;padding:.25em}p{line-height:1.5em;padding:.5em 0}.tc{border:solid 2px #E1E1E1;width:50px;height:23px;float:left;margin:.25em;cursor:pointer}.active,.tc:hover{border:2px solid #2694E8}</style>
<script type="text/javascript" src="farbtastic/farbtastic.js"></script>
<link rel="stylesheet" href="farbtastic/farbtastic.css" type="text/css" />
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {$('#picker').farbtastic('#color');});
</script>
<div id="divMain">
    <div class="divHeader"><a href="https://app.zblogcn.com/?id=1033" target="_blank">DPlayer for Z-BlogPHP</a> - 插件配置</div>
	    <div id="divMain2">
	        <form id="form1" name="form1" method="post">
                <table width="60%" style='padding:0px;margin:0px;' cellspacing='0' cellpadding='0' class="tableBorder">
                    <tr>
                        <th width='20%'><p align="center">设置</p></th>
                        <th width='70%'><p align="center">选项</p></th>
                    </tr>
                    <?php 
                        $config = array(
		                    "seturl" => $zbp->Config('DPlayer')->seturl,
		                    "dmserver" => $zbp->Config('DPlayer')->dmserver,
		                    "useue" => $zbp->Config('DPlayer')->useue,
		                    "hidermmenu" => $zbp->Config('DPlayer')->hidermmenu,
		                    "hotkey" => $zbp->Config('DPlayer')->hotkey,
		                    "danmaku" => $zbp->Config('DPlayer')->danmaku,
		                    "screenshot" => $zbp->Config('DPlayer')->screenshot,
		                    "loop" => $zbp->Config('DPlayer')->loop,
		                    "autoplay" => $zbp->Config('DPlayer')->autoplay,
		                    "preload" => $zbp->Config('DPlayer')->preload,
		                    "lang" => $zbp->Config('DPlayer')->lang,
		                    "maximum" => $zbp->Config('DPlayer')->maximum
		                );
                    ?>
                    <tr>
                        <td><b><p align="center">本站地址</p></b></td>
                        <td><p align="left"><input name="seturl" type="text" size="100%" value="<?php echo $config['seturl']; ?>" /></p></td>
                    </tr>
                    <tr>
                        <td><b><p align="center">弹幕服务器</p></b></td>
                        <td><p align="left"><input name="dmserver" type="text" size="100%" value="<?php echo $config['dmserver']; ?>" /></p></td>
                    </tr>
                    <tr>
                        <td><b><p align="center">附加/默认设置</p></b></td>
                        <td>
                            <p align="left"></p>
                            <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;适配 UEditor：&nbsp;&nbsp;敬请期待</p>
                            <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;右键菜单：&nbsp;&nbsp;
                                <input type="radio" name="hidermmenu" value="0" <?php if($config['hidermmenu']==0){echo 'checked="checked"';} ?>/>显示
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="hidermmenu" value="1" <?php if($config['hidermmenu']==1){echo 'checked="checked"';} ?>/>隐藏
                            </p>
                            <p align="left">---------------------------------------------------------</p>
                            <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;预加载：&nbsp;&nbsp;
                                <input type="radio" name="preload" value="0" <?php if($config['preload']==0){echo 'checked="checked"';} ?>/>自动
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="preload" value="1" <?php if($config['preload']==1){echo 'checked="checked"';} ?>/>开启
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="preload" value="2" <?php if($config['preload']==2){echo 'checked="checked"';} ?>/>关闭
                            </p>
                            <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;语言：&nbsp;&nbsp;
                                <input type="radio" name="lang" value="0" disabled="disabled" <?php if($config['lang']==0){echo 'checked="checked"';} ?>/>自动
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="lang" value="1" <?php if($config['lang']==1){echo 'checked="checked"';} ?>/>中文
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="lang" value="2" <?php if($config['lang']==2){echo 'checked="checked"';} ?>/>English
                            </p>
                            <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;最大弹幕数量：&nbsp;&nbsp;
                                <input name="maximum" type="text" value="<?php echo $config['maximum']; ?>" />
                            </p>
                            <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                热键<input type="checkbox" name="options[]" value="hotkey" <?php if($config['hotkey']==1){echo 'checked="checked"';} ?>/>&nbsp;&nbsp;
                                弹幕<input type="checkbox" name="options[]" value="danmaku" <?php if($config['danmaku']==1){echo 'checked="checked"';} ?>/>&nbsp;&nbsp;
                                截图<input type="checkbox" name="options[]" value="screenshot" <?php if($config['screenshot']==1){echo 'checked="checked"';} ?>/>&nbsp;&nbsp;
                                循环播放<input type="checkbox" name="options[]" value="loop" <?php if($config['loop']==1){echo 'checked="checked"';} ?>/>&nbsp;&nbsp;
                                自动播放<input type="checkbox" name="options[]" value="autoplay" <?php if($config['autoplay']==1){echo 'checked="checked"';} ?>/>&nbsp;&nbsp;
                            </p>
                            <p align="left">---------------------------------------------------------</p>
                            <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&diams;&nbsp;开启截图功能需源站支持 Cross-Origin</p>
                            <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&loz;&nbsp;<a href="http://diygod.me" target="_blank">关于作者</a>&nbsp;|&nbsp;<a href="https://github.com/DIYgod/DPlayer/issues" target="_blank">意见反馈</a>&nbsp;|&nbsp;<a href="https://www.anotherhome.net/2648" target="_blank">关于 DPlayer 播放器</a></p>
                            <p align="left"></p>
                        </td>
                    </tr>
                </table>
                <table width="60%" border="1" class="tableBorder">
	                <tr>
		                <th scope="col" height="32" width="150px"><p align="center">选择配色</p></th>
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
		                <td>自定义颜色<input type="text" id="color" name="theme" value="<?php echo $zbp->Config('DPlayer')->theme; ?>"/></td>
		                <td><div id="picker"></div><input type="Submit" class="button" value="保存" style="float:right" /></td>
	                </tr>
                </table>
                &copy;2016 <a href="https://www.fghrsh.net" target="_blank" style="color:#333333">FGHRSH</a> - <a href="https://www.fghrsh.net/post/57.html" target="_blank" style="color:#333333">DPlayer for Z-BlogPHP V1.6</a> (DPlayer 1.0.8)
            </form>
        </div>
    </div>
</div>

<?php require $blogpath . 'zb_system/admin/admin_footer.php'; RunTime(); ?>