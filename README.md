# [DPlayer_for_Z-BlogPHP](https://www.fghrsh.net/post/57.html)
[DPlayer](https://github.com/DIYgod/DPlayer) for Z-BlogPHP

## 声明
代码和用法参考了 [https://github.com/volio/DPlayer-for-typecho](https://github.com/volio/DPlayer-for-typecho)

##使用方式
下载后将文件夹名改为DPlayer上传启用即可

默认不自动播放，弹幕开启
```
[dplayer url="http://xxx.com/xxx.mp4" pic="http://xxx.com/xxx.jpg" autoplay="true" danmu="false"/]
```

### V1.4 更新
更新 DPlayer 至最新版本  
新增 去除右键菜单  
新增 去除全局按建检测 设置  
新版 DPlayer 更新了 [SATA](https://github.com/DIYgod/DPlayer/blob/master/LICENSE "The Star And Thank Author License") 授权协议，用前请先 [+1star](https://github.com/DIYgod/DPlayer "DPlayer") =-= (支持作者)

### V1.2 更新
已实现Ajax/Pjax模板支持，仅需在 加载完成时执行 里加入
```
dpajaxload();
```
即可无刷新加载 DPlayer 播放器（效果见我博客，全站pjax）

###参数说明
url - 视频地址（必须）  
pic - 视频封面（可选）  
danmu - 弹幕（可选，默认开）  
loop - 循环播放（可选，默认关）  
autoplay - 自动播放（可选，默认关）  
theme - 自定义颜色（可选，默认为全局设置）

###后台配置说明
本站地址 - 用于生成唯一视频ID  
去除右键菜单 - 隐藏播放器右键菜单  
弹幕后端服务器 - 用于指定弹幕服务器地址  
去除全局按建检测 - 解决评论 输入空格 导致 播放器暂停/播放 问题  
播放器色调 - 指定全局播放器颜色主题（颜色生效在哪？你看看进度条..）  
Tips：想多个站显示同一弹幕，设置“本站地址”为同一地址即可（视频地址需相同）

## LICENSE
MIT © [FGHRSH](https://www.fghrsh.net)