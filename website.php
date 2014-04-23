<?php
	class ListWebSite {
		
		public $tab_list = array(
						'http://mobile.tutsplus.com/tutorials/iphone/uiactionsheet_uiactionsheetdelegate/'
						);
		public $tab_dir = array();
		private $findme_a = '//';
		private $findme_b = '/';
		private $items = array();
		function __construct() {
			$this->parseTabList();
		}
		
		public function parseTabList(){
			$this->tab_dir = array();
			foreach($this->tab_list as $website){
				$website = substr($website, strpos($website, $this->findme_a)+strlen($this->findme_a));
				$website = substr($website, 0 , strpos($website, $this->findme_b));
				array_push($this->tab_dir , $website);
			}	
		}
		public function getTabDir(){
			return $this->tab_dir;
		}
		public function printTabDir($pattern = '<br />'){
			foreach($this->tab_dir as $website){
				echo $website.$pattern;
			}
		}
		public function setItemsToAdd($items){
			$this->items = $items;
		}
		public function addItemsToTabList($items = false){
			$this->items = ($items)? $items : $this->items;
			if(is_array($this->items)){
				foreach($this->items as $item){
					array_push($this->tab_list , $item);
				}
			}else{
				array_push($this->tab_list , $this->items);
			}
			$this->parseTabList();
		}
		
	}
	
	$listWebSite = new ListWebSite();
	echo 'printTabDir :: <br/>';
	$listWebSite->printTabDir();
	echo '<br/>';
	echo 'setItemsToAdd :: ';
	$listWebSite->setItemsToAdd(array(
									  'http://net.tutsplus.com/tutorials/php/how-to-use-selenium-2-with-phpunit/',
									  'http://photo.tutsplus.com/articles/theory/quick-tip-deciding-which-stock-image-library-to-trust-with-your-photos/',
									  'http://webdesign.tutsplus.com/articles/workshops/web-design-workshop-24-julien-lambe/'
									  ));
	echo '<br/>';
	echo 'addItemsToTabList :: ';
	$listWebSite->addItemsToTabList();
	echo '<br/>';
	echo 'printTabDir :: <br/>';
	$listWebSite->printTabDir('---');
	echo '<br/>';

	
?>

