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
	$config = array(
        "seturl" => $zbp->Config('DPlayer')->seturl,
        "dmserver" => $zbp->Config('DPlayer')->dmserver,
        "hotkey" => $zbp->Config('DPlayer')->hotkey,
        "danmaku" => $zbp->Config('DPlayer')->danmaku,
        "screenshot" => $zbp->Config('DPlayer')->screenshot,
        "loop" => $zbp->Config('DPlayer')->loop,
        "autoplay" => $zbp->Config('DPlayer')->autoplay,
        "preload" => $zbp->Config('DPlayer')->preload,
        "lang" => $zbp->Config('DPlayer')->lang,
        "maximum" => $zbp->Config('DPlayer')->maximum,
        "theme" => $zbp->Config('DPlayer')->theme
    );
	$article->Content = $dplayer->parseCallback($article->Content,$config);
}

function DPlayer_Filter_Plugin_ViewList_Template(&$template) {
    global $dplayer;
	global $zbp;
	$articles = $template->GetTags('articles');
	$config = array(
        "seturl" => $zbp->Config('DPlayer')->seturl,
        "dmserver" => $zbp->Config('DPlayer')->dmserver,
        "hotkey" => $zbp->Config('DPlayer')->hotkey,
        "danmaku" => $zbp->Config('DPlayer')->danmaku,
        "screenshot" => $zbp->Config('DPlayer')->screenshot,
        "loop" => $zbp->Config('DPlayer')->loop,
        "autoplay" => $zbp->Config('DPlayer')->autoplay,
        "preload" => $zbp->Config('DPlayer')->preload,
        "lang" => $zbp->Config('DPlayer')->lang,
        "maximum" => $zbp->Config('DPlayer')->maximum,
        "theme" => $zbp->Config('DPlayer')->theme
    );
	foreach($articles as $article) {
	    $article->Intro = $dplayer->parseCallback($article->Intro,$config);
	}
}

function DPlayer_Filter_Plugin_Zbp_MakeTemplatetags() {
    global $zbp;
    $zbp->footer .= '<script type="text/javascript" src="'.$zbp->host.'zb_users/plugin/DPlayer/DPlayer.min.js?v=1.0.5"></script>'."\r\n"."<script>function dpajaxload(){if(0<$('#dpajax').length){var DPlayerOptions=[];eval($('#dpajax').text());for(i=0;i<DPlayerOptions.length;i++)new DPlayer({element:document.getElementById('player'+DPlayerOptions[i].id),autoplay:DPlayerOptions[i].autoplay,theme:DPlayerOptions[i].theme,loop:DPlayerOptions[i].loop,lang:DPlayerOptions[i].lang,screenshot:DPlayerOptions[i].screenshot,hotkey:DPlayerOptions[i].hotkey,preload:DPlayerOptions[i].preload,video:DPlayerOptions[i].video,danmaku:DPlayerOptions[i].danmaku})}}dpajaxload();</script>";
}

function InstallPlugin_DPlayer() {
	global $zbp,$obj,$bucket;
    if (!$zbp->Config('DPlayer')->HasKey('theme')) {
        $zbp->Config('DPlayer')->seturl = $zbp->host;
        $zbp->Config('DPlayer')->dmserver = '//dplayer.daoapp.io/';
        $zbp->Config('DPlayer')->useue = 1;
		$zbp->Config('DPlayer')->hidermmenu = 0;
		$zbp->Config('DPlayer')->hotkey = 1;
		$zbp->Config('DPlayer')->danmaku = 1;
		$zbp->Config('DPlayer')->screenshot = 0;
		$zbp->Config('DPlayer')->loop = 0;
		$zbp->Config('DPlayer')->autoplay = 0;
		$zbp->Config('DPlayer')->preload = 0;
		$zbp->Config('DPlayer')->lang = 1;
		$zbp->Config('DPlayer')->maximum = 1000;
		$zbp->Config('DPlayer')->theme = '#FADFA3';
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
	$zbp->DelConfig('DPlayer');
}