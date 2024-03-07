<?php
class Bill
{
    private $conn;

    // HoaDonBan properties
    public $bill_id;
    public $bill_date;
    public $user_id;
    public $status_bill;
    public $totalprice;
    public $name;
    public $tel;
    public $location;
    public $note;
    public $deliverytime;
    public $modifieddate;

    // ChiTiet properties
    public $product_id;
    public $quantity;
    public $user_name;

    public $items;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        if (!$this->user_id) {
            $query = "INSERT INTO users SET name=:name, address =:address, user_tel =:user_tel, user_code = 'guest', status = 2, createddate = current_timestamp(), role_id = 3";
            $query2 = "INSERT INTO bill SET user_id = LAST_INSERT_ID(), bill_date = current_timestamp(), 
            status_bill = 0";
            $sql = array();
            foreach ($this->items as $item) {
                $sql[] = '(LAST_INSERT_ID(),"' . $item->id . '", ' . $item->quantity . ')';
            }
            $query3 = "INSERT INTO product_bill (bill_id, product_id, quantity) VALUES " . implode(',', $sql);
            $stmt = $this->conn->prepare($query);
            $stmt2 = $this->conn->prepare($query2);
            $stmt3 = $this->conn->prepare($query3);

            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':address', $this->location);
            $stmt->bindParam(':user_tel', $this->tel);

