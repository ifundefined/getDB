<?php ini_set('max_execution_time',0); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: Get Content ::</title>
<link rel="stylesheet" href="public/stylesheets/foundation.min.css">
<link rel="stylesheet" href="public/stylesheets/app.css">
<script src="public/javascripts/modernizr.foundation.js"></script>
</head>
<body>
<?php
	
	if(!empty($_REQUEST['linkWebSite'])){
		include('database.php');
		include('siteContent.php');
		$siteContent = new siteContent();
?>
<div class="row">
  <div><a href="getContent.php">&laquo; Retour</a></div>
  <div class="eight columns centered"> traitement en cours ... </div>
  <?php
  	$siteContent->setParamWithProxy(false);
	$siteContent->dirMadia = '../developer/media';
	$siteContent->main($_REQUEST['linkWebSite'],$_REQUEST['numPageBegin'],$_REQUEST['numPageEnd']);
	//$siteContent->upddteCategoryTaxonomy();
	//$siteContent->getParamIdBegin();
	
  ?>
  <div><a href="getContent.php">&laquo; Retour</a></div>
</div>
<?php 
	}else{
?>
<div class="row">
  <div class="eight columns centered">
    <form action="getContent.php" method="post">
      <fieldset>
        <legend>Fieldset Name</legend>
        <div class="formRow">
          <label for="idLinkWebSite"> Link Web Site * :</label>
          <input type="text" name="linkWebSite" id="idLinkWebSite" size="70" />
        </div>
        <div class="formRow">
          <label for="idNumPageBegin"> Num Page Begin :</label>
          <input type="text" name="numPageBegin" id="idNumPageBegin" value="0" />
        </div>
        <div class="formRow">
          <label for="idNumPageEnd"> Num Page End :</label>
          <input type="text" name="numPageEnd" id="idNumPageEnd" value="0" />
        </div>
        <div class="formRow">
          <input class="button" type="submit" value="submit" />
          <input class="button" type="reset" value="reset" />
        </div>
      </fieldset>
    </form>
  </div>
</div>
<?php } ?>
</body>
</html>