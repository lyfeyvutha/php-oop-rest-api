<?php
// Headers
header('Acces-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Instantiate 
$author = new Author($db);

// Blog author query
$result = $author->read();
// Get row count
$num = $result->rowCount();

// Check if any authors
if($num > 0) {
    // Author array
    $authors_arr = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $authors_item = array(
            'id' => $id,
            'author' => $author
        );

        // Push to "data"
        array_push($authors_arr, $authors_item);
    }

    // Turn to JSON & output
    echo json_encode($authors_arr);
} else {
  // No authors
  echo json_encode(
    array('message' => 'No Post Found')
  );
}