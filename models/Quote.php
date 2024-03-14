<?php

class Quote {
    // DB stuff
    private $conn;
    private $table = "quotes";

    // Properties
    public $id;
    public $quote;
    public $author_id;
    public $category_id;

    public $author;
    public $category;

    //constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }


    // READ -------------------------------------------------------------------------------------------------

    public function read() {
    // Create query
    $query = 'SELECT 
        a.author as author,
        c.category as category, 
        q.id,
        q.quote
    FROM ' . $this->table . ' q 
        LEFT JOIN authors as a ON q.author_id = q.id 
        LEFT JOIN categories as c ON q.category_id = q.id';

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
        q.id,
        q.quote,
        a.author as author,
        c.category as category
    FROM
        ' . $this->table . ' q
    LEFT JOIN authors as a ON q.author_id = a.id
    LEFT JOIN categories as c ON q.category_id = c.id
    WHERE
        q.id = ?
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
            array('message' => "No Quotes Found"));
            exit();
    }
    
    // Set properties
    $this->id = $row ['id'];
    $this->quote = $row ['quote'];
    $this->author = $row ['author'];     
    $this->category = $row ['category']; 

    }


    // Create -------------------------------------------------------------------------------------------------

    public function create() {
        // Create query
        $query = 'INSERT INTO ' . $this->table . ' 
        (quote, author_id, category_id) VALUES
        (:quote, :author_id, :category_id)';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));

            // Bind data
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);

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
            quote = :quote,
            author_id = :author_id,
            category_id = :category_id
        WHERE
            id = :id';

            

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->id = htmlspecialchars(strip_tags($this->id));
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));

            // Bind data
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);

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