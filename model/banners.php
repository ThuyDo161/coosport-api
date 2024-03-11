<?php
class Banner
{
    private $conn;

    // banner properties
    public $id;
    public $title;
    public $img;
    public $is_active;
    public $created_date;
    public $updated_date;
    public $fileUpload;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->fileUpload = null;
    }

    public function read()
    {
        $query = "SELECT id, title, img, is_active, created_date, updated_date 
        FROM banners";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // create data
    public function create()
    {
        $query = "INSERT INTO banners SET title=:title, img=:img, is_active=:is_active";
        $stmt = $this->conn->prepare($query);

        if (!$this->title || (!$this->img && !$this->fileUpload)) {
            return false;
        }

        // bind data 
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':img', $this->img);
        $stmt->bindParam(':is_active', $this->is_active);

        if ($this->fileUpload) {
            $img = $this->fileUpload;
            $DIR = '../../Images/banner/';
            if (!is_dir($DIR)) {
                mkdir($DIR, 0777, true);
            }
            $file_chunks = explode(";base64,", $img->file);
            $base64Img = base64_decode($file_chunks[1]);

            $path = 'http://' . $_SERVER['HTTP_HOST'] . '/php/coosport-api/Images/banner/' . $img->name;
            $file = $DIR . $img->name;
            if (file_put_contents($file, $base64Img)) {
                $stmt->bindParam(':img', $path);
            };
        }

        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }

    // update data
    public function update()
    {
        $query = "UPDATE banners SET title=:title, img=:img, is_active=:is_active, updated_date = current_timestamp() WHERE id=:id";
        $stmt = $this->conn->prepare($query);

        if (!$this->id || !$this->title || !$this->img) {
            return false;
        }

        // bind data 
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':img', $this->img);
        $stmt->bindParam(':is_active', $this->is_active);

        if ($this->fileUpload) {
            $img = $this->fileUpload;
            $DIR = '../../Images/banner/';
            if (!is_dir($DIR)) {
                mkdir($DIR, 0777, true);
            }
            $file_chunks = explode(";base64,", $img->file);
            $base64Img = base64_decode($file_chunks[1]);

            $path = 'http://' . $_SERVER['HTTP_HOST'] . '/php/coosport-api/Images/banner/' . $img->name;
            $file = $DIR . $img->name;
            if (file_put_contents($file, $base64Img)) {
                $stmt->bindParam(':img', $path);
            };
        }

        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
    // delete data
    public function delete()
    {
        $query = "DELETE FROM banners WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        // bind data 
        if (!$this->id) {
            return false;
        }
        $stmt->bindParam(':id', $this->id);
        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
}
