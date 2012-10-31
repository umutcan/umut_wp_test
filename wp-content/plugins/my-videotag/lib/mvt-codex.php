<?php
function get_video($content,$w,$h){
global $mvt;
$values = array (

//youtube.com
array('/youtube\.com.*v=([^&]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe width="'.$w.'" height="'.$h.'" src="http://www.youtube.com/embed/{ID_VIDEO}" frameborder="0" allowfullscreen></iframe></div>'),
array('/youtu\.be\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe width="'.$w.'" height="'.$h.'" src="http://www.youtube.com/embed/{ID_VIDEO}" frameborder="0" allowfullscreen></iframe></div>'),
//youtube.com playlist
array('/youtube\.com\/playlist\?list=([^&]*)/i', '<div class="myvideotag" style="width:'.$w.'px;"><iframe width="'.$w.'" height="'.$h.'" src="http://www.youtube.com/p/{ID_VIDEO}?version=3&amp;hl=es_ES" frameborder="0" allowfullscreen></iframe></div>'),

//video.google.com
array('/video.google.*docid=([^&]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><embed id="VideoPlayback" style="width:'.$w.'px;height:'.$h.'px" allowFullScreen="true" flashvars="fs=true" wmode="transparent" src="http://video.google.com/googleplayer.swf?docid={ID_VIDEO}" type="application/x-shockwave-flash"></embed></div>'),

//dailymotion.com
array('/dailymotion\.com\/video\/([^_]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe frameborder="0" width="'.$w.'" height="'.$h.'" src="http://www.dailymotion.com/embed/video/{ID_VIDEO}"></iframe></div>'),

//metacafe.com
array('/metacafe\.com\/watch\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><div style="background:#000000;width:'.$w.'px;height:'.$h.'px"><embed flashVars="playerVars=showStats=yes|autoPlay=no" src="http://www.metacafe.com/fplayer/{ID_VIDEO}.swf" width="'.$w.'" height="'.$h.'" wmode="transparent" allowFullScreen="true" allowScriptAccess="always" name="Metacafe_5384657" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed></div></div>'),

//Myspace
//myspace.com
array('/myspace\.com.*?videoID=([^&]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'" ><param name="allowFullScreen" value="true"/><param name="wmode" value="transparent"/><param name="movie" value="http://mediaservices.myspace.com/services/media/embed.aspx/m={ID_VIDEO},t=1,mt=video"/><embed src="http://mediaservices.myspace.com/services/media/embed.aspx/m={ID_VIDEO},t=1,mt=video" width="'.$w.'" height="'.$h.'" allowFullScreen="true" type="application/x-shockwave-flash" wmode="transparent"></embed></object></div>'),
//vids.myspace.com
array('/vids\.myspace\.com.*?videoID=([^&]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'" ><param name="allowFullScreen" value="true"/><param name="wmode" value="transparent"/><param name="movie" value="http://mediaservices.myspace.com/services/media/embed.aspx/m={ID_VIDEO},t=1,mt=video"/><embed src="http://mediaservices.myspace.com/services/media/embed.aspx/m={ID_VIDEO},t=1,mt=video" width="'.$w.'" height="'.$h.'" allowFullScreen="true" type="application/x-shockwave-flash" wmode="transparent"></embed></object></div>'),
//myspacetv.com
array('/myspacetv\.com.*?videoID=([^&]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'"><param name="wmode" value="transparent"/><param name="allowscriptaccess" value="always"/><param name="movie" value="http://lads.myspace.com/videos/vplayer.swf"/><param name="flashvars" value="m={ID_VIDEO}"/><embed src="http://lads.myspace.com/videos/vplayer.swf" width="'.$w.'" height="'.$h.'" flashvars="m={ID_VIDEO}" wmode="transparent" type="application/x-shockwave-flash" allowscriptaccess="always" /></object></div>'),

array('/myspace\.com.\/video\/([^\/]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'" ><param name="allowFullScreen" value="true"/><param name="wmode" value="transparent"/><param name="movie" value="http://mediaservices.myspace.com/services/media/embed.aspx/m={ID_VIDEO},t=1,mt=video"/><embed src="http://mediaservices.myspace.com/services/media/embed.aspx/m={ID_VIDEO},t=1,mt=video" width="'.$w.'" height="'.$h.'" allowFullScreen="true" type="application/x-shockwave-flash" wmode="transparent"></embed></object></div>'),

//video.yahoo.com
array('/video\.yahoo\.com.*v=([^&]*)/i','<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'"><param name="wmode" value="transparent"></param><param name="movie" value="http://d.yimg.com/static.video.yahoo.com/yep/YV_YEP.swf?ver=2.2.2" /><param name="allowFullScreen" value="true" /><param name="flashVars" value="id={DOWNLOAD%/so\.addVariable\("id", "(.*?)"\);/%}&vid={ID_VIDEO}&thumbUrl={DOWNLOAD%/so\.addVariable\("thumbUrl", "(.*?)"\);/%}&embed=1" /><embed src="http://d.yimg.com/static.video.yahoo.com/yep/YV_YEP.swf?ver=2.2.2" type="application/x-shockwave-flash" width="'.$w.'" height="'.$h.'" wmode="transparent" allowFullScreen="true" flashVars="id={DOWNLOAD%/so\.addVariable\("id", "(.*?)"\);/%}&vid={ID_VIDEO}&thumbUrl={DOWNLOAD%/so\.addVariable\("thumbUrl", "(.*?)"\);/%}&embed=1" ></embed></object></div>'),
//video.yahoo.com/watch/
array('/video\.yahoo\.com\/watch\/([^\/]*)/i','<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'"><param name="wmode" value="transparent"></param><param name="movie" value="http://d.yimg.com/static.video.yahoo.com/yep/YV_YEP.swf?ver=2.2.2" /><param name="allowFullScreen" value="true" /><param name="flashVars" value="id={DOWNLOAD%/so\.addVariable\("id", "(.*?)"\);/%}&vid={ID_VIDEO}&thumbUrl={DOWNLOAD%/so\.addVariable\("thumbUrl", "(.*?)"\);/%}&embed=1" /><embed src="http://d.yimg.com/static.video.yahoo.com/yep/YV_YEP.swf?ver=2.2.2" type="application/x-shockwave-flash" width="'.$w.'" height="'.$h.'" wmode="transparent" allowFullScreen="true" flashVars="id={DOWNLOAD%/so\.addVariable\("id", "(.*?)"\);/%}&vid={ID_VIDEO}&thumbUrl={DOWNLOAD%/so\.addVariable\("thumbUrl", "(.*?)"\);/%}&embed=1" ></embed></object></div>'),
//video.yahoo new
array ('/video\.yahoo\.com.*-(.*).html/i', '<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'"><param name="movie" value="http://d.yimg.com/nl/vyc/site/player.swf?lang=en-US"></param><param name="flashVars" value="browseCarouselUI=hide&repeat=0&vid={ID_VIDEO}&startScreenCarouselUI=hide&lang=en-US&"></param><param name="allowfullscreen" value="true"></param><param name="wmode" value="transparent"></param><embed width="'.$w.'" height="'.$h.'" allowFullScreen="true" src="http://d.yimg.com/nl/vyc/site/player.swf?lang=en-US" type="application/x-shockwave-flash" flashvars="browseCarouselUI=hide&repeat=0&vid={ID_VIDEO}&startScreenCarouselUI=hide&lang=en-US"></embed></object></div>'),

/*
//megavideo.com 
array ('/megavideo\.com.*v=(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'"><param name="wmode" value="transparent"></param><param name="movie" value="http://www.megavideo.com/v/{ID_VIDEO}"></param><param name="allowFullScreen" value="true"></param><embed src="http://www.megavideo.com/v/{ID_VIDEO}" wmode="transparent" type="application/x-shockwave-flash" allowfullscreen="true" width="'.$w.'" height="'.$h.'"></embed></object></div>'),
*/

//vimeo.com
array ('/vimeo\.com\/([^&]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe src="http://player.vimeo.com/video/{ID_VIDEO}?title=0&amp;byline=0&amp;portrait=0" width="'.$w.'" height="'.$h.'" frameborder="0"></iframe></div>'),

//gamevideos.1up.com
array ('/gamevideos\.1up\.com\/video\/id\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><div style="width:'.$w.'px; text-align:center"><embed type="application/x-shockwave-flash" width="'.$w.'" height="'.$h.'" src="http://gamevideos.1up.com/swf/gamevideos12.swf?embedded=1&amp;fullscreen=1&amp;autoplay=0&amp;src=http://gamevideos.1up.com/do/videoListXML%3Fid%3D{ID_VIDEO}%26adPlay%3Dtrue" wmode="transparent" align="middle"></embed></div></div>'),

//tu.tv
array ('/(tu\.tv)/i', '{DOWNLOAD%/<input name="html".*value=\'(.*?)\'/%}'),

//godtube.com
array ('/godtube\.com.*v=(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'" type="application/x-shockwave-flash" data="http://www.godtube.com/resource/mediaplayer/5.3/player.swf"><param name="movie" value="http://www.godtube.com/resource/mediaplayer/5.3/player.swf"><param name="allowfullscreen" value="true"><param name="allowscriptaccess" value="always"><param name="wmode" value="opaque"><param name="flashvars" value="file=http://www.godtube.com/resource/mediaplayer/{ID_VIDEO}.file&image=http://www.godtube.com/resource/mediaplayer/{ID_VIDEO}.jpg&screencolor=000000&type=video&autostart=false&playonce=true&skin=http://www.godtube.com//resource/mediaplayer/skin/carbon/carbon.zip&logo.file=http://media.salemwebnetwork.com/godtube/theme/default/media/embed-logo.png&logo.link=http://www.godtube.com/watch/%3Fv%3D{ID_VIDEO}&logo.position=top-left&logo.hide=false&controlbar.position=over"></object></div>'),

//myvideo.de
array ('/myvideo\.de\/watch\/(.*)\//i', '<div class="myvideotag" style="width: '.$w.'px;"><object style="width:'.$w.'px;height:'.$h.'px;" width="'.$w.'" height="'.$h.'" data="http://www.myvideo.de/movie/{ID_VIDEO}"><param name="wmode" value="transparent"></param><embed src="http://www.myvideo.de/movie/{ID_VIDEO}" width="'.$w.'" height="'.$h.'" type="application/x-shockwave-flash" allowscriptaccess="always" wmode="transparent" allowfullscreen="true"></embed><param name="movie" value="http://www.myvideo.de/movie/{ID_VIDEO}"/><param name="AllowFullscreen" value="true"></param><param name="AllowScriptAccess" value="always"></param></object></div>'),

//collegehumor.com/video:*
array ('/collegehumor\.com\/video:(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object type="application/x-shockwave-flash" data="http://www.collegehumor.com/moogaloop/moogaloop.swf?clip_id={ID_VIDEO}&fullscreen=1" width="'.$w.'" height="'.$h.'" ><param name="wmode" wmode="transparent" value="transparent"></param><param name="allowfullscreen" value="true" /><param name="movie" quality="best" value="http://www.collegehumor.com/moogaloop/moogaloop.swf?clip_id={ID_VIDEO}&fullscreen=1" /></object></div>'),
//collegehumor.com/video/*/tittleofvideo
array ('/collegehumor\.com\/video\/(.*)\/*/i', '<div class="myvideotag" style="width: '.$w.'px;"><object id="ch6478387" type="application/x-shockwave-flash" data="http://www.collegehumor.com/moogaloop/moogaloop.swf?clip_id={ID_VIDEO}&use_node_id=true&fullscreen=1" width="'.$w.'" height="'.$h.'"><param name="allowfullscreen" value="true"/><param name="wmode" value="transparent"/><param name="allowScriptAccess" value="always"/><param name="movie" quality="best" value="http://www.collegehumor.com/moogaloop/moogaloop.swf?clip_id={ID_VIDEO}&use_node_id=true&fullscreen=1"/><embed src="http://www.collegehumor.com/moogaloop/moogaloop.swf?clip_id={ID_VIDEO}&use_node_id=true&fullscreen=1" type="application/x-shockwave-flash" wmode="transparent" width="'.$w.'" height="'.$h.'" allowScriptAccess="always"></embed></object></div>'),

//comedycentral.com
array ('/comedycentral\.com.*videoId=([^&]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><embed style="display:block" src="http://media.mtvnservices.com/mgid:cms:item:comedycentral.com:{ID_VIDEO}" width="'.$w.'" height="'.$h.'" type="application/x-shockwave-flash" wmode="window" allowFullscreen="true" flashvars="autoPlay=false" allowscriptaccess="always" allownetworking="all" bgcolor="#000000"></embed></div>'),

//revver.com
array ('/revver\.com\/video\/([^\/]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><script src="http://flash.revver.com/player/1.0/player.js?mediaId:{ID_VIDEO};width:'.$w.';height:'.$h.';" type="text/javascript"></script></div>'),

//clipfish.de
array ('/clipfish\.de\/video\/([^\/]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="'.$w.'" height="'.$h.'" ><param name="wmode" value="transparent"></param><param name="allowScriptAccess" value="always" /><param name="movie" value="http://www.clipfish.de/cfng/flash/clipfish_player_3.swf?as=0&vid={ID_VIDEO}&r=1&area=e&c=990000" /> <param name="bgcolor" value="#ffffff" /> <param name="allowFullScreen" value="true" /> <embed src="http://www.clipfish.de/cfng/flash/clipfish_player_3.swf?as=0&vid={ID_VIDEO}&r=1&area=e&c=990000" quality="high" bgcolor="#990000" width="'.$w.'" height="'.$h.'" name="player" align="middle" allowFullScreen="true" allowScriptAccess="always" type="application/x-shockwave-flash" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed></object></div>'),

//aniboom.com
array ('/aniboom\.com\/animation-video\/([^\/]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'"><param name="wmode" value="transparent"></param><param name="movie" value="http://api.aniboom.com/e/{ID_VIDEO}" /><param name="allowScriptAccess" value="sameDomain" /><param name="allowfullscreen" value="true" /><param name="quality" value="high" /><embed src="http://api.aniboom.com/e/{ID_VIDEO}" quality="high"  width="'.$w.'"  height="'.$h.'" wmode="transparent" allowscriptaccess="sameDomain" allowfullscreen="true" type="application/x-shockwave-flash"></embed></object></div>'),

//facebook.com
array ('/facebook\.com.*v=([^&]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'" ><param name="allowfullscreen" value="true" /><param name="movie" value="http://www.facebook.com/v/{ID_VIDEO}" /><embed src="http://www.facebook.com/v/{ID_VIDEO}" type="application/x-shockwave-flash" allowfullscreen="true" width="'.$w.'" height="'.$h.'"></embed></object></div>'),

//funnyordie.com
array ('/funnyordie\.com\/videos\/(.*)\//i', '<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" id="ordie_player_ef76e75bcf"><param name="wmode" value="transparent"></param><param name="movie" value="http://player.ordienetworks.com/flash/fodplayer.swf" /><param name="flashvars" value="key={ID_VIDEO}" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always"></param><embed width="'.$w.'" height="'.$h.'" flashvars="key={ID_VIDEO}" wmode="transparent" allowfullscreen="true" allowscriptaccess="always" quality="high" src="http://player.ordienetworks.com/flash/fodplayer.swf" name="ordie_player_{ID_VIDEO}" type="application/x-shockwave-flash"></embed></object></div>'),

//dotsub.com
array ('/dotsub\.com\/view\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'" type="application/x-shockwave-flash" id="mpl" name="mpl" data="http://dotsub.com/static/players/portalplayer.swf?v=2886"><param name="wmode" value="transparent"></param><param name="swliveconnect" value="true"><param name="allowFullScreen" value="true"><param name="allowScriptAccess" value="always"><param name="flashvars" value="uuid={ID_VIDEO}&amp;lang=spa&amp;type=flv&amp;plugins=dotsub&amp;tid=UA-3684979-1&amp;debug=none&amp;embedded=false"></object></div>'),

//dorkly.com
array ('/dorkly\.com\/video\/(.*)\//i', '<div class="myvideotag" style="width: '.$w.'px;"><object type="application/x-shockwave-flash" data="http://www.dorkly.com/moogaloop/noobtube.swf?clip_id={ID_VIDEO}&fullscreen=1" width="'.$w.'" height="'.$h.'"><param name="allowfullscreen" value="true"/><param name="wmode" value="transparent"/><param name="allowScriptAccess" value="always"/><param name="movie" quality="best" value="http://www.dorkly.com/moogaloop/noobtube.swf?clip_id={ID_VIDEO}&fullscreen=1"/><embed src="http://www.dorkly.com/moogaloop/noobtube.swf?clip_id={ID_VIDEO}&fullscreen=1" type="application/x-shockwave-flash" wmode="transparent" width="'.$w.'" height="'.$h.'" allowScriptAccess="always"></embed></object></div>'),

//scribd.com
array ('/cribd\.com\/doc\/([^\/]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe class="scribd_iframe_embed" src="http://www.scribd.com/embeds/{ID_VIDEO}/content?start_page=1&view_mode=list&access_key=key-1evz4wg4vd1fxbt8udvc" data-auto-height="true" data-aspect-ratio="0.707514450867052" scrolling="no" id="doc_49419" width="'.$w.'" height="'.$h.'" frameborder="0"></iframe><script type="text/javascript">(function() { var scribd = document.createElement("script"); scribd.type = "text/javascript"; scribd.async = true; scribd.src = "http://www.scribd.com/javascripts/embed_code/inject.js"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(scribd, s); })();</script></div>'),

//justin.tv
array ('/justin\.tv\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object type="application/x-shockwave-flash" height="'.$h.'" width="'.$w.'" id="live_embed_player_flash" data="http://www.justin.tv/widgets/live_embed_player.swf?channel={ID_VIDEO}" bgcolor="#000000"><param name="wmode" value="transparent"></param><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="all" /><param name="movie" value="http://www.justin.tv/widgets/live_embed_player.swf" /><param name="flashvars" value="channel={ID_VIDEO}&auto_play=false&start_volume=25" /></object></div>'),

//citytv.com.co
array ('/citytv\.com\.co\/videos\/(.*)\//i', '<div class="myvideotag" style="width: '.$w.'px;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,115,0" id="videocom_{ID_VIDEO}" height="'.$h.'" width="'.$w.'"><param name="movie" value="http://www.citytv.com.co/media/swf/Videocom.swf"></param><param name="allowfullscreen" value="true"></param><param name="allowscriptaccess" value="always"></param><param name="flashvars" value="videoID={ID_VIDEO}&showTools=false&autoPlay=false"></param><param name=wmode value=transparent></param><embed wmode=transparent name="videocom_{ID_VIDEO}" src="http://www.citytv.com.co/media/swf/Videocom.swf"  flashvars= "videoID={ID_VIDEO}&showTools=false&autoPlay=false" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" height="'.$h.'" width="'.$w.'"/></embed></object></div>'),

array ('/citytv\.com\.co\/videos\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,115,0" id="videocom_{ID_VIDEO}" height="'.$h.'" width="'.$w.'"><param name="movie" value="http://www.citytv.com.co/media/swf/Videocom.swf"></param><param name="allowfullscreen" value="true"></param><param name="allowscriptaccess" value="always"></param><param name="flashvars" value="videoID={ID_VIDEO}&showTools=false&autoPlay=false"></param><param name=wmode value=transparent></param><embed wmode=transparent name="videocom_{ID_VIDEO}" src="http://www.citytv.com.co/media/swf/Videocom.swf"  flashvars= "videoID={ID_VIDEO}&showTools=false&autoPlay=false" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" height="'.$h.'" width="'.$w.'"/></embed></object></div>'),

//snotr.com
array ('/snotr\.com\/video\/(.*)\//i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe src="http://www.snotr.com/embed/{ID_VIDEO}" width="'.$w.'" height="'.$h.'" frameborder="0"></iframe></div>'),

//videobb.com
array ('/videobb\.com\/watch_video.php\?v=(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object id="player" width="'.$w.'" height="'.$h.'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ><param name="movie" value="http://www.videobb.com/e/{ID_VIDEO}" ></param><param name="allowFullScreen" value="true" ></param><param name="allowscriptaccess" value="always"></param><param name=wmode value=transparent></param><embed src="http://www.videobb.com/e/{ID_VIDEO}" type="application/x-shockwave-flash" allowscriptaccess="always" wmode="transparent" allowfullscreen="true" width="'.$w.'" height="'.$h.'"></embed></object></div>'),
array ('/videobb\.com\/video\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object id="player" width="'.$w.'" height="'.$h.'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ><param name="movie" value="http://www.videobb.com/e/{ID_VIDEO}" ></param><param name="allowFullScreen" value="true" ></param><param name="allowscriptaccess" value="always"></param><param name=wmode value=transparent></param><embed src="http://www.videobb.com/e/{ID_VIDEO}" type="application/x-shockwave-flash" allowscriptaccess="always" wmode="transparent" allowfullscreen="true" width="'.$w.'" height="'.$h.'"></embed></object></div>'),
array ('/videobb\.com\/f\/([^.]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object id="player" width="'.$w.'" height="'.$h.'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ><param name="movie" value="http://www.videobb.com/e/{ID_VIDEO}" ></param><param name="allowFullScreen" value="true" ></param><param name="allowscriptaccess" value="always"></param><param name=wmode value=transparent></param><embed src="http://www.videobb.com/e/{ID_VIDEO}" type="application/x-shockwave-flash" allowscriptaccess="always" wmode="transparent" allowfullscreen="true" width="'.$w.'" height="'.$h.'"></embed></object></div>'),
// videobb.com /e/ (par Antoine)
array ('/videobb\.com\/e\/(.+)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object id="player" width="'.$w.'" height="'.$h.'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ><param name="movie" value="http://www.videobb.com/e/{ID_VIDEO}" ></param><param name="allowFullScreen" value="true" ></param><param name="allowscriptaccess" value="always"></param><param name=wmode value=transparent></param><embed src="http://www.videobb.com/e/{ID_VIDEO}" type="application/x-shockwave-flash" allowscriptaccess="always" wmode="transparent" allowfullscreen="true" width="'.$w.'" height="'.$h.'"></embed></object></div>'),
// videobb.com /player/player.swf (par Antoine)
array ('/(videobb\.com\/player.*)/si', '<div class="myvideotag" style="width: '.$w.'px;"><object id="player" width="'.$w.'" height="'.$h.'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ><param name="movie" value="http://www.{ID_VIDEO}" ></param><param name="allowFullScreen" value="true" ></param><param name="allowscriptaccess" value="always"></param><param name=wmode value=transparent></param><embed src="http://www.{ID_VIDEO}" type="application/x-shockwave-flash" allowscriptaccess="always" wmode="transparent" allowfullscreen="true" width="'.$w.'" height="'.$h.'"></embed></object></div>'),

// wat.tv  (par Antoine)
array ('/wat\.tv\/swf2\/[0-9A-Za-z]{19}/si', '<div class="myvideotag" style="width: '.$w.'px;"> <object width="'.$w.'" height="'.$h.'"><param name="movie" value="http://www.wat.tv/swf2/{ID_VIDEO}" /><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="true" /><embed src="http://www.wat.tv/swf2/{ID_VIDEO}" type="application/x-shockwave-flash" width="'.$w.'" height="'.$h.'" allowScriptAccess="always" allowFullScreen="true"></embed></object> </div>'),

//videozer.com
array ('/videozer\.com\/video\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object id="player" width="'.$w.'" height="'.$h.'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ><param name="movie" value="http://videozer.com/embed/{ID_VIDEO}" ></param><param name="allowFullScreen" value="true" ></param><param name="allowscriptaccess" value="always"></param><param name=wmode value=transparent></param><embed src="http://videozer.com/embed/{ID_VIDEO}" type="application/x-shockwave-flash" allowscriptaccess="always" wmode="transparent" allowfullscreen="true" width="'.$w.'" height="'.$h.'"></embed></object></div>'),

//novamov.com
array ('/novamov\.com\/video\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe style="overflow: hidden; border: 0; width: '.$w.'px; height: '.$h.'px" src="http://embed.novamov.com/embed.php?width='.$w.'&height='.$h.'&v={ID_VIDEO}&px=1" scrolling="no"></iframe></div>'),

//youku.com
array ('/v\.youku\.com\/v_show\/id_(.*)\.html/i', '<div class="myvideotag" style="width: '.$w.'px;"><embed src="http://player.youku.com/player.php/sid/{ID_VIDEO}/v.swf" quality="high" width="'.$w.'" height="'.$h.'" align="middle" allowScriptAccess="sameDomain" wmode="transparent" type="application/x-shockwave-flash"></embed></div>'),

//vxv.com
array ('/vxv\.com\/video\/(.*)\//i', '<div class="myvideotag" style="width: '.$w.'px;"><object height="'.$h.'" width="'.$w.'" name="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" id="player"> <param value="http://www.vxv.com/e/{ID_VIDEO}" name="movie"><param value="true" name="allowfullscreen"><param value="always" name="allowscriptaccess"><embed height="'.$h.'" width="'.$w.'" allowfullscreen="true" wmode="transparent" allowscriptaccess="always" src="http://www.vxv.com/e/{ID_VIDEO}" name="player2" id="player2" type="application/x-shockwave-flash"></object></div>'),
      
//clipshack.com
array ('/clipshack\.com\/Clip.aspx(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><embed src="http://www.clipshack.com/player.swf{ID_VIDEO}" width="'.$w.'" height="'.$h.'" wmode="transparent"></embed></div>'),

//putlocker.com
array ('/putlocker\.com\/file\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe src="http://www.putlocker.com/embed/{ID_VIDEO}" width="'.$w.'" height="'.$h.'" frameborder="0" scrolling="no"></iframe></div>'),

//userporn.com
array ('/userporn\.com\/video\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object id="player" width="'.$w.'" height="'.$h.'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ><param name="movie" value="http://www.userporn.com/e/{ID_VIDEO}" ></param><param name="allowFullScreen" value="true" ></param><param name="allowscriptaccess" value="always"></param><param name="wmode" value="transparent"></param><embed src="http://www.userporn.com/e/{ID_VIDEO}" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="'.$w.'" height="'.$h.'"></embed></object></div>'),

/*
//megaporn.com
array ('/megaporn\.com.*v=(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'"><param name="movie" value="http://www.megaporn.com/e/{ID_VIDEO}"></param><param name="allowFullScreen" value="true"></param><param name="wmode" value="transparent"></param><embed src="http://www.megaporn.com/e/{ID_VIDEO}" type="application/x-shockwave-flash" allowfullscreen="true" width="'.$w.'" height="'.$h.'"></embed></object></div>'),
*/

//uploadc.com
array ('/uploadc\.com\/([^\/]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><IFRAME SRC="http://www.uploadc.com/embed-{ID_VIDEO}.html" FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO width='.$w.' height='.$h.'></IFRAME></div>'),

//ovfile.com
array ('/ovfile\.com\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><IFRAME SRC="http://ovfile.com/embed-{ID_VIDEO}.html" FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO width='.$w.' height='.$h.'></IFRAME></div>'),

//veevr.com
array ('/veevr\.com\/videos\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe src="http://veevr.com/embed/{ID_VIDEO}?w='.$w.'&h='.$h.'" width="'.$w.'" height="'.$h.'" scrolling="no" frameborder="0"></iframe></div>'),

//divxstage.eu
array ('/divxstage\..*?\/video\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe style="overflow: hidden; border: 0; width: '.$w.'px; height: '.$h.'px" src="http://embed.divxstage.eu/embed.php?v={ID_VIDEO}&width='.$w.'&height='.$h.'" scrolling="no"></iframe></div>'),

//usershare.net
array ('/usershare\.net\/(.*)\//i', '<div class="myvideotag" style="width: '.$w.'px;"><IFRAME SRC="http://www.usershare.net/embedmp4-{ID_VIDEO}.html" FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO WIDTH='.$w.' HEIGHT='.$h.'></IFRAME></div>'),

//sockshare.com
array ('/sockshare\.com\/file\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe src="http://www.sockshare.com/embed/{ID_VIDEO}" width="'.$w.'" height="'.$h.'" frameborder="0" scrolling="no"></iframe></div>'),

//blip.tv
array ('/blip\.tv.*-(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object id="player" width="'.$w.'" height="'.$h.'"><param name="movie" value="http://blip.tv/scripts/flash/showplayer.swf?file=http://blip.tv/rss/flash/{ID_VIDEO}" ></param><param name="allowFullScreen" value="true" ></param><param name="allowscriptaccess" value="always"></param><param name=wmode value=transparent></param><embed src="http://blip.tv/scripts/flash/showplayer.swf?file=http://blip.tv/rss/flash/{ID_VIDEO}" type="application/x-shockwave-flash" width="'.$w.'" height="'.$h.'" allowscriptaccess="always" allowfullscreen="true" /></embed></object></div>'),

//veoh.com
array ('/veoh\.com\/watch\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object width="410" height="341" id="veohFlashPlayer" name="veohFlashPlayer"><param name="movie" value="http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1144&permalinkId={ID_VIDEO}&player=videodetailsembedded&videoAutoPlay=0&id=anonymous"></param><param name="allowFullScreen" value="true"></param><param name="wmode" value="transparent"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1144&permalinkId={ID_VIDEO}&player=videodetailsembedded&videoAutoPlay=0&id=anonymous" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="'.$w.'" height="'.$h.'" id="veohFlashPlayerEmbed" name="veohFlashPlayerEmbed"></embed></object></div>'),

//zappinternet.com
array ('/zappinternet\.com\/video\/([^\/]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object type="application/x-shockwave-flash" data="http://zappinternet.com/v/{ID_VIDEO}" height="'.$h.'" width="'.$w.'"><param name="movie" value="http://zappinternet.com/v/KaZxBojJov" /><param name="wmode" value="transparent"></param><param name="allowFullScreen" value="true" /></object></div>'),
//zappin.me
array ('/zappin\.me\/([^\/]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object type="application/x-shockwave-flash" data="http://zappinternet.com/v/{ID_VIDEO}" height="'.$h.'" width="'.$w.'"><param name="wmode" value="transparent"></param><param name="movie" value="http://zappinternet.com/v/KaZxBojJov" /><param name="allowFullScreen" value="true" /></object></div>'),

//liveleak.com
array ('/liveleak\.com.*=(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'"><param name="movie" value="http://www.liveleak.com/e/{ID_VIDEO}"></param><param name="wmode" value="transparent"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.liveleak.com/e/{ID_VIDEO}" type="application/x-shockwave-flash" wmode="transparent" allowscriptaccess="always" width="'.$w.'" height="'.$h.'"></embed></object></div>'),

//dalealplay.com
array ('/dalealplay\.com.*=(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'" type="application/x-shockwave-flash" data="http://c.brightcove.com/services/viewer/federated_f9?&amp;width='.$w.'&amp;height='.$h.'&amp;flashID=reproductor&amp;wmode=transparent&amp;playerID=81909921001&amp;publisherID=35140843001&amp;isVid=true&amp;isUI=true&amp;experienceID=reproductor&amp;videoSmoothing=true&amp;sct=sports&amp;pb=ZOO%3A100&amp;optimizedContentLoad=true&amp;%40videoPlayer=ref%3ADAP-{ID_VIDEO}&amp;autoStart=0" seamlesstabbing="false"><param name="allowScriptAccess" value="always"></param><param name="allowFullScreen" value="true"></param><param name="seamlessTabbing" value="false"></param><param name="swliveconnect" value="true"></param><param name="wmode" value="transparent"></param><param name="quality" value="high"></param></object></div>'),

//stagevu.com
array ('/stagevu\.com\/video\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe src="http://stagevu.com/embed?width='.$w.'&amp;height='.$h.'&amp;background=000&amp;uid={ID_VIDEO}" width="'.$w.'" height="'.$h.'" frameborder="0" scrolling="no"></iframe></div>'),

//flickr.com
array ('/flickr\.com\/photos\/.*?\/([^\/]*)\//i', '<div class="myvideotag" style="width: '.$w.'px;"><object type="application/x-shockwave-flash" width="'.$w.'" height="'.$h.'" data="http://www.flickr.com/apps/video/stewart.swf?v=71377" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"> <param name="flashvars" value="photo_id={ID_VIDEO}"></param> <param name="movie" value="http://www.flickr.com/apps/video/stewart.swf?v=71377"></param><param name="bgcolor" value="#000000"></param><param name="wmode" value="transparent"></param><param name="allowFullScreen" value="true"></param><embed type="application/x-shockwave-flash" src="http://www.flickr.com/apps/video/stewart.swf?v=71377" bgcolor="#000000" allowfullscreen="true" flashvars="photo_id={ID_VIDEO}" height="'.$h.'" width="'.$w.'"></embed></object></div>'),

//videoweed.es
array ('/videoweed\.es\/file\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe width="'.$w.'" height="'.$h.'" frameborder="0" src="http://embed.videoweed.es/embed.php?v={ID_VIDEO}&width='.$w.'&height='.$h.'" scrolling="no"></iframe></div>'),

//rutube.ru
array ('/rutube\.ru.*v=(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><OBJECT width="'.$w.'" height="'.$h.'"><PARAM name="movie" value="http://video.rutube.ru/{ID_VIDEO}"></PARAM><PARAM name="wmode" value="transparent"></PARAM><PARAM name="allowFullScreen" value="true"></PARAM><EMBED src="http://video.rutube.ru/{ID_VIDEO}" type="application/x-shockwave-flash" wmode="window" width="'.$w.'" height="'.$h.'" allowFullScreen="true" ></EMBED></OBJECT></div>'),

//screenr.com
array ('/screenr\.com\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe src="http://www.screenr.com/embed/{ID_VIDEO}" width="'.$w.'" height="'.$h.'" frameborder="0"></iframe></div>'),

//zkouknito.cz
array ('/zkouknito\.cz\/video_([^_]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object allowfullscreen="true" allowscriptaccess="always" type="application/x-shockwave-flash" style="width:'.$w.'px; height:'.$h.'px;" data="http://www.zkouknito.cz/player/external.swf?vid={ID_VIDEO}"><param name="allowfullscreen" value="true" /><param name="wmode" value="transparent"></param><param name="allowscriptaccess" value="always" /><param name="movie" value="http://www.zkouknito.cz/player/external.swf?vid={ID_VIDEO}" /></object></div>'),

//cinemovies.fr
array ('/cinemovies\.fr.*-([^.]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><embed height="'.$h.'" width="'.$w.'" allowscriptaccess="always" allowfullscreen="true" flashvars="config=http://www.cinemovies.fr/player/export-ba-{ID_VIDEO}.xml" src="http://www.cinemovies.fr/player/export/CineMovies2.swf"></embed></div>'),

//allocine.fr
array ('/allocine\.fr.*cmedia=([^&]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object width="'.$w.'" height="'.$h.'"><param name="movie" value="http://www.allocine.fr/blogvision/{ID_VIDEO}"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="http://www.allocine.fr/blogvision/{ID_VIDEO}" type="application/x-shockwave-flash" width="'.$w.'" height="'.$h.'" allowFullScreen="true" allowScriptAccess="always"></embed></object></div>'),

//videos.sapo.pt
array ('/videos\.sapo\.pt\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe src="http://videos.sapo.pt/playhtml?file=http://rd3.videos.sapo.pt/{ID_VIDEO}/mov/1&relatedVideos=none" frameborder="0" scrolling="no" width="'.$w.'" height="'.$h.'"></iframe></div>'),

//videos .flv
array ('/(.*).flv/i', '<div class="myvideotag" style="width: '.$w.'px;"><embed src="'.MVT_URLPATH.'media/mvtplayer.swf" flashvars="?&autoplay=false&sound=70&buffer=2&splashscreen='.MVT_URLPATH.'images/preview.png&vdo={ID_VIDEO}.flv" width="'.$w.'" height="'.$h.'" allowFullScreen="true" quality="best" wmode="transparent" allowScriptAccess="always"  pluginspage="http://www.macromedia.com/go/getflashplayer"  type="application/x-shockwave-flash"></embed>
</div>'),

//videos .mp4
array ('/(.*).mp4/i', '<div class="myvideotag" style="width: '.$w.'px;"><embed src="'.MVT_URLPATH.'media/mvtplayer.swf" flashvars="?&autoplay=false&sound=70&buffer=2&splashscreen='.MVT_URLPATH.'images/preview.png&vdo={ID_VIDEO}.mp4" width="'.$w.'" height="'.$h.'" allowFullScreen="true" quality="best" wmode="transparent" allowScriptAccess="always"  pluginspage="http://www.macromedia.com/go/getflashplayer"  type="application/x-shockwave-flash"></embed>
</div>'),

//upload2.com
array ('/upload2\.com\/video\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe width="'.$w.'" scrolling="no" height="'.$h.'" frameborder="0" src="http://upload2.com/embed/{ID_VIDEO}"></iframe></div>'),

//vesti.ru
array ('/vesti\.ru\/videos\?vid=([^&]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,29,0" width="'.$w.'" height="'.$h.'" id="flvplayer_videoHost" align="middle"><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="true" /><param name="movie" value="http://www.vesti.ru/i/flvplayer_videoHost.swf?vid={ID_VIDEO}&fbv=true&isHome=false" /><param name="quality" value="high" /><param name="wmode" value="transparent" /><param name="devicefont" value="true" /><param name="bgcolor" value="#000000" /><param name="vid" value="{ID_VIDEO}" /><embed src="http://www.vesti.ru/i/flvplayer_videoHost.swf?vid={ID_VIDEO}&fbv=true&isHome=false" quality="high" devicefont="true" bgcolor="#000000" width="'.$w.'" height="'.$h.'" name="flvplayer" align="middle" allowScriptAccess="always" allowFullScreen="true" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object></div>'),

//vidhog.com
array ('/vidhog\.com\/(.*).html/i', '<div class="myvideotag" style="width: '.$w.'px;"><IFRAME SRC="http://www.vidhog.com/embed-{ID_VIDEO}.html" FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO WIDTH='.$w.' HEIGHT='.$h.'></IFRAME></div>'),

//xhamster.com
array ('/xhamster\.com\/movies\/([^\/]*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe width="'.$w.'" height="'.$h.'" src="http://xhamster.com/xembed.php?video={ID_VIDEO}" frameborder="0" scrolling="no"></iframe></div>'),

//break.com
array ('/break\.com\/.*?\/.*-(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><object type="application/x-shockwave-flash" data="http://embed.break.com/{ID_VIDEO}" width="'.$w.'" height="'.$h.'" wmode="transparent"><param name="movie" value="http://embed.break.com/{ID_VIDEO}" /></object></div>'),

//girlslikeporn.net
array ('/girlslikeporn\.net\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><IFRAME SRC="http://girlslikeporn.net/embed-{ID_VIDEO}-'.$w.'x'.$h.'.html" FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO WIDTH='.$w.' HEIGHT='.$h.'></IFRAME></div>'),

//viki.com
array ('/.*?.viki\.com\/.*?\/.*?\/.*?\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe src="http://www.viki.com/player/medias/{ID_VIDEO}" width="'.$w.'" height="'.$h.'" frameBorder="0"></iframe></div>'),

//vzaar.com
array ('/vzaar\.com\/videos\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe allowFullScreen class="vzaar-video-player" frameborder="0" height="'.$h.'" id="vzvd-{ID_VIDEO}" name="vzvd-{ID_VIDEO}" src="http://view.vzaar.com/{ID_VIDEO}/player" title="vzaar video player" type="text/html" width="'.$w.'"></iframe></div>'),
array ('/vzaar\.tv\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;"><iframe allowFullScreen class="vzaar-video-player" frameborder="0" height="'.$h.'" id="vzvd-{ID_VIDEO}" name="vzvd-{ID_VIDEO}" src="http://view.vzaar.com/{ID_VIDEO}/player" title="vzaar video player" type="text/html" width="'.$w.'"></iframe></div>'),

//4shared.com
array ('/4shared\.com\/.*?\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;" id="myvideotag"><embed src="http://www.4shared.com/embed/{ID_VIDEO}" width="'.$w.'" height="'.$h.'" allowfullscreen="true" allowscriptaccess="always"></embed></div>'),

//vidxden.com
array ('/vidxden\.com\/([^\/]*)/i', '<div class="myvideotag" style="width: '.$w.'px;" id="myvideotag"><IFRAME SRC="http://www.vidxden.com/embed-{ID_VIDEO}.html" FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO WIDTH='.$w.' HEIGHT='.$h.'></IFRAME></div>'),

//vidbux.com
array ('/vidbux\.com\/([^\/]*)/i', '<div class="myvideotag" style="width: '.$w.'px;" id="myvideotag"><IFRAME SRC="http://www.vidbux.com/embed-{ID_VIDEO}-width-'.$w.'-height-'.$h.'.html" FRAMEBORDER=0 MARGINWIDTH=0 MARGINHEIGHT=0 SCROLLING=NO WIDTH='.$w.' HEIGHT='.$h.'></IFRAME></div>'),

//movshare.net
array ('/movshare\.net\/video\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;" id="myvideotag"><iframe style="overflow: hidden; border: 0; width: '.$w.'px; height: '.$h.'px" src="http://embed.movshare.net/embed.php?v={ID_VIDEO}&width='.$w.'&height='.$h.'&color=black" scrolling="no"></iframe></div>'),

//mais.uol.com.br
array ('/mais\.uol\.com\.br\/view\/(.*)/i', '<div class="myvideotag" style="width: '.$w.'px;" id="myvideotag">
<object width="'.$w.'" height="'.$h.'" id="player_{ID_VIDEO}" ><param value="true" name="allowfullscreen"/><param value="http://storage.mais.uol.com.br/embed_v2.swf?mediaId={ID_VIDEO}" name="movie"/><param value="always" name="allowscriptaccess"/><param value="window" name="wmode"/><embed id="player_{ID_VIDEO}" width="'.$w.'" height="'.$h.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" src="http://storage.mais.uol.com.br/embed_v2.swf?mediaId={ID_VIDEO}" wmode="window" /></embed></object>
</div>'),

//vevo.com
array ('/vevo\.com\/watch\/.*?\/.*?\/([^?]*)/i', '<div class="myvideotag" style="width: '.$w.'px;" id="myvideotag">
<object width="'.$w.'" height="'.$h.'"><param name="movie" value="http://videoplayer.vevo.com/embed/Embedded?videoId={ID_VIDEO}&playlist=false&autoplay=0&playerId=62FF0A5C-0D9E-4AC1-AF04-1D9E97EE3961&playerType=embedded&env=0&cultureName=en-US&cultureIsRTL=False"></param><param name="wmode" value="transparent"></param><param name="bgcolor" value="#000000"></param><param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param><embed src="http://videoplayer.vevo.com/embed/Embedded?videoId={ID_VIDEO}&playlist=false&autoplay=0&playerId=62FF0A5C-0D9E-4AC1-AF04-1D9E97EE3961&playerType=embedded&env=0&cultureName=en-US&cultureIsRTL=False" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="'.$w.'" height="'.$h.'" bgcolor="#000000" wmode="transparent"></embed></object>
</div>'),

//movreel.com
array ('/movreel\.com\/([^\/]*)/i', '<iframe src="http://movreel.com/embed/{ID_VIDEO}" width=607 height=470 border=0 frameborder=0 scrolling=no></iframe>'),

//shiatv.net
array ('/shiatv\.net\/.*?viewkey=([^&]*)/i', '<embed src="http://shiatv.net/player41.swf?file=http://shiatv.net/runmyfile.php?vkey={ID_VIDEO}&autostart=false&showfsbutton=true&wmode=transparent&logo=http://shiatv.net/domainshiatv/images/logoplayer.png&link=http://shiatv.net" loop="False" width="'.$w.'" height="'.$h.'" allowfullscreen="true" allowscriptaccess="always" flashvars="width='.$w.'&height='.$h.'&file=http://shiatv.net/runmyfile.php?vkey={ID_VIDEO}&backcolor=0x000033&frontcolor=0xffffff&lightcolor=0xffffff&screencolor=0x000033&displayheight='.$h.'&displaywidth='.$w.'&searchbar=false&autoscroll=true&autostart=false&logo=http://shiatv.net/domainshiatv/images/logoplayer.png&link=http://shiatv.net name="/YouTube_video/youtube" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"\" />'),

//gametrailers.com
array ('/gametrailers\.com\/video\/.*?\/([^\/]*)/i', '<iframe src="http://media.mtvnservices.com/embed/mgid:moses:video:gametrailers.com:{ID_VIDEO}" width="'.$w.'" height="'.$h.'" frameborder="0"></iframe>'),

//videobam.com
array ('/videobam\.com\/(.*)/i', '<iframe title="VideoBam video player" type="text/html" frameborder="0" scrolling="no" width="480" height="415" src="http://videobam.com/widget/{ID_VIDEO}" allowFullScreen></iframe>'),


);// Video arrays stop here, do not place after this line!

foreach ($values as $value){
if (preg_match($value[0], $content, $matches)){
$id_video=$matches[1];
return preg_replace_callback('/{.*?}/', create_function('$matches', 'switch (true){
case preg_match("/\{ID_VIDEO\}/", $matches[0]):
return "'.$id_video.'";
break;
case preg_match("/\{LINK\}/", $matches[0]):
return "'.$content.'";
break;
case preg_match("/\{DOWNLOAD(.*?)%(.*?)%(.*?)\}/", $matches[0], $matches2):
if (empty($matches2[1])) $matches2[1]="'.$content.'";
preg_match($matches2[2], file_get_contents(str_replace(" ","+",$matches2[1])), $matches3);
if (empty($matches2[3])){
return $matches3[1];
}else{
$t=$matches3[1];
foreach(explode("|", $matches2[3]) as $e){
eval(\'$t=\'.$e.\'($t);\');
}
return $t;
}
break;
}
return $matches[0];'), $value[1]);
}
}
return '<div class="mvtalert">'.__('Sorry, site not recognized', 'my-videotag').'</div>';
}
?>