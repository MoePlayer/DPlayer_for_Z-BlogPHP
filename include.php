<?php
require dirname(__FILE__).'/function.php';
$dplayer = new DPlayer_class();
RegisterPlugin("DPlayer", "ActivePlugin_DPlayer");

function ActivePlugin_DPlayer() {
	Add_Filter_Plugin('Filter_Plugin_ViewPost_Template', 'DPlayer_Filter_Plugin_ViewPost_Template');
	Add_Filter_Plugin('Filter_Plugin_ViewList_Template', 'DPlayer_Filter_Plugin_ViewList_Template');
	Add_Filter_Plugin('Filter_Plugin_Zbp_MakeTemplatetags', 'DPlayer_Filter_Plugin_Zbp_MakeTemplatetags');
	Add_Filter_Plugin('Filter_Plugin_Edit_Response5','DPlayer_Edit_5');
	Add_Filter_Plugin('Filter_Plugin_Admin_Header','SCP_frame_src');
	Add_Filter_Plugin('Filter_Plugin_Other_Header','SCP_frame_src');
}

function DPlayer_Filter_Plugin_ViewPost_Template(&$template) {
    global $zbp;
    global $dplayer;
	$article = $template->GetTags('article');
	$article->Content = $dplayer->parseCallback($article->Content, $zbp->Config('DPlayer'));
}

function DPlayer_Filter_Plugin_ViewList_Template(&$template) {
    global $zbp;
    global $dplayer;
	$config = $zbp->Config('DPlayer');
	if ($config->parselist) {
	    $articles = $template->GetTags('articles');
	    foreach($articles as $article) $article->Intro = $dplayer->parseCallback($article->Intro, $config);
	}
}

