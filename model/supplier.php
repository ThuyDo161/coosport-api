<?php
class Supplier
{
    private $conn;

    // supplier properties
    public $supplier_id;
    public $supplier_name;
    public $supplier_address;
    public $supplier_tel;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // create data
    public function create()
    {
        $query = "INSERT INTO supplier
         SET supplier_name=:supplier_name, supplier_address=:supplier_address, supplier_tel=:supplier_tel";
        $stmt = $this->conn->prepare($query);

        // clean data
        $this->supplier_name = htmlspecialchars(strip_tags($this->supplier_name));
        $this->supplier_address = htmlspecialchars(strip_tags($this->supplier_address));
        $this->supplier_tel = htmlspecialchars(strip_tags($this->supplier_tel));

        if ($this->supplier_name == "" || $this->supplier_address == "" || $this->supplier_tel == "") {
            return false;
        }

        // bind data 
        $stmt->bindParam(':supplier_name', $this->supplier_name);
        $stmt->bindParam(':supplier_address', $this->supplier_address);
        $stmt->bindParam(':supplier_tel', $this->supplier_tel);

        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }

    public function read()
    {
        $query = "SELECT supplier.supplier_id, supplier_name, supplier_address, supplier_tel  
        FROM supplier";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // update data
    public function update()
    {
        $query = "UPDATE supplier
         SET supplier_name=:supplier_name, supplier_address=:supplier_address, supplier_tel=:supplier_tel
         WHERE supplier_id=:supplier_id";
        $stmt = $this->conn->prepare($query);

        // clean data
        $this->supplier_id = htmlspecialchars(strip_tags($this->supplier_id));
        $this->supplier_name = htmlspecialchars(strip_tags($this->supplier_name));
        $this->supplier_address = htmlspecialchars(strip_tags($this->supplier_address));
        $this->supplier_tel = htmlspecialchars(strip_tags($this->supplier_tel));

        if ($this->supplier_id == "" || $this->supplier_address == "" || $this->supplier_tel == "") {
            return false;
        }

        // bind data 
        $stmt->bindParam(':supplier_id', $this->supplier_id);
        $stmt->bindParam(':supplier_name', $this->supplier_name);
        $stmt->bindParam(':supplier_address', $this->supplier_address);
        $stmt->bindParam(':supplier_tel', $this->supplier_tel);

        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
    // delete data
    public function delete()
    {
        $query = "DELETE FROM supplier
         WHERE supplier_id=:supplier_id";
        $stmt = $this->conn->prepare($query);
        // bind data 
        if (!$this->supplier_id) {
            return false;
        }
        $stmt->bindParam(':supplier_id', $this->supplier_id);
        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
}
