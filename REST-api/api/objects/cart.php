<?php
class Carts{
 
    // database connection and table name
    private $conn;
    private $table_name = "carts";
 
    // object properties
    public $customerName;
    public $productName;
    public $total;
    public $totalDiscount;
    public $totalWithDiscount;
    public $totalTax;
    public $totalWithTax;
    public $grandTotal;
    public $custID;
    public $prodID;
 
    public function __construct($db){
        $this->conn = $db;
    }
 
    // used by select drop-down list
    public function read(){
        //select all data
        $query = "SELECT cust.name,string_agg(p.name,',') as products_names,
                    SUM(p.price) as total,
                    SUM((price-(p.price*p.discount)/100)+((p.price-(p.price*p.discount)/100)*cat.tax)/100) as grand_total,
                    SUM((price-(p.price*p.discount)/100)+((p.price-(p.price*p.discount)/100)*cat.tax)/100) as total_with_tax,
                    SUM(((price-(p.price*p.discount)/100)*cat.tax)/100) total_tax,SUM((p.price*p.discount)/100) as total_discount,
                    SUM(price-(p.price*p.discount)/100) as total_with_discount FROM carts c 
                    INNER JOIN Customers cust ON cust.id=c.cutomer_id 
                    INNER JOIN Products p ON p.id=c.product_id 
                    INNER JOIN Categories cat ON cat.id=p.category_id 
                    GROUP BY cust.id";
 
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
 
        return $stmt;
    }

     public function getTotal(){

        $query = "SELECT cust.name,string_agg(p.name,',') as products_names,
                    SUM(p.price) as total 
                    FROM carts c 
                    INNER JOIN Customers cust ON cust.id=c.cutomer_id 
                    INNER JOIN Products p ON p.id=c.product_id 
                    INNER JOIN Categories cat ON cat.id=p.category_id 
                    WHERE cust.id= ? 
                    GROUP BY cust.id";
 
        $stmt = $this->conn->prepare( $query );

        // bind id of product to be updated
        $stmt->bindParam(1, $this->custID);

        $stmt->execute();
 
        return $stmt;
    }

    public function getTotalDiscount(){

        $query = "SELECT cust.name,string_agg(p.name,',') as products_names,
                    SUM((p.price*p.discount)/100) as total_discount  
                    FROM carts c 
                    INNER JOIN Customers cust ON cust.id=c.cutomer_id 
                    INNER JOIN Products p ON p.id=c.product_id 
                    INNER JOIN Categories cat ON cat.id=p.category_id 
                    WHERE cust.id= ? 
                    GROUP BY cust.id";
 
        $stmt = $this->conn->prepare( $query );

        // bind id of product to be updated
        $stmt->bindParam(1, $this->custID);

        $stmt->execute();
 
        return $stmt;
    }

    public function getTotalTax(){

        $query = "SELECT cust.name,string_agg(p.name,',') as products_names,
                    SUM(((price-(p.price*p.discount)/100)*cat.tax)/100) total_tax   
                    FROM carts c 
                    INNER JOIN Customers cust ON cust.id=c.cutomer_id 
                    INNER JOIN Products p ON p.id=c.product_id 
                    INNER JOIN Categories cat ON cat.id=p.category_id 
                    WHERE cust.id= ? 
                    GROUP BY cust.id";
 
        $stmt = $this->conn->prepare( $query );

        // bind id of product to be updated
        $stmt->bindParam(1, $this->custID);

        $stmt->execute();
 
        return $stmt;
    }

    function create(){
    
        // query to insert record
        $query = "INSERT INTO " . $this->table_name . "(product_id,cutomer_id) VALUES(:prodID, :custID)";
                    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->prodID=htmlspecialchars(strip_tags($this->prodID));
        $this->custID=htmlspecialchars(strip_tags($this->custID));
    
        // bind values
        $stmt->bindParam(":custID", $this->custID);
        $stmt->bindParam(":prodID", $this->prodID);
            
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }

    
    function delete(){
        
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE product_id =:product_id AND cutomer_id=:cutomer_id";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind id of record to delete
        $stmt->bindParam(":cutomer_id", $this->custID);
        $stmt->bindParam(":product_id", $this->prodID);
        // echo "HERE".$this->id;
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }

    // delete the product
    function deleteCart(){
        
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE cutomer_id=:cutomer_id";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // bind id of record to delete
        $stmt->bindParam(":cutomer_id", $this->custID);
        // echo "HERE".$this->id;
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
        
    }
}
?>