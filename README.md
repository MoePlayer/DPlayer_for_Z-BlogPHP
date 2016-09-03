#[DPlayer_for_Z-BlogPHP](https://app.zblogcn.com/?id=1033)
Demo：https://www.fghrsh.net/post/57.html  
效果截图  
<img width="80%" src="https://fp1.fghrsh.net/2016/06/01/5dc96d3a7e157c883ea62e22339cee39.jpg" border="0" vspace="0"/>  
<br/>
<a style="color: rgb(0, 112, 192); text-decoration: none;" href="https://github.com/DIYgod/DPlayer" target="_blank">
    <span style="color: #0070C0;">DIYgod开发的超级好看的HTML5弹幕视频播放器，现已移植到了 Z-BlogPHP 233</span>  
</a>

##声明
部分代码参考 [https://github.com/volio/DPlayer-for-typecho](https://github.com/volio/DPlayer-for-typecho)

##使用方式
```
[dplayer url="http://xxx.com/xxx.mp4" pic="http://xxx.com/xxx.jpg" autoplay="true" danmu="false"/]
```
直接在文章插入上述标签即可，默认不自动播放，弹幕开启

###参数说明
```
url - 视频地址（必须）　　     // 填url地址
pic - 视频封面（可选）　　     // 填url地址
autoplay - 自动播放（可选）　　// true（开启） 或 false（关闭）
theme - 自定义颜色（可选） 　  // 十六进制 (hex)  例：#FADFA3
loop - 循环播放（可选）　　    // true（开启） 或 false（关闭）
lang - 界面语言（可选）　　    // 'zh'（中文） 或 'en'（英文）
danmu - 弹幕开关（可选）　　   // true（开启） 或 false（关闭）
id - 指定弹幕ID（可选）　　    // 跳过弹幕ID生成，直接绑定弹幕池ID
screenshot - 截图功能（可选）　// true  或 false，开启截图功能需源站支持 Cross-Origin
hotkey - 热键（可选）　　      // true  或 false，空格 播放/暂停，↕调节音量，↔调节进度
preload - 预加载（可选）　　   //'auto'（自动） 或 'metadata'（开启） 或 'none'（关闭）
```

## LICENSE
MIT &copy; [FGHRSH](https://www.fghrsh.net)