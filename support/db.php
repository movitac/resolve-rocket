<?php 
class DB{
    protected $host='localhost';
    protected $user='root';
    protected $password='';
    protected $database='resolve_rocket';

    public $conn;

    public function __construct()
    {
        $this->conn=new mysqli($this->host,$this->user,$this->password,$this->database);
        if($this->conn->errno){
            die('Database error');
        }
    }
}
?>