<?php
	/**
		** 05-11-2012
		** update
		** optimisation pour le parcoure des article est l'association des category et taxonomy
		** add nex param $this->idBegin designed i first item insert
	*/
	include('proxy.php');
	include('simple_html_dom.php');
	
	
	
	class StructureDom {
		/*
		|
		|	Public Attributs
		|	This attribute is the HTML DOM structure
		|
		*/
		public $pathArticle ='div.type-post';
		public $pathImg ='div.post_image a img';
		public $pathImg_z = 0;
		public $pathImgDefault = 'default.png';
		public $pathArticlePostTitle ='div.preview h1.post_title a';
		public $pathArticlePostTitle_z = 0;
		public $pathArticlePostMeta ='div.preview div.post_meta';
		public $pathArticlePostMeta_z = 0;
		public $pathArticlePostBy ='div.preview div.post_meta a';
		public $pathArticlePostBy_z = 0;
		public $pathArticleText ='div.preview div.text p';
		public $pathArticleText_z = 0;
		public $pathArticleMoreLink ='div.preview a.more-link';
		public $pathArticleMoreLink_z = 0;
		

		
	}
	
	class SiteContent extends Database{
		
		/*
		|
		|	Private Attributs
		|
		*/
		private $needleBegin = '//';
		private $needleEnd = '/';
		private $proxy = '';
		private $structureDom = '';
		private $idBegin = 0;
		
		/*
		|
		|	Public Attributs
		|
		*/
		public $url = '';
		public $dirUrl = '';
		public $pathDirUrl = '';
		public $dirMadia = 'media';
		public $dirSaving = 'saving';
		public $numPageBegin = 1;
		public $numPageEnd = 10;
		public $step = 10;
		public $withProxy = false;
		
		
		function __construct() {
			parent::__construct();
			$this->proxy = new Proxy();
			$this->structureDom = new StructureDom();
		}
		/**
		 * main
		 * Public
		 * get file html and parse dom and insert to data base 
		 *
		 * @return	void
		 */
		public function main($url = false, $numPageBegin = 0, $numPageEnd = 0){
			// init dir url
			if(empty($this->dirUrl) && !$url ){
				exit('Url not declare');
			}else{
				$url = ($url)? $url : $this->url;
				$this->dirUrl($url);
			}
			
			$this->numPageBegin =  $numPageBegin;
			$this->numPageEnd =  $numPageEnd;

			for($i=$this->numPageBegin; $i<=$this->numPageEnd; $i++){
				if($this->withProxy){
					$html = file_get_html('http://'.$this->dirUrl.'/page/'.$i.'/',false,$this->proxy->ctx);
				}else{
					$html = file_get_html('http://'.$this->dirUrl.'/page/'.$i.'/');
				}
				$html->find('head', 0)->innertext = '';
				//$html->save($this->dirSaving.'/page.'.$i.'.html');
				$this->htmlParse($html,$i);
				$html->clear();
			}
			
		}
		/**
		 * ParseHtml
		 * Public
		 * parser HTML DOM && insert to Data Base
		 *
		 * @return	void
		 */
		public function htmlParse($html,$currentNumPage){

			$mktime = time();
			$i = true;
			// parse nodes
			foreach($html->find($this->structureDom->pathArticle) as $key => $article) {
				//get tag category of article
				$this->htmlTagsCategorie($article->class, $arg);
				
				//art_category 
					$item['art_category'] = implode(',',$arg['arg_cat']);
					
				//art_taxonomy 			
					$item['art_taxonomy'] = implode(',',$arg['arg_tag']);
					
				//art_id
					$item['art_id']     				= trim($article->id);
				
				//art_img
					$item['art_img'] = (isset($article->find($this->structureDom->pathImg,$this->structureDom->pathImg_z)->src)) ? trim($article->find($this->structureDom->pathImg,$this->structureDom->pathImg_z)->src) : $this->structureDom->pathImgDefault;
				
				//art_post_title
				$item['art_post_title']    	= trim($article->find($this->structureDom->pathArticlePostTitle,$this->structureDom->pathArticlePostTitle_z)->plaintext);
				
				//art_posts_by
					$item['art_posts_by']				= trim($article->find($this->structureDom->pathArticlePostBy,$this->structureDom->pathArticlePostBy_z)->plaintext);
					$item['art_posts_by_link']				= trim($article->find($this->structureDom->pathArticlePostBy,$this->structureDom->pathArticlePostBy_z)->href);
				
				//art_date_post
					$article->find($this->structureDom->pathArticlePostBy,$this->structureDom->pathArticlePostBy_z)->innertext = ' ';
					$article->find($this->structureDom->pathArticlePostBy,$this->structureDom->pathArticlePostBy_z+1)->innertext = ' ';
					$post_meta = $article->find($this->structureDom->pathArticlePostMeta,$this->structureDom->pathArticlePostMeta_z)->plaintext;
					$post_meta  = substr($post_meta, strpos($post_meta, 'on ')+3);
					$art_date_post_time = strtotime(trim($post_meta));
					$item['art_date_post']			= $art_date_post_time;
				
				//art_description
					$item['art_description']				= trim($article->find($this->structureDom->pathArticleText,$this->structureDom->pathArticleText_z)->plaintext);
					
				//art_link_more
					if(isset($article->find('p.ct-feat-excerpt span a',0)->href))
						$item['art_link_more']				= trim($article->find($this->structureDom->pathArticleMoreLink,$this->structureDom->pathArticleMoreLink_z)->href);
					else
						$item['art_link_more']				= trim($article->find($this->structureDom->pathArticlePostTitle,$this->structureDom->pathArticlePostTitle_z)->href);
						
				//art_date_insert
				$item['art_date_insert']				= $mktime;
				
				// GET IMAGE
				if($item['art_img'] != $this->structureDom->pathImgDefault){
					if($this->withProxy){
						$content = file_get_contents(str_replace('https://', 'http://', $item['art_img']) , false,$this->proxy->ctx );
					}else{
						$content = file_get_contents(str_replace('https://', 'http://', $item['art_img']) );
					}
					//file_put_contents($this->dirMadia.'/'.basename($item['art_img']) , $content);
					file_put_contents($this->dirMadia.'/'.trim($article->id).'.jpg' , $content);
				}
				$articles[] = $item;
				
				// save article to DB
				$id = $this->newArticles($item, $currentNumPage);
				if($currentNumPage == $this->numPageBegin && $i ){
					$this->idBegin = $id;	
				}
				$i = false;
			}
			//print_r($articles);
		}
		/**
		 * NewArticles
		 * Private
		 * Insert New Article to DB
		 *
		 * @param	article arrayy
		 * @return	void
		 */
		private function newArticles($article,$i){
			$result = mysql_query("SELECT * FROM  articles WHERE  art_id =  '".$article['art_id']."'");
			if (!$result) {
				// Error query
				die('Requête article exist with id '.$article['art_id'].' is invalide : ' . mysql_error());
			}else{
				// query succes
				if(mysql_num_rows($result) != 0){
					// if article already exists
					echo 'Article '.$i.' which in the ID  '.$article['art_id'].' already exists  !<br>------------<br><br>';	
				}else{
					// save to DB
					$sql_query = "INSERT INTO articles (
									id ,
									art_id ,
									art_post_title ,
									art_img ,
									art_posts_by ,
									art_posts_by_link ,
									art_description ,
									art_link_more ,
									art_link_src ,
									art_date_post ,
									art_date_insert ,
									art_taxonomy ,
									art_category ,
									art_site ,
									art_parent_cat ,
									active
									)
									VALUES (
									NULL,
									'".$article['art_id']."',
									'".addslashes($article['art_post_title'])."',
									'".basename($article['art_img'])."',
									'".addslashes($article['art_posts_by'])."',
									'".$article['art_posts_by_link']."',
									'".addslashes($article['art_description'])."',
									'".$article['art_link_more']."',
									'".$this->dirUrl."/page/".$i."',
									'".$article['art_date_post']."',
									'".$article['art_date_insert']."',
									'".$article['art_taxonomy']."',
									'".$article['art_category']."',
									'".$this->url."',
									'".$this->dirUrl."',
									'1'
									);";
						$result = mysql_query($sql_query); // Run QUERY
						$id = mysql_insert_id();
						// dba taxonomy
						$this->updateCategoryTaxonomy($id, 'dba_category', $article['art_category'], 'dba_articles_category', 'id_category');
						// dba category
						$this->updateCategoryTaxonomy($id, 'dba_taxonomy', $article['art_taxonomy'], 'dba_articles_taxonomy', 'id_taxonomy');
						if (!$result) {
							die('!!! Requête article insert with id '.$article['art_id'].' is invalide : ' . mysql_error());
						}else{
							echo 'Requête '.$i.' success !<br>------------<br><br>';
						} // if query succes					
				}
			}// if query succes
				
		}
		
		/**
		 * TabCategory
		 * Public
		 * Select a new Category
		 *
		 * @return	tab_unique array
		 */
		public function tabItems($items){
			$tab_items = array();
			$tab_expload_cat = explode(',',$items);
			foreach($tab_expload_cat as $item){
				if(trim($item) != '' ){
					array_push($tab_items, $item);
				}
			}
			return $tab_items;			
		}
		
		
		/**
		 * UpdateTab
		 * Public
		 * Insert New Category  or Taxonomy to DB
		 *
		 * @return	void
		 */
		public function updateCategoryTaxonomy($idArticle, $tab_db, $items, $tab_relationship, $id_relationship){
			$tab_items =$this->tabItems($items);
			$rank = 1;
			$id_tab_db = 0;
			
			foreach( $tab_items as $item){
				$sql_query = "SELECT * FROM $tab_db WHERE  entitled = '$item'";
				$sql_result = mysql_query($sql_query); // Run QUERY
				if(mysql_num_rows($sql_result) != 0 ){
					// just update rank
					$sql_row = mysql_fetch_array($sql_result);
					$new_rank =  intval($sql_row['rank'])+1;
					$id_tab_db = $id = $sql_row['id'];
					$sql_query = "UPDATE   $tab_db SET  rank =  '$new_rank' WHERE  id = ".$sql_row['id'].";";												
					$sql_result = mysql_query($sql_query); // Run QUERY
					if (!$sql_result) {
						die('!!! Requête '.$tab_db.' update error : ' . mysql_error());
					}
				}else{
					$sql_query = "INSERT INTO  $tab_db (
									id ,
									entitled ,
									rank ,
									active
									)
									VALUES (
									NULL ,  '".$item."',  '".$rank."',  '1'
									);";
													
					$sql_result = mysql_query($sql_query); // Run QUERY
					$id_tab_db = mysql_insert_id();
					if (!$sql_result) {
						die('!!! Requête '.$tab_db.' insert error : ' . mysql_error());
					}
				}
				
				// Establish the relationship between tables Category and Article
				$query = "INSERT INTO  $tab_relationship (id ,id_articles ,$id_relationship) VALUES (NULL ,  '".$idArticle."',  '".$id_tab_db."');";
				$result = mysql_query($query); // Run QUERY
				
			}
		}
		
		/**
		 * HtmlTagsCategorie
		 * Private
		 * Filtre tags and Categories
		 *
		 * @param	str string
		 * @param	&arg Array
		 * @return	&arg Array
		 */
		private function htmlTagsCategorie($str,&$arg){
			$arg['arg_class'] = explode(' ',$str);
			
			$arg_tag = $arg_cat = array();
			for($j=0; $j< count($arg['arg_class']); $j++){
				//echo( $arg[$j] ).'<br>';
				if(stripos(' '.$arg['arg_class'][$j], 'tag-')){
		//			echo( $arg[$j] ).'<br>';
					array_push ($arg_tag, substr($arg['arg_class'][$j], strpos($arg['arg_class'][$j], 'tag-')+4));
				}
				if(stripos(' '.$arg['arg_class'][$j], 'category-')){
		//			echo( $arg[$j] ).'<br>';
					array_push ($arg_cat, substr($arg['arg_class'][$j], strpos($arg['arg_class'][$j], 'category-')+9));
				}
			}
			$arg['arg_cat'] = $arg_cat;
			$arg['arg_tag'] = $arg_tag;
		}
		
		/**
		 * DirUrl
		 * Public
		 * Creat name of dir
		 *
		 * @param	url string
		 * @return	dirUrl string
		 */
		public function dirUrl($url = false){
			$url = ($url)? $url : $this->url;
			$this->urlToDir($url);
			$this->creatDirUrl();
			return true;
		}
		/**
		 * CreatDirUrl
		 * Public
		 * Creat Dir
		 *
		 * @return	void
		 */
		public function creatDirUrl(){
			//$this->dirMadia = $this->dirUrl.'/'.$this->dirMadia;
			//$this->dirSaving = $this->dirUrl.'/'.$this->dirSaving;
			/*if(!is_dir($this->dirUrl)){
				mkdir($this->dirUrl);				
				mkdir($this->dirMadia);
				mkdir($this->dirSaving);
			}*/
			return true;
		}
		/**
		 * UrlToDir
		 * Private
		 * Convert Url to available name of dir
		 *
		 * @param	url string
		 * @return	dirUrl string
		 */
		private function urlToDir($url){
			$url = substr( $url, strpos($url,$this->needleBegin)+strlen($this->needleBegin));
			$dirUrl = substr( $url, 0, strpos($url,$this->needleEnd));
			$this->dirUrl  = $this->pathDirUrl.$dirUrl;
			return true;
		}
		
		// Methodes Get
		/**
		 * Methodes Get
		 * Public
		 * Get Parametres
		 *
		 * @return	Parametre available type of Parametre
		 */
		public function getParamUrl(){
			return $this->url;
		}
		public function getParamDir(){
			return $this->dirUrl;
		}
		public function getParamWithProxy(){
			return $this->withProxy;
		}
		public function getParamIdBegin(){
			return $this->idBegin;
		}
		// Methodes Set
		/**
		 * Methodes Set
		 * Public
		 * Set Parametres
		 *
		 * @param	Parametre
		 * @return	vaid
		 */
		public function setParamUrl($url){
			$this->url = $url;
			return true;
		}
		public function setParamWithProxy($withProxy){
			$this->withProxy = $withProxy;
			return true;
		}
	}

?>