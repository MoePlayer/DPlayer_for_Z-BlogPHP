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
    $zbp->header .= '<link rel="stylesheet" href="'.$zbp->host.'zb_users/plugin/DPlayer/dplayer/DPlayer.min.css">'."\r\n".'<script>var dPlayers = [];var dPlayerOptions = [];</script>'."\r\n";
    $zbp->footer .= '<script type="text/javascript" src="'.$zbp->host.'zb_users/plugin/DPlayer/dplayer/DPlayer.min.js"></script>'."\r\n"."<script>var len = dPlayerOptions.length;for(var i=0;i<len;i++){dPlayers[i] = new DPlayer({element: document.getElementById('player' + dPlayerOptions[i]['id']),autoplay: dPlayerOptions[i]['autoplay'],video: dPlayerOptions[i]['video'],theme: dPlayerOptions[i]['theme'],danmaku: dPlayerOptions[i]['danmaku'],});dPlayers[i].init();}</script>";
}

function InstallPlugin_DPlayer() {
	global $zbp,$obj,$bucket;
    if (!$zbp->Config('DPlayer')->HasKey('theme')) {
        $zbp->Config('DPlayer')->seturl = $zbp->host;
        $zbp->Config('DPlayer')->dmserver = '//danmaku.daoapp.io/dplayer/danmaku';
		$zbp->Config('DPlayer')->theme = '#FADFA3';
        $zbp->SaveConfig('DPlayer');
    }
}

function UninstallPlugin_DPlayer() {
	global $zbp;
	$zbp->DelConfig('DPlayer');
}