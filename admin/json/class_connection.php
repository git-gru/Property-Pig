<?php

class DB_Connection {
    public $dbh;
	function __construct(){
        $hostname = 'localhost';
        $username = 'pp_j9f349imc9';
        $password = '~;lWBT35GD;{';
        $dbname     = 'pp_0cd249u34';
       
        try {
            $this->dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$username,$password);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->dbh -> exec('SET NAMES utf8');
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
	}
}

?>
