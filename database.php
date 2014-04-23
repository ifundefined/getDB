<?php
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
|				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
| -------------------------------------------------------------------
*/
class Database{
	
	/*
	case 'development':
		$db['default']['hostname'] = "localhost";
		$db['default']['username'] = "root";
		$db['default']['password'] = "";
		$db['default']['database'] = "dbanisabid";
	break;
	case 'testing':
		$db['default']['hostname'] = "mysql5-12.perso";
		$db['default']['username'] = "anisabid_cms";
		$db['default']['password'] = "vDCfzuuE";
		$db['default']['database'] = "anisabid_cms";
	break;
	
	*/
	
	
	private $hostname	= "mysql5-12.perso";
	private $username  	= "anisabid_cms";
	private $password 	= "vDCfzuuE";
	private $database 	= "anisabid_cms";
	private $dbdriver 	= 'mysql';
	
	function __construct() {
        mysql_connect($this->hostname, $this->username, $this->password) or die("Impossible de se connecter : " . mysql_error());
		mysql_select_db($this->database);
    }

}

	
	



/* End of file database.php */
/* Location: ./application/config/database.php */