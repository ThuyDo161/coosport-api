<?php
class Color
{
    private $conn;

    // color properties
    public $color_id;
    public $colorname;
    public $color_code;
    public $createddate;
    public $modifieddate;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT color.color_id, `colorname`, color_code, color.createddate, color.modifieddate, COUNT(product.product_id) AS 'product_quantity' 
        FROM color LEFT JOIN product ON color.color_id = product.color GROUP BY color.color_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // create data
    public function create()
    {
        $query = "INSERT INTO color SET colorname=:colorname, color_code=:color_code, createddate = current_timestamp()";
        $stmt = $this->conn->prepare($query);

        // clean data
        $this->colorname = htmlspecialchars(strip_tags($this->colorname));

        if ($this->colorname == "" || $this->color_code == "") {
            return false;
        }

        // bind data 
        $stmt->bindParam(':colorname', $this->colorname);
        $stmt->bindParam(':color_code', $this->color_code);

        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }

    // update data
    public function update()
    {
        $query = "UPDATE color SET colorname=:colorname, color_code=:color_code, modifieddate = current_timestamp() WHERE color_id=:color_id";
        $stmt = $this->conn->prepare($query);

        // clean data
        $this->color_id = htmlspecialchars(strip_tags($this->color_id));
        $this->colorname = htmlspecialchars(strip_tags($this->colorname));

        if ($this->color_id == "" || $this->colorname == "" || !$this->color_code) {
            return false;
        }

        // bind data 
        $stmt->bindParam(':color_id', $this->color_id);
        $stmt->bindParam(':colorname', $this->colorname);
        $stmt->bindParam(':color_code', $this->color_code);

        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
    // delete data
    public function delete()
    {
        $query = "DELETE FROM color WHERE color_id=:color_id";
        $stmt = $this->conn->prepare($query);
        // bind data 
        if (!$this->color_id) {
            return false;
        }
        $stmt->bindParam(':color_id', $this->color_id);
        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
}