function DPlayer_Filter_Plugin_Zbp_MakeTemplatetags() {
    global $zbp;
    if ($zbp->Config('DPlayer')->flv) $zbp->footer .= '<script type="text/javascript" src="'.$zbp->host.'zb_users/plugin/DPlayer/plugin/flv.min.js"></script>'."\n";
    if ($zbp->Config('DPlayer')->hls) $zbp->footer .= '<script type="text/javascript" src="'.$zbp->host.'zb_users/plugin/DPlayer/plugin/hls.min.js"></script>'."\n";
    $zbp->footer .=
    '<script type="text/javascript" src="'.$zbp->host.'zb_users/plugin/DPlayer/DPlayer.min.js?v=1.1.3"></script>'."\n".
    '<script>function dpajaxload(){if(0<$(\'#dpajax\').length){var DPlayerOptions=[];eval($(\'#dpajax\').text());for(i=0;i<DPlayerOptions.length;i++)new DPlayer({element:document.getElementById(\'dp\'+DPlayerOptions[i].id),autoplay:DPlayerOptions[i].autoplay,theme:DPlayerOptions[i].theme,loop:DPlayerOptions[i].loop,lang:DPlayerOptions[i].lang,screenshot:DPlayerOptions[i].screenshot,hotkey:DPlayerOptions[i].hotkey,preload:DPlayerOptions[i].preload,video:DPlayerOptions[i].video,danmaku:DPlayerOptions[i].danmaku})}}dpajaxload();</script>';
}
function SCP_frame_src(){
   echo '<meta http-equiv="Content-Security-Policy" content="child-src \'self\' https://api.menhood.wang;sandbox allow-forms allow-same-origin">'. "\r\n";
}
function DPlayer_Edit_5(){
    echo <<<EOF
    <input type="button" id="ShowButton_2" name="ShowButton_2" value="显示DP插入参数" class="button">
    <style>
    #dplayerinput{display:none;margin:5px;width:400px;height:100%;}
    #iframechild{border-radius:10px;}
    h4{display:inline}
    #shortcode{display:none;margin:2px;}
    #shortcodecopy{display:none;maigin:2px;}
    #copysuccess{display:none;}
    </style>
<div id="" style="display: flex;" >
    <div id="dplayerinput"  >
    
    <br>
    <h4>图片地址格式：</h4>http://ddns.menhood.wang/img.jpg
    <br>
    <h4>视频地址格式</h4>：http://ddns.menhood.wang/video.mp4
    <br>
    <h4>图片地址：</h4><input type="text" oninput="dplayerurls()" value=""  width=100% id="dplayerpic">
    <br>
    <h4>视频地址：</h4><input type="text" oninput="dplayerurls()" value=""  width=100% id="dplayerurl">
    <br>
    <h4>是否开启弹幕:</h4><input type="checkbox" id="danmucheck" >
    <br>
    <h4>是否开启自动播放:</h4><input type="checkbox" id="autoplaycheck" >
    <br>
    <h4>是否开启预加载:</h4><input type="checkbox" id="preloadcheck" >
    <script>
    $(function(){
        $("#ShowButton_2").click(
            function(){
                 if($("#dplayerinput").css("display")=='none'){
                    $("#dplayerinput").slideDown();
                    
                    $("#ShowButton_2").val("隐藏DP插入参数");
                 }else{
                    $("#dplayerinput").slideUp();
                    $("#ShowButton_2").val("显示DP插入参数");
                 }
            });
        });
    $(function(){
        $("#dplisgenerate").click(
            function(){
                 if($("#dplistiframe").css("display")=='none'){
                        window.open('https://api.menhood.wang/dpplaylist');
                    //$("#dplistiframe").slideDown();
                    //$("#dplisgenerate").val("隐藏列表生成页");
                    //$("#iframechild").attr("src","https://api.menhood.wang/dpplaylist/index.php");
                 }else{
                    //$("#dplistiframe").slideUp();
                   // $("#dplisgenerate").val("显示列表生成页");
                 }
            });
        });
      

	    var dplayerurl;
	    var dplayerpic;
	    var danmucheack;
	    var dpcode;

	    function dplayerurls(){
		   dplayerurl = document.getElementById("dplayerurl").value;
		   dplayerpic = document.getElementById("dplayerpic").value;
		   
	    }//文本框参数处理
	    
	    function dplayerinsert(){
	    if (document.getElementById("danmucheck").checked){
          danmucheack="true";
      }else {
        danmucheack="false";
      }//检查弹幕checkbox是否选中
          if (document.getElementById("autoplaycheck").checked){
          autoplaycheck="true";
      }else {
        autoplaycheck="false";
      }
          if (document.getElementById("preloadcheck").checked){
          preloadcheck="true";
      }else {
        preloadcheck="false";
      }
        dpcode = '[dplayer url="' + dplayerurl + '" pic="' + dplayerpic + '"preload='+'"'+ preloadcheck +'"'+'"autoplay='+'"'+ autoplaycheck +'"'+'danmu='+'"'+danmucheack+'"'+' / ]';
            if(editor_api.editor.content.obj.execCommand){
            editor_api.editor.content.obj.execCommand('inserthtml',dpcode);                
            }else{
                document.getElementById("shortcode").value=dpcode;
               document.getElementById("shortcode").style.display="inline";
               document.getElementById("shortcodecopy").style.display="inline";
               document.getElementById("dpclear").style.display="none";
            }

            /* (document.getElementById("editor_content")){
            document.getElementById("editor_content").innerHTML = dpcode;
            }*/
      }//插入单个视频代码
      
      function dpclear(){
            if(document.getElementById("carea")){
            editor_api.editor.content.obj.setContent('',false);                
            }

            if (document.getElementByName("carea-html-code")){
            document.getElementByName("carea-html-code")[0].value = '';
            }
      }//清除输入代码
      function disinfo(){document.getElementById("copysuccess").style.display="none";}
      function copycode(){
        document.getElementById("shortcode").select(); // 选择对象
        document.execCommand("Copy"); // 执行浏览器复制命令
        document.getElementById("copysuccess").style.display="inline";
        
        setTimeout("disinfo()", 3000);
        }//复制代码
</script>
    <br>
    
    <input type="button" onclick="dplayerinsert()" class="button" value="生成代码">
    <input type="button" id="dplisgenerate" name="dplisgenerate" value="生成列表" class="button">
    <input type="button" id="dpclear" onclick="dpclear()" class="button" value="清除输入" >
    <hr>
    <input type="text" id="shortcode" value="" ><input type="button"id="shortcodecopy" onclick="copycode()" class="button" value="复制" >
    <br>
    <span id="copysuccess">复制成功，请将短代码粘贴到编辑器内<span>
    </div>
    <div id="dplistiframe" style="display:none; ">
    <iframe sandbox="allow-forms allow-same-origin allow-scripts" name="iframechild" id="iframechild" src=""  border="0" frameborder="no" framespacing="0" allowfullscreen="true" width=768 height=350 > </iframe>
    </div>
 </div>    
EOF;
}

function InstallPlugin_DPlayer() {
	global $zbp,$obj,$bucket;
    if (!$zbp->Config('DPlayer')->HasKey('theme')) {
        $zbp->Config('DPlayer')->siteurl = $zbp->host;
        $zbp->Config('DPlayer')->dmserver = '//api.prprpr.me/dplayer/';
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
		$zbp->Config('DPlayer')->flv = 1;
		$zbp->Config('DPlayer')->hls = 0;
		$zbp->Config('DPlayer')->theme = '#FADFA3';
		$zbp->Config('DPlayer')->parselist = 0;
        $zbp->SaveConfig('DPlayer');
    }
}

function UninstallPlugin_DPlayer() {
	global $zbp;
	if ($zbp->Config('DPlayer')->hidermmenu == '1') {
	    $dpjs = file_get_contents(dirname(__FILE__)."/DPlayer.min.js");
		$dpjs = str_replace('<!--<div class="dplayer-menu">', '<div class="dplayer-menu">', $dpjs);
		$dpjs = str_replace('About DPlayer")+"</a></span></div>\n            </div>-->\n', 'About DPlayer")+"</a></span></div>\n            </div>\n', $dpjs);
		file_put_contents(dirname(__FILE__)."/DPlayer.min.js", $dpjs);
	}
	$zbp->DelConfig('DPlayer');
}
