<?php
class Receipt
{
    private $conn;

    // HoaDonNhap properties
    public $receipt_id;
    public $receipt_date;
    public $user_id;
    public $status;
    public $totalprice;
    public $supplier_id;
    public $modifieddate;

    // ChiTiet properties
    public $product_id;
    public $quantity;
    public $name;
    public $supplier_name;

    public $items;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        if (!$this->user_id) {
            return 201;
        }

        $query = "INSERT INTO receipt SET user_id =:user_id, receipt_date = current_timestamp(), 
            supplier_id =:supplier_id";
        $sql = array();
        foreach ($this->items as $item) {
            $sql[] = '(LAST_INSERT_ID(),"' . $item->id . '", ' . $item->quantity . ')';
        }
        $query2 = "INSERT INTO product_receipt (receipt_id, product_id, quantity) VALUES " . implode(',', $sql);
        $stmt = $this->conn->prepare($query);
        $stmt2 = $this->conn->prepare($query2);

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':supplier_id', $this->supplier_id);

        if ($stmt->execute() &&  $stmt2->execute()) {
            return 200;
        } else return 201;
    }

    public function read()
    {
        $query = "SELECT * FROM receipt, users, supplier WHERE receipt.user_id=users.users_id AND receipt.supplier_id = supplier.supplier_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function detail($id)
    {
        $query = "SELECT * FROM product_receipt, product 
        WHERE product_receipt.product_id=product.product_id AND product_receipt.receipt_id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt;
    }

    public function update()
    {
        if ($this->items) {
            $query2 = "DELETE FROM product_receipt
         WHERE receipt_id=:receipt_id";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(':receipt_id', $this->receipt_id);

            $sql = array();
            foreach ($this->items as $item) {
                $sql[] = '("' . $this->receipt_id . '","' . $item->id . '", ' . $item->quantity . ')';
            }
            $query3 = "INSERT INTO product_receipt (receipt_id, product_id, quantity) VALUES " . implode(',', $sql);
            $stmt3 = $this->conn->prepare($query3);
            $stmt2->execute();
            $stmt3->execute();
        }
        $query = "UPDATE receipt set modifieddate = current_timestamp(), 
        supplier_id =:supplier_id 
        WHERE receipt_id = :receipt_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':supplier_id', $this->supplier_id);
        $stmt->bindParam(':receipt_id', $this->receipt_id);

        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM receipt
         WHERE receipt_id=:receipt_id";
        $query2 = "DELETE FROM product_receipt
         WHERE receipt_id=:receipt_id";
        $stmt = $this->conn->prepare($query);
        $stmt2 = $this->conn->prepare($query2);
        // bind data 
        if (!$this->receipt_id) {
            return false;
        }
        $stmt->bindParam(':receipt_id', $this->receipt_id);
        $stmt2->bindParam(':receipt_id', $this->receipt_id);
        if ($stmt2->execute() && $stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }

    public function Spending($year)
    {
        $query = "SELECT 
            SUM(IF(MONTH(receipt_date)=1,(totalprice),0)) as Thang1, 
            SUM(IF(MONTH(receipt_date)=2,(totalprice),0)) as Thang2, 
            SUM(IF(MONTH(receipt_date)=3,(totalprice),0)) as Thang3, 
            SUM(IF(MONTH(receipt_date)=4,(totalprice),0)) as Thang4, 
            SUM(IF(MONTH(receipt_date)=5,(totalprice),0)) as Thang5, 
            SUM(IF(MONTH(receipt_date)=6,(totalprice),0)) as Thang6, 
            SUM(IF(MONTH(receipt_date)=7,(totalprice),0)) as Thang7, 
            SUM(IF(MONTH(receipt_date)=8,(totalprice),0)) as Thang8, 
            SUM(IF(MONTH(receipt_date)=9,(totalprice),0)) as Thang9, 
            SUM(IF(MONTH(receipt_date)=10,(totalprice),0)) as Thang10, 
            SUM(IF(MONTH(receipt_date)=11,(totalprice),0)) as Thang11, 
            SUM(IF(MONTH(receipt_date)=12,(totalprice),0)) as Thang12 
            from receipt WHERE YEAR(receipt_date)=:year";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':year', $year);
        $stmt->execute();

        return $stmt;
    }
}
