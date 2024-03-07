<?php
class HeroSlide
{
    private $conn;

    // Category properties
    public $slide_id;
    public $title;
    public $description;
    public $img;
    public $color;
    public $path;
    public $fileUpload;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT * FROM `hero_slide`";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function create()
    {
        $query = "INSERT INTO hero_slide SET title=:title, description=:description, img=:img, color=:color, path=:path";
        $stmt = $this->conn->prepare($query);

        // bind data 
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':img', $this->img);
        $stmt->bindParam(':path', $this->path);
        $stmt->bindParam(':color', $this->color);

        if ($this->fileUpload) {
            $img = $this->fileUpload;
            $DIR = '../../Images/slider/';
            $file_chunks = explode(";base64,", $img->file);
            $base64Img = base64_decode($file_chunks[1]);

            $path = 'http://' . $_SERVER['HTTP_HOST'] . '/php/htshop/Images/slider/' . $img->name;
            $file = $DIR . $img->name;
            if (file_put_contents($file, $base64Img)) {
                $stmt->bindParam(':img', $path);
            };
        }

        if (!$this->title || !$this->description || (!$this->img && !$this->fileUpload) || !$this->path  || !$this->color) {
            return false;
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
        $query = "UPDATE hero_slide SET title=:title, description=:description, img=:img, color=:color, path=:path WHERE slide_id=:slide_id";
        $stmt = $this->conn->prepare($query);

        // bind data 
        $stmt->bindParam(':slide_id', $this->slide_id);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':img', $this->img);
        $stmt->bindParam(':path', $this->path);
        $stmt->bindParam(':color', $this->color);

        if ($this->fileUpload) {
            $img = $this->fileUpload;
            $DIR = '../../Images/slider/';
            $file_chunks = explode(";base64,", $img->file);
            $base64Img = base64_decode($file_chunks[1]);

            $path = 'http://' . $_SERVER['HTTP_HOST'] . '/php/htshop/Images/slider/' . $img->name;
            $file = $DIR . $img->name;
            if (file_put_contents($file, $base64Img)) {
                $stmt->bindParam(':img', $path);
            };
        }

        if (!$this->slide_id || !$this->title || !$this->description || (!$this->img && !$this->fileUpload) || !$this->path  || !$this->color) {
            return false;
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
        $query = "DELETE FROM hero_slide WHERE slide_id=:slide_id";
        $stmt = $this->conn->prepare($query);
        // bind data 
        if (!$this->slide_id) {
            return false;
        }
        $stmt->bindParam(':slide_id', $this->slide_id);
        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
}
