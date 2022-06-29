<?php
session_start();
class Dbh{
    protected $host;
    protected $username;
    protected $password;
    protected $dbname;

     function __construct(){
        $this->host = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->dbname = "zuriphp";
        
    }

    protected function connect(){
        $connect = new mysqli($this->host, $this->username, $this->password, $this->dbname);
        if(!$connect->connect_errno){
            // echo "error in connection";
        }
        return $connect;    
    }

}
?>