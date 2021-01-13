<?php


class database
{
    private $dbName="task",$dbHost="localhost",$dbUser="root",$dbPass="",$db;

    public function __construct()
    {

        try {
            $this->db = new PDO("mysql:host=$this->dbHost;dbname=$this->dbName", $this->dbUser, $this->dbPass);
            $this->db->query("SET CHARACTER SET utf8");
            $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
            $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,true);
            $this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e) {

            echo json_encode(array("status"=>"Database Error","message"=>$e->getMessage()));
        }
    }

    public function connection(){
        return $this->db;
    }

}