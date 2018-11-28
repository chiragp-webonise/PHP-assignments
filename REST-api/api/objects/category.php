<?php
class Category{
 
    // database connection and table name
    private $conn;
    private $table_name = "Categories";
 
    // object properties
    public $id;
    public $name;
    public $description;
    public $tax;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // used by select drop-down list
    public function read(){
        //select all data
        $query = "SELECT
                    id, name, description, tax
                FROM
                    " . $this->table_name . "
                ORDER BY
                    name";
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
 
        return $stmt;
    }

     // update the product
     function update(){
    
        // update query
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    name = :name,
                    description = :description,
                    tax= :tax
                WHERE
                    id = :id";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->tax=htmlspecialchars(strip_tags($this->tax));
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind new values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':tax', $this->tax);
        $stmt->bindParam(':id', $this->id);
    
        // execute the query
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
    // delete the product
    function delete(){
        
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id);
        // echo "HERE".$this->id;
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }

     // create product
     function create(){
    
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . " (name,description,tax) VALUES(:Name,:Description,:Tax)";

        echo "HERE".$query;
                    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->tax=htmlspecialchars(strip_tags($this->tax));
    
        // bind values
        $stmt->bindParam(":Name", $this->name);
        $stmt->bindParam(":Description", $this->description);
        $stmt->bindParam(":Tax", $this->tax);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }
}
?>