<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object file
require '../../vendor/autoload.php';
include_once '../config/database.php';
include_once '../objects/cart.php';
 
// get database connection
try {
    $db=Database::get()->connect();
    echo 'A connection to the PostgreSQL database sever has been established successfully.';
} catch (\PDOException $e) {
    echo $e->getMessage();
}
 
// prepare cart object
$cart = new Carts($db);
 
// get cart id
$data = json_decode(file_get_contents("php://input"));
 
// set cart id to be deleted
$cart->custID = $data->custid;

// delete the cart
if($cart->deleteCart()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "cart was deleted."));
}
 
// if unable to delete the cart
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to delete cart."));
}
?>