            if ($stmt->execute() &&  $stmt2->execute() && $stmt3->execute()) {
                return 200;
            } else return 201;
        }

        if ($this->name || $this->location || $this->tel) {
            $note = ($this->name ? "Tên người nhận: " . $this->name . ", " : '') .
                ($this->location ? "Địa chỉ người nhận: " . $this->location . ", " : '') .
                ($this->tel ? "Điện thoại người nhận: " . $this->tel : '');

            $query = "INSERT INTO bill SET user_id =:user_id, bill_date = current_timestamp(), 
            status_bill = 0, note =:note";
            $sql = array();
            foreach ($this->items as $item) {
                $sql[] = '(LAST_INSERT_ID(),"' . $item->id . '", ' . $item->quantity . ')';
            }
            $query2 = "INSERT INTO product_bill (bill_id, product_id, quantity) VALUES " . implode(',', $sql);
            $stmt = $this->conn->prepare($query);
            $stmt2 = $this->conn->prepare($query2);

            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':note', $note);

            if ($stmt->execute() &&  $stmt2->execute()) {
                return 200;
            } else return 201;
        }

        $query = "INSERT INTO bill SET user_id =:user_id, bill_date = current_timestamp(), 
            status_bill = 0";
        $sql = array();
        foreach ($this->items as $item) {
            $sql[] = '(LAST_INSERT_ID(),"' . $item->id . '", ' . $item->quantity . ')';
        }
        $query2 = "INSERT INTO product_bill (bill_id, product_id, quantity) VALUES " . implode(',', $sql);
        $stmt = $this->conn->prepare($query);
        $stmt2 = $this->conn->prepare($query2);

        $stmt->bindParam(':user_id', $this->user_id);

        if ($stmt->execute() &&  $stmt2->execute()) {
            return 200;
        } else return 201;
    }

    public function read()
    {
        $query = "SELECT * FROM `bill` 
        WHERE user_id=:user_id AND (status_bill = 0 OR status_bill = 1 OR status_bill = 2)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        return $stmt;
    }
    public function readAll()
    {
        $query = "SELECT * FROM bill, users WHERE bill.user_id=users.users_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function detail($id)
    {
        $query = "SELECT * FROM product_bill, product, size, color 
        WHERE product_bill.product_id=product.product_id AND product.color = color.color_id 
        AND product.size = size.size_id AND product_bill.bill_id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt;
    }

    public function update($cancelOder = null)
    {
        $query = "UPDATE bill set deliverytime = :deliverytime, status_bill= :status_bill 
        WHERE bill_id = :bill_id";
        if ($cancelOder) {
            $query = "UPDATE bill set status_bill = 3
        WHERE bill_id = :bill_id";
        }
        $stmt = $this->conn->prepare($query);
        if ($cancelOder === null) {
            $stmt->bindParam(':deliverytime', $this->deliverytime);
            $stmt->bindParam(':status_bill', $this->status_bill);
        }
        $stmt->bindParam(':bill_id', $this->bill_id);

        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }

    public function delete()
    {
        $query = "DELETE FROM bill
         WHERE bill_id=:bill_id";
        $query2 = "DELETE FROM product_bill
         WHERE bill_id=:bill_id";
        $stmt = $this->conn->prepare($query);
        $stmt2 = $this->conn->prepare($query2);
        // bind data 
        if (!$this->bill_id) {
            return false;
        }
        $stmt->bindParam(':bill_id', $this->bill_id);
        $stmt2->bindParam(':bill_id', $this->bill_id);
        if ($stmt2->execute() && $stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }

    public function Turnover($year)
    {
        $query = "SELECT 
            SUM(IF(MONTH(bill_date)=1,(totalprice),0)) as Thang1, 
            SUM(IF(MONTH(bill_date)=2,(totalprice),0)) as Thang2, 
            SUM(IF(MONTH(bill_date)=3,(totalprice),0)) as Thang3, 
            SUM(IF(MONTH(bill_date)=4,(totalprice),0)) as Thang4, 
            SUM(IF(MONTH(bill_date)=5,(totalprice),0)) as Thang5, 
            SUM(IF(MONTH(bill_date)=6,(totalprice),0)) as Thang6, 
            SUM(IF(MONTH(bill_date)=7,(totalprice),0)) as Thang7, 
            SUM(IF(MONTH(bill_date)=8,(totalprice),0)) as Thang8, 
            SUM(IF(MONTH(bill_date)=9,(totalprice),0)) as Thang9, 
            SUM(IF(MONTH(bill_date)=10,(totalprice),0)) as Thang10, 
            SUM(IF(MONTH(bill_date)=11,(totalprice),0)) as Thang11, 
            SUM(IF(MONTH(bill_date)=12,(totalprice),0)) as Thang12 
            from bill WHERE YEAR(bill_date)=:year";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':year', $year);
        $stmt->execute();

        return $stmt;
    }
    public function Turnover2($year)
    {
        $query = "SELECT 
            SUM(IF(MONTH(bill_date)=1,(COUNT(bill_id)),0)) as Thang1, 
            SUM(IF(MONTH(bill_date)=2,(COUNT(bill_id)),0)) as Thang2, 
            SUM(IF(MONTH(bill_date)=3,(COUNT(bill_id)),0)) as Thang3, 
            SUM(IF(MONTH(bill_date)=4,(COUNT(bill_id)),0)) as Thang4, 
            SUM(IF(MONTH(bill_date)=5,(COUNT(bill_id)),0)) as Thang5, 
            SUM(IF(MONTH(bill_date)=6,(COUNT(bill_id)),0)) as Thang6, 
            SUM(IF(MONTH(bill_date)=7,(COUNT(bill_id)),0)) as Thang7, 
            SUM(IF(MONTH(bill_date)=8,(COUNT(bill_id)),0)) as Thang8, 
            SUM(IF(MONTH(bill_date)=9,(COUNT(bill_id)),0)) as Thang9, 
            SUM(IF(MONTH(bill_date)=10,(COUNT(bill_id)),0)) as Thang10, 
            SUM(IF(MONTH(bill_date)=11,(COUNT(bill_id)),0)) as Thang11, 
            SUM(IF(MONTH(bill_date)=12,(COUNT(bill_id)),0)) as Thang12 
            from bill WHERE YEAR(bill_date)=:year";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':year', $year);
        $stmt->execute();

        return $stmt;
    }
}
