<?php ini_set('max_execution_time',0); 
include('database.php');
include('siteContent.php');
$siteContent = new siteContent();
//$siteContent->setParamWithProxy(true);
$siteContent->dirMadia = '../media';
$siteContent->main('http://psd.tutsplus.com/',1,1);
$siteContent->main('http://net.tutsplus.com/',1,1);
$siteContent->main('http://vector.tutsplus.com/',1,1);
$siteContent->main('http://audio.tutsplus.com/',1,1);
$siteContent->main('http://photo.tutsplus.com/',1,1);
$siteContent->main('http://mobile.tutsplus.com/',1,1);
$siteContent->main('http://webdesign.tutsplus.com/',1,1);
$siteContent->main('http://mac.tutsplus.com/',1,1);
$siteContent->main('http://mac.tutsplus.com/',1,1);
$siteContent->main('http://gamedev.tutsplus.com/',1,1);

?>