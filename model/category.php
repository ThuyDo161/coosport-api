<?php
class Category
{
    private $conn;

    // Category properties
    public $category_id;
    public $categoryname;
    public $category_slug;
    public $createddate;
    public $modifieddate;
    public $createdby;
    public $modifiedby;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // create data
    public function create()
    {
        do {
            $query = "SELECT * FROM category WHERE category_slug =:category_slug";
            $stmt = $this->conn->prepare($query);

            $this->category_slug = htmlspecialchars(strip_tags($this->category_slug));

            $stmt->bindParam(':category_slug', $this->category_slug);
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0) {
                $slug = explode('-', $this->category_slug);
                $this->category_slug = $slug[0] . '-' . mt_rand();
            }
        } while ($num > 0);
        $query = "INSERT INTO category
         SET categoryname=:categoryname, category_slug=:category_slug, createddate = current_timestamp()";
        $stmt = $this->conn->prepare($query);

        // clean data
        $this->categoryname = htmlspecialchars(strip_tags($this->categoryname));
        $this->category_slug = htmlspecialchars(strip_tags($this->category_slug));

        if ($this->categoryname == "" || $this->category_slug == "") {
            return false;
        }

        // bind data 
        $stmt->bindParam(':categoryname', $this->categoryname);
        $stmt->bindParam(':category_slug', $this->category_slug);

        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
    // read data
    public function read()
    {
        $query = "SELECT category.category_id, `categoryname`, `category_slug`, category.createddate, category.modifieddate, COUNT(product.product_id) AS 'product_quantity' 
        FROM category LEFT JOIN product ON category.category_id=product.category_id GROUP BY category.category_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    // update data
    public function update()
    {
        do {
            $query = "SELECT * FROM category
             WHERE category_slug =:category_slug AND category
            .category_id!=:category_id";
            $stmt = $this->conn->prepare($query);

            $this->category_slug = htmlspecialchars(strip_tags($this->category_slug));

            $stmt->bindParam(':category_slug', $this->category_slug);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0) {
                $slug = explode('-', $this->category_slug);
                $this->category_slug = $slug[0] . '-' . mt_rand();
            }
        } while ($num > 0);
        $query = "UPDATE category
         SET categoryname=:categoryname, category_slug=:category_slug, modifieddate = current_timestamp()
         WHERE category_id=:category_id";
        $stmt = $this->conn->prepare($query);

        // clean data
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->categoryname = htmlspecialchars(strip_tags($this->categoryname));
        $this->category_slug = htmlspecialchars(strip_tags($this->category_slug));

        if ($this->category_id == "" || $this->category_slug == "") {
            return false;
        }

        // bind data 
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':categoryname', $this->categoryname);
        $stmt->bindParam(':category_slug', $this->category_slug);

        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
    // delete data
    public function delete()
    {
        $query = "DELETE FROM category
         WHERE category_id=:category_id";
        $stmt = $this->conn->prepare($query);
        // bind data 
        if (!$this->category_id) {
            return false;
        }
        $stmt->bindParam(':category_id', $this->category_id);
        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
}
