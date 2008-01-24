<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" encoding="utf-8" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>
<xsl:template match="/">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>XML/XSL实验室 - <xsl:value-of select="planabc/channel/title"/></title>
	<link href="feed.css" rel="stylesheet" type="text/css" media="all"/>
        <script src="xsl/cdata.js" type="text/javascript"></script>
</head>

<body class="Lab">
	<div id="header">
		<span><a href="{link}" title=""><xsl:value-of select="planabc/channel/title"/></a></span>
                <a href="#content" accesskey="c" title="快捷键：ALT + C" class="skipto">{ Skip to Content }</a>
	</div>

	<div id="content">
                <h1 id="logo"><a href="{link}" title="">PlanABC</a></h1>
                <p class="slogan"><xsl:value-of select="planabc/channel/content"/></p>

                <xsl:for-each select="planabc/channel/item">

		<div class="post">

                  <h2 class="posttitle"><a href="{link}" rel="bookmark" title="{title}"><xsl:value-of select="title"/></a> <span><xsl:value-of select="commentnews_count"/></span></h2>

                  <p class="postmeta"><span class="postat"><span class="ym"><xsl:value-of select="pubDate/year"/></span><span class="d"><xsl:value-of select="pubDate/day"/></span></span> <a href="{categoryLink}" title="{category}" rel="category tag"><xsl:value-of select="category"/></a></p>

                  <div class="postentry">
		      <xsl:value-of disable-output-escaping="yes" select="content"/>
		  </div>

                  <p class="continue"><a href="{link}">感兴趣？继续阅读 ?</a></p>

                 </div>

		</xsl:for-each>
	</div>

	<div id="footer">
		<p><xsl:value-of select="planabc/channel/copyright"/> &#183; <a href="http://www.planabc.net" title="本站基于XML+ XSL构建">XML+ XSL</a>
</p>
	</div>
</body>
</html>

</xsl:template>
</xsl:stylesheet>