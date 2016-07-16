<?php
require dirname(__FILE__).'/function.php';
$dplayer = new dplayer_class();
RegisterPlugin("DPlayer","ActivePlugin_DPlayer");

function ActivePlugin_DPlayer() {
	Add_Filter_Plugin('Filter_Plugin_ViewPost_Template','DPlayer_Filter_Plugin_ViewPost_Template');
	Add_Filter_Plugin('Filter_Plugin_ViewList_Template','DPlayer_Filter_Plugin_ViewList_Template');
	Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags','DPlayer_Filter_Plugin_Zbp_MakeTemplatetags');
}

function DPlayer_Filter_Plugin_ViewPost_Template(&$template) {
    global $dplayer;
	global $zbp;
	$article = $template->GetTags('article');
	$article->Content = $dplayer->parseCallback($article->Content,$zbp->Config('DPlayer')->seturl,$zbp->Config('DPlayer')->dmserver,$zbp->Config('DPlayer')->theme);
}

function DPlayer_Filter_Plugin_ViewList_Template(&$template) {
    global $dplayer;
	global $zbp;
	$articles = $template->GetTags('articles');
	foreach($articles as $article) {
	    $article->Intro = $dplayer->parseCallback($article->Intro,$zbp->Config('DPlayer')->seturl,$zbp->Config('DPlayer')->dmserver,$zbp->Config('DPlayer')->theme);
	}
}

function DPlayer_Filter_Plugin_Zbp_MakeTemplatetags() {
    global $zbp;
    //$zbp->header .= '<link rel="stylesheet" href="'.$zbp->host.'zb_users/plugin/DPlayer/dplayer/DPlayer.min.css">'."\r\n";
    $zbp->footer .= '<script type="text/javascript" src="'.$zbp->host.'zb_users/plugin/DPlayer/DPlayer.min.js?v=1.0.2"></script>'."\r\n"."<script>function dpajaxload(){var dPlayers=[],dPlayerOptions=[];if($(\"#dpajax\").length>0){eval($(\"#dpajax\").text());var len=dPlayerOptions.length;for(var i=0;i<len;i++){dPlayers[i]=new DPlayer({element:document.getElementById('player'+dPlayerOptions[i]['id']),autoplay:dPlayerOptions[i]['autoplay'],loop:dPlayerOptions[i]['loop'],lang:dPlayerOptions[i]['lang'],video:dPlayerOptions[i]['video'],theme:dPlayerOptions[i]['theme'],danmaku:dPlayerOptions[i]['danmaku'],});dPlayers[i].init()}}}dpajaxload();</script>";
}

function InstallPlugin_DPlayer() {
	global $zbp,$obj,$bucket;
    if (!$zbp->Config('DPlayer')->HasKey('theme')) {
        $zbp->Config('DPlayer')->seturl = $zbp->host;
        $zbp->Config('DPlayer')->dmserver = '//dplayer.daoapp.io/';
		$zbp->Config('DPlayer')->theme = '#FADFA3';
		$zbp->Config('DPlayer')->hidermmenu = '0';
		$zbp->Config('DPlayer')->fixcodekey = '0';
        $zbp->SaveConfig('DPlayer');
    }
}

function UninstallPlugin_DPlayer() {
	global $zbp;
	if ($zbp->Config('DPlayer')->hidermmenu == '1') {
	    $dpjs = file_get_contents(dirname(__FILE__)."/DPlayer.min.js");
		$dpjs = str_replace('<!--<div class="dplayer-menu">','<div class="dplayer-menu">',$dpjs);
		$dpjs = str_replace('About DPlayer")+"</a></span></div>\n            </div>-->\n','About DPlayer")+"</a></span></div>\n            </div>\n',$dpjs);
		file_put_contents(dirname(__FILE__)."/DPlayer.min.js",$dpjs);
	}
	if ($zbp->Config('DPlayer')->hidermmenu !== '0') {
	    $dpjs = file_get_contents(dirname(__FILE__)."/DPlayer.min.js");
		$dpjs = str_replace('/*fixcodekey32*/','a.preventDefault(),e.toggle();',$dpjs);
		$dpjs = str_replace('/*fixcodekey37*/','a.preventDefault(),e.audio.currentTime=e.audio.currentTime-5;',$dpjs);
		$dpjs = str_replace('/*fixcodekey39*/','a.preventDefault(),e.audio.currentTime=e.audio.currentTime+5;',$dpjs);
		$dpjs = str_replace('/*fixcodekey38*/','a.preventDefault(),r=e.audio.volume+.1,r=r>0?r:0,r=r<1?r:1,e.updateBar("volume",r,"width"),e.audio.volume=r,e.audio.muted&&(e.audio.muted=!1),b();',$dpjs);
		$dpjs = str_replace('/*fixcodekey40*/','a.preventDefault(),r=e.audio.volume-.1,r=r>0?r:0,r=r<1?r:1,e.updateBar("volume",r,"width"),e.audio.volume=r,e.audio.muted&&(e.audio.muted=!1),b()',$dpjs);
		file_put_contents(dirname(__FILE__)."/DPlayer.min.js",$dpjs);
	}
	$zbp->DelConfig('DPlayer');
}