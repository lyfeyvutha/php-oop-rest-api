<?php

class Author {
    // DB stuff
    private $conn;
    private $table = "authors";

    // Properties
    public $id;
    public $author;

    //constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }


    // READ -------------------------------------------------------------------------------------------------

    public function read() {
    // Create query
    $query = 'SELECT a.id, a.author FROM ' . $this->table . ' a';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Execute query
    $stmt->execute();

    return $stmt;
    }


    // Read Single -------------------------------------------------------------------------------------------

    public function read_single() {
        // Create query
    $query = 'SELECT 
        a.id,
        a.author 
        FROM
        ' . $this->table . ' a
    WHERE a.id = ?
    LIMIT 1 ';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $this->id);

    // Execute query
    $stmt-> execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(
            array('message' => "author_id Not Found"));
            exit();
    }
    
    // Set properties
    $this->id = $row ['id'];
    $this->author = $row ['author'];
    }


    // Create -------------------------------------------------------------------------------------------------

    public function create() {
        // Create query
        $query = 'INSERT INTO ' . $this->table . ' 
        (author) VALUES
        (:author)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->author = htmlspecialchars(strip_tags($this->author));

            // Bind data
            $stmt->bindParam(':author', $this->author);

            // Execute query
            if($stmt->execute()) {
                return true;
            }
            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false; 
    }


    // Update -------------------------------------------------------------------------------------------------

    public function update() {
        // Create query
        $query = 'UPDATE ' . $this->table . ' 
          SET
            id = :id,
            author = :author
        WHERE
            id= :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->author = htmlspecialchars(strip_tags($this->author));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':author', $this->author);

            // Execute query
            if($stmt->execute()) {
                return true;
            }
            // Print error if something goes wrong
            printf("Error: %s.\n", $stmt->error);

            return false; 
    }

    // Delete ---------------------------------------------------------------------------------------------------

    public function delete() {
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if($stmt->execute()) {
            return true;
        }
        
        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}