<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
require '../../vendor/autoload.php';
include_once '../config/database.php';
include_once '../objects/cart.php';
// instantiate database and cart object
try {
    $db=Database::get()->connect();
    echo 'A connection to the PostgreSQL database sever has been established successfully.';
} catch (\PDOException $e) {
    echo $e->getMessage();
}

// initialize object
$cart = new Carts($db);

// set ID property of record to read
$cart->custID = isset($_GET['id']) ? $_GET['id'] : die();

// query carts
$stmt = $cart->getTotalTax();
$num = $stmt->rowCount();
// check if more than 0 record found
if($num>0){
 
    // carts array
    $carts_arr=array();
    $carts_arr["records"]=array();
 
    // retrieve our table contents
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);
 
        $cart_item=array(
            "name" => $name,
            "products_names" => html_entity_decode($products_names),
            "total_tax" => $total_tax                        
            );
 
        array_push($carts_arr["records"], $cart_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show carts data in json format
    echo json_encode($carts_arr);
}else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no carts found
    echo json_encode(
        array("message" => "No carts found.")
    );
}
 
?>