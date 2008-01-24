程序名称:IXNA
程序版本:0.4 Bate 2(20071205)

运行环境:
PHP5+MySQL4.1+iconv

程序介绍:
国内最好的开源PHP新闻聚合

   1.支持RSS多核心切换,默认支持lastrss,simplepie,magpierss
   2.支持 RDF,RSS,ATOM feeds 支持智能识别
   3.基于浏览器Web端的前台浏览和后台管理,可在前台编辑,删除,锁定相关新闻
   4.支持站点和时间分类,Tags浏览,单独页面浏览,并运用了ajax技术
   5.支持搜索
   6.支持导入和导出OPML
   7.可显示favicon
   8.具有多种主题样式,现有default,bxna两套主题
   9.支持Rewrite启用
   10.生成SiteMAP0.9
   11.多语言包

安装方法:

用phpmyadmin导入install/xna.sql,修改config.php中的数据库地址,设定皮肤地址和是否启用rewrite,设cache目录为777,Win下面为网站用户可读写,后台登陆为./admin/,默认用户名为admin,密码为admin888
.htaccess为linux下Rewrite文件,httpd.ini为win下ISAPI_Rewrite配置文件

授权说明:
本程序使用通用公共授权GPL,为开源的项目,可免费使用本程序,也可二次开发,但不得用于任何商业用途或在本程序基本上开发进行商业用途,如需要,请与本人联系商业授权

PS:有兴趣或认为自己修改的不错的或是有好的意见的,都可以发信给我spvrk@spvrk.com,会更新到新的版本中去的
PS:从一个月前的PHP白痴到现在的能写一个完整的系统,人是要迫自己的

升级过程：

2007-07-05发布0.1

0.2
1.修正后台乱码
2.核心修改,可导入0.9-2.0所有版本Rss文件
3.增加搜索、单页面显示功能(点击ICO图标弹出)
4.不再生成静态xml文件,使用Rewrite生成假静态XML,方便阅读
5.重新修改数据库文件

0.3
6.采集远程ICO文件保存到本地目录,去掉RSS的语言选项,改为自动识别,更新smarty.Xajax.adodb版本,其它一些细节修改
7.修正UTF-8模板空行问题
8.多模板支持,默认下有IXNA和BXNA两套模板

0.4 Bate 1
9.修改用户登陆,分类数据库生成,支持生成SiteAMP0.9,支持是否启用rewrite
10.界面细节修改,增加同站点所有内容阅读,单新闻阅读,时间分类和多语言包,OPML的导入和导出
11.多核心切换,默认使用magpierss

0.4 Bate 2
12.修正Magpie的BUG：Fatal error: Only variables can be passed by reference in .../magpierss/rss_parse.inc on line 343,参考文件http://svn.gregarius.net/trac/attachment/ticket/175/namespace.diff,修正parse_w3cdtf()函数对blogger atom格式的bug
13.加入HMTL2WAP函数,WAP重写
14.后台管理修改,加入审核站点和新闻功能,增加链接管理,清除缓存功能
15.articles.php修改,更适合阅读
16,修改同内容,不同Feed源多次入库问题
17.修改在Linux下运行出错的一些小问题
18.Rss和Atom生成修改
19.后台登陆重写
20.放弃XAJAX,使用AJAXRequest,解决IE下没JS没下载完点击出错的问题
21.TFormValid.js文件修改,解决未下载完JS,提交不过滤问题
22.细节修改

已经知BUG
1.OPML导入有问题
2.后台TPL的语言包懒得改,太多了
3.Tags没做

目标
Gigg模式,用户登陆,整合论坛,聚合自己需要的Feed源,插件支持

官方网址:
http://www.ixna.net

下载地址:
http://zxsv.com/post/241.html

作者:剑气凌人	阳光锈了2007-07-27


ALTER TABLE `i_xna_news` AUTO_INCREMENT =2910
$search = array("a","b","c","d");
$text = preg_replace($search,"**",$text);
peter 说:
search是关键词列表，**是你想替换成的内容，比如[被过滤]
peter 说:
$text就是你想要清理的内容。
peter 说:
你可以这样
$strip_keyword="a,b,c,d";
$search=explode(",",$strip_keyword);
peter 说:

$text = preg_replace($search,"**",$text);


0904

ajax刷新分页有问题,会直接跳到首页
GetInsertSQL
GetUpdateSQL