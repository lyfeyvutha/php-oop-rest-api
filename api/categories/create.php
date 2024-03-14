<?php 

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate blog category object
$category = new Category($db);

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->category)) {

    echo json_encode(
        array('message' => "Missing Required Parameters"));
        exit();
    }

$category->category = $data->category;

// Create category
if($category->create()) {
    echo json_encode(
        array('id' => $category->id, 'category' => $category->category)  
    );
} else {
    echo json_encode(
        array('message' => 'Category Not Created')
    );
}