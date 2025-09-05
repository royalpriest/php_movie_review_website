<?php
// define a class named database
class Database{
    // declare private properties for the database conection
    private $host = '172.31.22.43';// add your ip host here
    private $db_name = 'Royalpriest200639398'; // your database name
    private $username =  'Royalpriest200639398';// add your username
    private $password = 'Hf4sb4GEZc'; // add your password
    // create a public property to hols the connection object
    public $conn;
    //create a method  our  database connection
    public function connect(){
        $this->conn = null;
        try{
            // attempt to create a new PDO (PHP Data object)
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}",
                $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }catch (PDOException $e){
            // if an error occurs display a connection error message
            echo "Connection Error: " . $e->getMessage();
        }
        // return the connection object
        return $this->conn;
    }
}
?>