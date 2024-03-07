<?php
class Brand
{
    private $conn;

    // brand properties
    public $brand_id;
    public $brandname;
    public $brand_slug;
    public $createddate;
    public $modifieddate;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // create data
    public function create()
    {
        do {
            $query = "SELECT * FROM brand WHERE brand_slug =:brand_slug";
            $stmt = $this->conn->prepare($query);

            $this->brand_slug = htmlspecialchars(strip_tags($this->brand_slug));

            $stmt->bindParam(':brand_slug', $this->brand_slug);
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0) {
                $slug = explode('-', $this->brand_slug);
                $this->brand_slug = $slug[0] . '-' . mt_rand();
            }
        } while ($num > 0);
        $query = "INSERT INTO brand
         SET brandname=:brandname, brand_slug=:brand_slug, createddate = current_timestamp()";
        $stmt = $this->conn->prepare($query);

        // clean data
        $this->brandname = htmlspecialchars(strip_tags($this->brandname));
        $this->brand_slug = htmlspecialchars(strip_tags($this->brand_slug));

        if ($this->brandname == "" || $this->brand_slug == "") {
            return false;
        }

        // bind data 
        $stmt->bindParam(':brandname', $this->brandname);
        $stmt->bindParam(':brand_slug', $this->brand_slug);

        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }

    public function read()
    {
        $query = "SELECT brand.brand_id, `brandname`, `brand_slug`, brand.createddate, brand.modifieddate, COUNT(product.product_id) AS 'product_quantity' 
        FROM brand LEFT JOIN product ON brand.brand_id = product.brand_id GROUP BY brand.brand_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // update data
    public function update()
    {
        do {
            $query = "SELECT * FROM brand
             WHERE brand_slug =:brand_slug AND brand
            .brand_id!=:brand_id";
            $stmt = $this->conn->prepare($query);

            $this->brand_slug = htmlspecialchars(strip_tags($this->brand_slug));

            $stmt->bindParam(':brand_slug', $this->brand_slug);
            $stmt->bindParam(':brand_id', $this->brand_id);
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0) {
                $slug = explode('-', $this->brand_slug);
                $this->brand_slug = $slug[0] . '-' . mt_rand();
            }
        } while ($num > 0);
        $query = "UPDATE brand
         SET brandname=:brandname, brand_slug=:brand_slug, modifieddate = current_timestamp()
         WHERE brand_id=:brand_id";
        $stmt = $this->conn->prepare($query);

        // clean data
        $this->brand_id = htmlspecialchars(strip_tags($this->brand_id));
        $this->brandname = htmlspecialchars(strip_tags($this->brandname));
        $this->brand_slug = htmlspecialchars(strip_tags($this->brand_slug));

        if ($this->brand_id == "" || $this->brand_slug == "") {
            return false;
        }

        // bind data 
        $stmt->bindParam(':brand_id', $this->brand_id);
        $stmt->bindParam(':brandname', $this->brandname);
        $stmt->bindParam(':brand_slug', $this->brand_slug);

        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
    // delete data
    public function delete()
    {
        $query = "DELETE FROM brand
         WHERE brand_id=:brand_id";
        $stmt = $this->conn->prepare($query);
        // bind data 
        if (!$this->brand_id) {
            return false;
        }
        $stmt->bindParam(':brand_id', $this->brand_id);
        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
}
