<?php
class Proxy {

	/*
	|
	|	Public Attributs
	|
	*/
	public $authProxy = 'anisabid:anisabid';
	public $p_method = 'GET';
	public $p_proxy = 'tcp://192.168.2.102:8080';
	public $p_request_fulluri = true;
	public $p_header = 'Proxy-Authorization: Basic';
	public $ctx = '';
	
	function __construct() {
		// Encodage de l'autentification
		$authProxy = base64_encode($this->authProxy);
		// Création des options de la requête
		$opts = array(
			'http' => array (
				'method'=> $this->p_method,
				'proxy'=> $this->p_proxy,
				'request_fulluri' => $this->p_request_fulluri,
				'header'=> $this->p_header." ".$authProxy
			),
			'https' => array (
				'method'=> $this->p_method,
				'proxy'=> $this->p_proxy,
				'request_fulluri' => $this->p_request_fulluri,
				'header'=> $this->p_header." ".$authProxy
			)
		);
		// Création du contexte de transaction
		$this->ctx = stream_context_create($opts);
	}
	
	// Methodes Get
	/**
	 * Methodes Get
	 * Public
	 * Get Parametres
	 *
	 * @return	Parametre available type of Parametre
	 */
	public function getParamCtx(){
		return $this->ctx;
	}
}
?>