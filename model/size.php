<?php
class Size
{
    private $conn;

    // Category properties
    public $size_id;
    public $sizename;
    public $createddate;
    public $modifieddate;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // create data
    public function create()
    {
        $query = "INSERT INTO size SET sizename=:sizename, createddate = current_timestamp()";
        $stmt = $this->conn->prepare($query);

        // clean data
        $this->sizename = htmlspecialchars(strip_tags($this->sizename));

        if ($this->sizename == "") {
            return false;
        }

        // bind data 
        $stmt->bindParam(':sizename', $this->sizename);

        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }

    public function read()
    {
        $query = "SELECT size.size_id, `sizename`, size.createddate, size.modifieddate, COUNT(product.product_id) AS 'product_quantity' 
        FROM size LEFT JOIN product ON size.size_id = product.size GROUP BY size.size_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // update data
    public function update()
    {
        $query = "UPDATE size SET sizename=:sizename, modifieddate = current_timestamp() WHERE size_id=:size_id";
        $stmt = $this->conn->prepare($query);

        // clean data
        $this->size_id = htmlspecialchars(strip_tags($this->size_id));
        $this->sizename = htmlspecialchars(strip_tags($this->sizename));

        if ($this->size_id == "" || $this->sizename == "") {
            return false;
        }

        // bind data 
        $stmt->bindParam(':size_id', $this->size_id);
        $stmt->bindParam(':sizename', $this->sizename);

        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
    // delete data
    public function delete()
    {
        $query = "DELETE FROM size WHERE size_id=:size_id";
        $stmt = $this->conn->prepare($query);
        // bind data 
        if (!$this->size_id) {
            return false;
        }
        $stmt->bindParam(':size_id', $this->size_id);
        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
}
