<?php
class Policy
{
    private $conn;

    // Category properties
    public $policy_id;
    public $name;
    public $description;
    public $icon;
    public $createddate;
    public $modifieddate;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = "SELECT * FROM `policy`";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // update data
    public function update()
    {
        $query = "UPDATE tkhachhang SET TenKH=:TenKH, DiaChi=:DiaChi, DienThoai=:DienThoai, NgaySinh=:NgaySinh
         WHERE MaKH=:MaKH";
        $stmt = $this->conn->prepare($query);

        // clean data
        $this->MaKH = htmlspecialchars(strip_tags($this->MaKH));
        $this->TenKH = htmlspecialchars(strip_tags($this->TenKH));
        $this->DiaChi = htmlspecialchars(strip_tags($this->DiaChi));
        $this->DienThoai = htmlspecialchars(strip_tags($this->DienThoai));
        $this->NgaySinh = htmlspecialchars(strip_tags($this->NgaySinh));

        if ($this->MaKH == "" || $this->DiaChi == "" || $this->DienThoai == "") {
            return false;
        }

        // bind data 
        $stmt->bindParam(':MaKH', $this->MaKH);
        $stmt->bindParam(':TenKH', $this->TenKH);
        $stmt->bindParam(':DiaChi', $this->DiaChi);
        $stmt->bindParam(':DienThoai', $this->DienThoai);
        $stmt->bindParam(':NgaySinh', $this->NgaySinh);

        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
    // delete data
    public function delete()
    {
        $query = "DELETE FROM tkhachhang WHERE MaKH=:MaKH";
        $stmt = $this->conn->prepare($query);
        // bind data 
        if (!$this->MaKH) {
            return false;
        }
        $stmt->bindParam(':MaKH', $this->MaKH);
        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
}
