<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
if (!$zbp->CheckRights('root')) {$zbp->ShowError(6);exit();}
if (!$zbp->CheckPlugin('DPlayer')) {$zbp->ShowError(48);die();}
require '../../../zb_system/admin/admin_header.php';
require '../../../zb_system/admin/admin_top.php';

if(isset($_POST['siteurl'])){
    $tips = '';
	foreach($_POST as $k => $v) $$k = $v;
	
	if(empty($siteurl)){
	    $zbp->ShowHint('bad', '本站地址不允许为空！');
	} else {
		if ($siteurl != ($zbp->Config('DPlayer')->siteurl)) {
			$zbp->Config('DPlayer')->siteurl = $siteurl;
			$tips .= '本站地址设置成功;';
		}
	}
	if(empty($dmserver)){
		$zbp->Config('DPlayer')->dmserver = '';
		$tips .= '弹幕池 地址 为空，弹幕将不显示;';
	} else {
	    if ($dmserver != ($zbp->Config('DPlayer')->dmserver)) {
			$zbp->Config('DPlayer')->dmserver = $dmserver;
			$tips .= '弹幕池 地址 设置成功;';
		}
	}
	if (!$hidermmenu == ($zbp->Config('DPlayer')->hidermmenu)) {
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
	$hotkey = in_array('hotkey',$options) ? 1 : 0;
	$danmaku = in_array('danmaku',$options) ? 1 : 0;
	$screenshot = in_array('screenshot',$options) ? 1 : 0;
	$loop = in_array('loop',$options) ? 1 : 0;
	$autoplay = in_array('autoplay',$options) ? 1 : 0;
	$parselist = in_array('parselist', $options) ? 1 : 0;
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
	if ($flv != $zbp->Config('DPlayer')->flv) {
	    $zbp->Config('DPlayer')->flv = $flv;
	    $tips .= '设置已应用;';
	}
	if ($hls != $zbp->Config('DPlayer')->hls) {
	    $zbp->Config('DPlayer')->hls = $hls;
	    $tips .= '设置已应用;';
	}
	if ($parselist != $zbp->Config('DPlayer')->parselist) {
	    $zbp->Config('DPlayer')->parselist = $parselist;
	    $tips .= '设置已应用;';
	}
	$zbp->SaveConfig('DPlayer');
	
	if (!empty($tips)) {
	    $tips = explode(";",$tips);
	    for ($i=0;$i<count($tips)-1;$i++) $zbp->ShowHint('good', $tips[$i]);
	} else $zbp->ShowHint('bad', '设置未更改');
}
?>
<link rel="stylesheet" href="jcolor/jcolor.min.css" type="text/css" />
<style>table,td,th,tr,.api,tr.color1,tr.color2,tr.color3,tr.color4 { background: rgba(0,0,0,0)!important; border: 2px solid rgba(100,100,100,0.2)!important; }</style>
<script type="text/javascript" src="jcolor/jcolor.min.js"></script>
<!-- 背景图取自 pixiv，作品ID：63069891。 （https://www.pixiv.net/member_illust.php?mode=medium&illust_id=63069891） -->
<div id="divMain" style="border-radius: 3px; padding: 10px; background: white url(<?php echo $zbp->host; ?>zb_users/plugin/DPlayer/bg.png) no-repeat right bottom;">
    <div class="divHeader"><a href="https://app.zblogcn.com/?id=1033" target="_blank">DPlayer for Z-BlogPHP</a> - 插件配置</div>
	    <div id="divMain2">
	        <form id="form1" name="form1" method="post">
                <table width="90%" style='padding:0px;margin:0px;' cellspacing='0' cellpadding='0' class="tableBorder">
                    <tr>
                        <th width='20%'><p align="center">设置</p></th>
                        <th width='70%'><p align="center">选项</p></th>
                    </tr>
                    <?php 
                        $config = array(
		                    'siteurl' => $zbp->Config('DPlayer')->siteurl,
		                    'dmserver' => $zbp->Config('DPlayer')->dmserver,
		                    'useue' => $zbp->Config('DPlayer')->useue,
		                    'hidermmenu' => $zbp->Config('DPlayer')->hidermmenu,
		                    'hotkey' => $zbp->Config('DPlayer')->hotkey,
		                    'danmaku' => $zbp->Config('DPlayer')->danmaku,
		                    'screenshot' => $zbp->Config('DPlayer')->screenshot,
		                    'loop' => $zbp->Config('DPlayer')->loop,
		                    'autoplay' => $zbp->Config('DPlayer')->autoplay,
		                    'preload' => $zbp->Config('DPlayer')->preload,
		                    'lang' => $zbp->Config('DPlayer')->lang,
		                    'maximum' => $zbp->Config('DPlayer')->maximum,
		                    'flv' => $zbp->Config('DPlayer')->flv,
		                    'hls' => $zbp->Config('DPlayer')->hls,
		                    'parselist' => $zbp->Config('DPlayer')->parselist
		                );
                    ?>
                    <tr>
                        <td><b><p align="center">本站地址</p></b></td>
                        <td><p align="left"><input name="siteurl" type="text" size="100%" value="<?php echo $config['siteurl']; ?>" /></p></td>
                    </tr>
                    <tr>
                        <td><b><p align="center">弹幕池地址</p></b></td>
                        <td><p align="left"><input name="dmserver" type="text" size="100%" value="<?php echo $config['dmserver']; ?>" /></p></td>
                    </tr>
                    <tr>
                        <td><b><p align="center">附加/默认设置</p></b></td>
                        <td>
                            <p align="left"></p>
                            <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;右键菜单：&nbsp;&nbsp;
                                <input type="radio" name="hidermmenu" value="0" <?php if($config['hidermmenu']==0){echo 'checked="checked"';} ?>/>显示
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="hidermmenu" value="1" <?php if($config['hidermmenu']==1){echo 'checked="checked"';} ?>/>隐藏
                            </p>
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
                                解析列表页标签&nbsp;<input type="checkbox" name="options[]" value="parselist" <?php if($config['parselist']==1){echo 'checked="checked"';} ?>/>&nbsp;&nbsp;
                            </p>
                            <p align="left">---------------------------------------------------------</p>
                            <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;FLV 支持：&nbsp;&nbsp;
                                <input type="radio" name="flv" value="1" <?php if($config['flv']==1){echo 'checked="checked"';} ?>/>开启
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="flv" value="0" <?php if($config['flv']==0){echo 'checked="checked"';} ?>/>关闭
                            </p>
                            <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;HLS（m3u8） 支持：&nbsp;&nbsp;
                                <input type="radio" name="hls" value="1" <?php if($config['hls']==1){echo 'checked="checked"';} ?>/>开启
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="hls" value="0" <?php if($config['hls']==0){echo 'checked="checked"';} ?>/>关闭
                            </p>
                            <p align="left">---------------------------------------------------------</p>
                            <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                自定义颜色&nbsp;&nbsp;
                                <input type="text" size="6" id="color" name="theme" value="<?php echo $zbp->Config('DPlayer')->theme; ?>"/>&nbsp;&nbsp;
                                <a onclick="color_picker($('#color').val());">预览颜色</a>
                                <a class="dplayer-theme-color" style="float:left;padding:6px 10px"></a>
                            </p> 
                            <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                推荐配色&nbsp;&nbsp;
                                <a onclick="color_picker('#FADFA3');" style="background-color:#FADFA3;border:1px solid #aaa">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;
                                <a onclick="color_picker('#7addeb');" style="background-color:#7addeb;border:1px solid #aaa">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;
                                <a onclick="color_picker('#dab3db');" style="background-color:#dab3db;border:1px solid #aaa">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;
                                <a onclick="color_picker('#e69184');" style="background-color:#e69184;border:1px solid #aaa">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;
                                <a onclick="color_picker('#acec8e');" style="background-color:#acec8e;border:1px solid #aaa">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;
                                <a onclick="color_picker('#ffffff');" style="background-color:#ffffff;border:1px solid #aaa">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;
                            </p>
                            <script>
                                function color_picker(hex) {
                                    $("#color").val(hex);$("#color").css("background-color",hex);
                                    $('.dplayer-theme-color').colorpicker().destroy();
                                    $('.dplayer-theme-color').colorpicker({
                                        labels: true,
                                        color: hex,
                                        colorSpace: 'rgb',
                                        expandEvent: 'mouseenter',
                                        collapseEvent: 'mouseleave mousewheel'
                                    });
                                    $('.dplayer-theme-color').on('newcolor', function (ev, colorpicker) {
                                        $("#color").val(colorpicker.toString('rgb'));$("#color").css("background-color",colorpicker.toString('rgb'));
                                    });
                                }
                                color_picker('<?php echo $zbp->Config('DPlayer')->theme; ?>');
                            </script>
                            <p align="left">---------------------------------------------------------</p>
                            <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&diams;&nbsp;&nbsp;开启 截图 功能需 源站 支持 <a href="http://baike.baidu.com/item/CORS/16411212" target="_blank">Cross-Origin</a></p>
                            <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&loz;&nbsp;&nbsp;<a href="http://diygod.me" target="_blank">关于作者</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="https://github.com/DIYgod/DPlayer/issues" target="_blank">意见反馈</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="https://www.anotherhome.net/2648" target="_blank">关于 DPlayer 播放器</a></p>
                            <p align="left"></p>
                        </td>
                    </tr>
                </table>
                <div style="width:90%;float:inherit">
                    <div style="float:left;padding:10px 0">
                        &copy;2017 <a href="https://www.fghrsh.net" target="_blank" style="color:#333333">FGHRSH</a> - <a href="https://www.fghrsh.net/post/57.html" target="_blank" style="color:#333333">DPlayer for Z-BlogPHP V1.8</a> (DPlayer 1.1.3)
                    </div>
                    <div style="float:right;padding:5px 0;">
                        <input type="Submit" class="button" value="保存设置" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require $blogpath . 'zb_system/admin/admin_footer.php'; RunTime(); ?>