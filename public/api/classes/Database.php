<?php
class Database{
    
    // CHANGE THE DB INFO ACCORDING TO YOUR DATABASE
    private $db_host = 'localhost';
    private $db_name = 'sab2007_admin';
    private $db_username = 'sab2007_admin';
    private $db_password = '5jHn6s4TL@';
    
    public function dbConnection(){
        
        try{
            $conn = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name,$this->db_username,$this->db_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }
        catch(PDOException $e){
            echo "Connection Loss..."; 
            // echo "Connection error ".$e->getMessage(); 
            exit;
        }
          
    }
}