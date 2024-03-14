<?php

class Category {
    // DB stuff
    private $conn;
    private $table = "categories";

    // Properties
    public $id;
    public $category;

    //constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }


    // READ -------------------------------------------------------------------------------------------------

    public function read() {
    // Create query
    $query = 'SELECT c.id, c.category FROM ' . $this->table . ' c';

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
        c.id,
        c.category
    FROM
        ' . $this->table . ' c
    WHERE
        c.id = ?
    LIMIT 1';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $this->id);

    // Execute query
    $stmt-> execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo json_encode(
            array('message' => "category_id Not Found"));
            exit();
    }
    
    // Set properties
    $this->id = $row ['id'];
    $this->category = $row ['category'];
    }


    // Create -------------------------------------------------------------------------------------------------

    public function create() {
        // Create query
        $query = 'INSERT INTO ' . $this->table . ' 
        (category) VALUES
        (:category)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->category = htmlspecialchars(strip_tags($this->category));

            // Bind data
            $stmt->bindParam(':category', $this->category);

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
            category = :category
         WHERE
            id= :id';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->category = htmlspecialchars(strip_tags($this->category));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':category', $this->category);

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