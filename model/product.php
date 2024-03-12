<?php
class Product
{
    private $conn;

    // Product properties
    public $product_id;
    public $productname;
    public $pricesell;
    public $priceentry;
    public $count;
    public $description;
    public $category_id;
    public $brand_id;
    public $color;
    public $size;
    public $parent_id;
    public $product_slug;
    public $createddate;
    public $modifieddate;
    public $createdby;
    public $modifiedby;

    public $img;
    public $categoryname;
    public $brandname;
    public $colorname;
    public $color_code;
    public $sizename;
    public $children_color;
    public $children_size;

    public $keySearch;
    public $_limit;
    public $_page;
    public $_total_page;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getStart($total_records)
    {
        // // Tìm số record
        // $query = "SELECT COUNT(product_id) as total FROM product,category WHERE product.category_id = category.category_id";
        // $stmt = $this->conn->prepare($query);
        // $stmt->execute();
        // $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // $total_records = $row['total'];

        // TÍNH TOÁN TOTAL_PAGE VÀ START
        // tổng số trang
        if ($this->_limit <= 0) {
            $this->_limit = 1;
        }
        $this->_total_page = ceil($total_records / $this->_limit);

        // Giới hạn current_page trong khoảng 1 đến total_page
        if ($this->_page > $this->_total_page) {
            $this->_page = $this->_total_page;
        } else if ($this->_page < 1) {
            $this->_page = 1;
        }

        // Tìm Start
        $start = ($this->_page - 1) * $this->_limit;
        return $start;
    }
    // Read data
    public function readAll()
    {

        $query = "SELECT product.product_id, product.productname, product.pricesell, product.priceentry, product.count, product.description, product.category_id, product.brand_id, product.color, product.size, product.parent_id, product.product_slug, product.createddate, product.modifieddate, product.createdby, product.modifiedby,category.categoryname, color.colorname, size.sizename, brand.brandname,  GROUP_CONCAT(DISTINCT img.img) AS img, GROUP_CONCAT(DISTINCT p.color_code) as children_color, GROUP_CONCAT(DISTINCT p.sizename) AS
            children_size
                        FROM product LEFT JOIN img ON product.product_id = img.product_id 
                        LEFT JOIN category ON product.category_id = category.category_id
                        LEFT JOIN brand ON product.brand_id = brand.brand_id
                        LEFT JOIN color ON product.color = color.color_id
                        LEFT JOIN size ON product.size = size.size_id
                        LEFT JOIN (SELECT product_id, parent_id, color_code, sizename FROM product, color, size WHERE product.color = color.color_id AND product.size = size.size_id ) p ON product.product_id = p.parent_id
                        GROUP BY product.product_id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if ($this->_page && $this->_limit) {
            $start = $this->getStart($stmt->countRows());

            $query = "SELECT product.product_id, product.productname, product.pricesell, product.priceentry, product.count, product.description, product.category_id, product.brand_id, product.color, product.size, product.parent_id, product.product_slug, product.createddate, product.modifieddate, product.createdby, product.modifiedby,category.categoryname, color.colorname, size.sizename, brand.brandname,  GROUP_CONCAT(DISTINCT img.img) AS img, GROUP_CONCAT(DISTINCT p.colorname) as children_color, GROUP_CONCAT(DISTINCT p.sizename) AS
                children_size
                            FROM product LEFT JOIN img ON product.product_id = img.product_id 
                            LEFT JOIN category ON product.category_id = category.category_id
                            LEFT JOIN brand ON product.brand_id = brand.brand_id
                            LEFT JOIN color ON product.color = color.color_id
                            LEFT JOIN size ON product.size = size.size_id
                            LEFT JOIN (SELECT product_id, parent_id, colorname, sizename FROM product, color, size WHERE product.color = color.color_id AND product.size = size.size_id ) p ON product.product_id = p.parent_id
                            WHERE product.parent_id is null GROUP BY product.product_id 
                LIMIT $start,$this->_limit";


            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        }
        return $stmt;
    }
    // Read data
    public function read()
    {

        $query = "SELECT
        product.product_id,
        product.productname,
        product.pricesell,
        product.priceentry,
        product.count,
        COALESCE(product.count + IFNULL(subquery.total_count, 0), product.count) AS total_count,
        product.description,
        product.category_id,
        product.brand_id,
        product.color,
        product.size,
        product.parent_id,
        product.product_slug,
        product.createddate,
        product.modifieddate,
        product.createdby,
        product.modifiedby,
        category.categoryname,
        color.colorname,
        size.sizename,
        brand.brandname,
        GROUP_CONCAT(DISTINCT img.img) AS img,
        GROUP_CONCAT(DISTINCT child.color) AS children_color,
        GROUP_CONCAT(DISTINCT child.size) AS children_size
      FROM
        product
        LEFT JOIN img ON product.product_id = img.product_id
        LEFT JOIN category ON product.category_id = category.category_id
        LEFT JOIN brand ON product.brand_id = brand.brand_id
        LEFT JOIN color ON product.color = color.color_id
        LEFT JOIN size ON product.size = size.size_id
        LEFT JOIN (
          SELECT
            parent_id,
            SUM(count) AS total_count,
            color_code,
            sizename
          FROM
            product,
            color, 
            size
          WHERE
            product.color = color.color_id AND 
            product.size = size.size_id AND
            parent_id IS NOT NULL
          GROUP BY
            parent_id
        ) subquery ON product.product_id = subquery.parent_id
        LEFT JOIN product AS child ON product.product_id = child.parent_id
      WHERE
        product.parent_id IS NULL
      GROUP BY
        product.product_id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if ($this->_page && $this->_limit) {
            $start = $this->getStart($stmt->countRows());

            $query = "SELECT product.product_id, product.productname, product.pricesell, product.priceentry, product.count, product.description, product.category_id, product.brand_id, product.color, product.size, product.parent_id, product.product_slug, product.createddate, product.modifieddate, product.createdby, product.modifiedby,category.categoryname, color.colorname, size.sizename, brand.brandname,  GROUP_CONCAT(DISTINCT img.img) AS img, GROUP_CONCAT(DISTINCT p.colorname) as children_color, GROUP_CONCAT(DISTINCT p.sizename) AS
                children_size
                            FROM product LEFT JOIN img ON product.product_id = img.product_id 
                            LEFT JOIN category ON product.category_id = category.category_id
                            LEFT JOIN brand ON product.brand_id = brand.brand_id
                            LEFT JOIN color ON product.color = color.color_id
                            LEFT JOIN size ON product.size = size.size_id
                            LEFT JOIN (SELECT product_id, parent_id, colorname, sizename FROM product, color, size WHERE product.color = color.color_id AND product.size = size.size_id ) p ON product.product_id = p.parent_id
                            WHERE product.parent_id is null GROUP BY product.product_id 
                LIMIT $start,$this->_limit";


            $stmt = $this->conn->prepare($query);
            $stmt->execute();
        }
        return $stmt;
    }

    // Read data
    public function readBySlug()
    {

        $query = "SELECT product.product_id, product.productname, product.pricesell, product.priceentry, product.count, product.description, product.category_id, product.brand_id, product.color, product.size, product.parent_id, product.product_slug, product.createddate, product.modifieddate, product.createdby, product.modifiedby,category.categoryname, color.colorname, size.sizename, brand.brandname,  GROUP_CONCAT(DISTINCT img.img) AS img, GROUP_CONCAT(DISTINCT p.colorname) as children_color, GROUP_CONCAT(DISTINCT p.sizename) AS
            children_size
            FROM product LEFT JOIN img ON product.product_id = img.product_id 
            LEFT JOIN category ON product.category_id = category.category_id
            LEFT JOIN brand ON product.brand_id = brand.brand_id
            LEFT JOIN color ON product.color = color.color_id
            LEFT JOIN size ON product.size = size.size_id
            LEFT JOIN (SELECT product_id, parent_id, colorname, sizename FROM product, color, size WHERE product.color = color.color_id AND product.size = size.size_id ) p ON product.product_id = p.parent_id
            WHERE product.parent_id is null AND (category.category_slug = :slug OR brand.brand_slug = :slug) GROUP BY product.product_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':slug', $this->product_slug);
        $stmt->execute();
        // if ($this->_page && $this->_limit) {
        //     $start = $this->getStart($stmt->rowCount());
        //     if ($this->costFrom && $this->costTo) {
        //         $query = "SELECT * FROM product,category WHERE product.category_id = category.category_id
        //         AND product.pricesell Between $this->costFrom and $this->costTo
        //         AND category.TenVanTat = :slug LIMIT $start,$this->_limit";
        //     } else {
        //         $query = "SELECT * FROM product,category WHERE product.category_id = category.category_id 
        //         AND category.TenVanTat = :slug LIMIT $start,$this->_limit";
        //     }
        //     $stmt = $this->conn->prepare($query);
        //     $stmt->bindParam(':slug', $this->TenVanTat);
        //     $stmt->execute();
        // }
        return $stmt;
    }

    // Read data
    public function search()
    {
        $keys = '%' . $this->keySearch . '%';

        $query = "SELECT product.product_id, product.productname, product.pricesell, product.priceentry, product.count, product.description, product.category_id, product.brand_id, product.color, product.size, product.parent_id, product.product_slug, product.createddate, product.modifieddate, product.createdby, product.modifiedby,category.categoryname, color.colorname, size.sizename, brand.brandname,  GROUP_CONCAT(DISTINCT img.img) AS img, GROUP_CONCAT(DISTINCT p.color_code) as children_color, GROUP_CONCAT(DISTINCT p.sizename) AS
            children_size
                        FROM product LEFT JOIN img ON product.product_id = img.product_id 
                        LEFT JOIN category ON product.category_id = category.category_id
                        LEFT JOIN brand ON product.brand_id = brand.brand_id
                        LEFT JOIN color ON product.color = color.color_id
                        LEFT JOIN size ON product.size = size.size_id
                        LEFT JOIN (SELECT product_id, parent_id, color_code, sizename FROM product, color, size WHERE product.color = color.color_id AND product.size = size.size_id ) p ON product.product_id = p.parent_id
                        WHERE product.parent_id is null AND (product.productname LIKE :keys) GROUP BY product.product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':keys', $keys);
        $stmt->execute();
        if ($this->_page && $this->_limit) {
            $start = $this->getStart($stmt->rowCount());
            $query = "SELECT product.product_id, product.productname, product.pricesell, product.priceentry, product.count, product.description, product.category_id, product.brand_id, product.color, product.size, product.parent_id, product.product_slug, product.createddate, product.modifieddate, product.createdby, product.modifiedby,category.categoryname, color.colorname, size.sizename, brand.brandname,  GROUP_CONCAT(DISTINCT img.img) AS img, GROUP_CONCAT(DISTINCT p.color_code) as children_color, GROUP_CONCAT(DISTINCT p.sizename) AS
            children_size
                        FROM product LEFT JOIN img ON product.product_id = img.product_id 
                        LEFT JOIN category ON product.category_id = category.category_id
                        LEFT JOIN brand ON product.brand_id = brand.brand_id
                        LEFT JOIN color ON product.color = color.color_id
                        LEFT JOIN size ON product.size = size.size_id
                        LEFT JOIN (SELECT product_id, parent_id, color_code, sizename FROM product, color, size WHERE product.color = color.color_id AND product.size = size.size_id ) p ON product.product_id = p.parent_id
                        WHERE product.parent_id is null AND (product.productname LIKE :keys OR category.categoryname LIKE :keys) LIMIT $start,$this->_limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':keys', $keys);
            $stmt->execute();
        }
        return $stmt;
    }


    // Show one data
    public function show()
    {
        $query = "SELECT product.product_id, product.productname, product.pricesell, product.priceentry, product.count, product.description, product.category_id, product.brand_id, product.color, product.size, product.parent_id, product.product_slug, product.createddate, product.modifieddate, product.createdby, product.modifiedby,category.categoryname, color.colorname, color.color_code, size.sizename, brand.brandname,  GROUP_CONCAT(DISTINCT img.img) AS img
        FROM product LEFT JOIN img ON product.product_id = img.product_id 
        LEFT JOIN category ON product.category_id = category.category_id
        LEFT JOIN brand ON product.brand_id = brand.brand_id
        LEFT JOIN color ON product.color = color.color_id
        LEFT JOIN size ON product.size = size.size_id
        LEFT JOIN (SELECT product_id, parent_id, colorname, sizename FROM product, color, size WHERE product.color = color.color_id AND product.size = size.size_id ) p ON product.product_id = p.parent_id
        WHERE product.parent_id=:product_id OR product.product_id=:product_id GROUP BY product.product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $this->product_id);
        $stmt->execute();

        return $stmt;
    }

    // create data
    public function create()
    {
        do {
            $query = "SELECT * FROM product WHERE product_slug=:product_slug";
            $stmt = $this->conn->prepare($query);

            $this->product_slug = htmlspecialchars(strip_tags($this->product_slug));

            $stmt->bindParam(':product_slug', $this->product_slug);
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0) {
                $slug = explode('-', $this->product_slug);
                $this->product_slug = $slug[0] . '-' . mt_rand();
            }
        } while ($num > 0);
        $query = "INSERT INTO product SET productname=:productname, pricesell=:pricesell, priceentry=:priceentry,
         description=:description, category_id=:category_id, brand_id=:brand_id, color=:color, size=:size,
         parent_id=:parent_id, product_slug=:product_slug, count=:count, createddate = current_timestamp(), modifieddate = current_timestamp()";
        $stmt = $this->conn->prepare($query);

        if ($this->productname == "" || $this->pricesell == "" || $this->priceentry == "") {
            return false;
        }

        $this->parent_id = $this->parent_id == "" ?  NULL : $this->parent_id;
        $this->product_slug = $this->product_slug == "" ?  NULL : $this->product_slug;
        // bind data 
        $stmt->bindParam(':productname', $this->productname);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':brand_id', $this->brand_id);
        $stmt->bindParam(':pricesell', $this->pricesell);
        $stmt->bindParam(':priceentry', $this->priceentry);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':size', $this->size);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':parent_id', $this->parent_id);
        $stmt->bindParam(':product_slug', $this->product_slug);
        $stmt->bindParam(':count', $this->count);

        if ($this->img) {
            $DIR = '../../Images/product/';
            if (!is_dir($DIR)) {
                mkdir($DIR, 0777, true);
            }
            foreach ($this->img as $img) {
                $file_chunks = explode(";base64,", $img->file);
                $base64Img = base64_decode($file_chunks[1]);

                $path = 'https://' . $_SERVER['HTTP_HOST'] . '/coosport-server/Images/product/' . $img->name;
                $file = $DIR . $img->name;
                if (file_put_contents($file, $base64Img)) {
                    $sql[] = '("' . $path . '",LAST_INSERT_ID())';
                };
            }
            $query2 = "INSERT INTO img (`img`, `product_id`) VALUES " . implode(',', $sql);
            $stmt2 = $this->conn->prepare($query2);;
            if ($stmt->execute() && $stmt2->execute()) {
                return true;
            } else {
                printf('Error %s \n', $stmt->error || $stmt2->error);
                return false;
            }
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
        do {
            $query = "SELECT * FROM product WHERE product_slug=:product_slug AND product_id != :product_id";
            $stmt = $this->conn->prepare($query);

            $this->product_slug = htmlspecialchars(strip_tags($this->product_slug));

            $stmt->bindParam(':product_slug', $this->product_slug);
            $stmt->bindParam(':product_id', $this->product_id);

            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0) {
                $slug = explode('-', $this->product_slug);
                $this->product_slug = $slug[0] . '-' . mt_rand();
            }
        } while ($num > 0);
        $query = "UPDATE product SET productname=:productname, pricesell=:pricesell, priceentry=:priceentry,
         description=:description, category_id=:category_id, brand_id=:brand_id, color=:color, size=:size,
         parent_id=:parent_id, product_slug=:product_slug, count=:count, modifieddate = current_timestamp()
            WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);

        if ($this->product_id == "" || $this->pricesell == "" || $this->productname == "") {
            return false;
        }

        $this->parent_id = $this->parent_id == "" ?  NULL : $this->parent_id;
        $this->product_slug = $this->parent_id ?  "" : $this->product_slug;
        // bind data 
        $stmt->bindParam(':product_id', $this->product_id);
        $stmt->bindParam(':productname', $this->productname);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':brand_id', $this->brand_id);
        $stmt->bindParam(':pricesell', $this->pricesell);
        $stmt->bindParam(':priceentry', $this->priceentry);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':size', $this->size);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':parent_id', $this->parent_id);
        $stmt->bindParam(':product_slug', $this->product_slug);
        $stmt->bindParam(':count', $this->count);

        if ($this->img) {
            $DIR = '../../Images/product/';
            if (!is_dir($DIR)) {
                mkdir($DIR, 0777, true);
            }
            foreach ($this->img as $img) {
                $file_chunks = explode(";base64,", $img->file);
                $base64Img = base64_decode($file_chunks[1]);

                $path = 'https://' . $_SERVER['HTTP_HOST'] . '/coosport-server/Images/product/' . $img->name;
                $file = $DIR . $img->name;
                if (file_put_contents($file, $base64Img)) {
                    $query2 = "UPDATE img SET img = :pathImg 
                    WHERE img_id = (SELECT img_id FROM img 
                    WHERE product_id=:product_id LIMIT " . ($img->id - 1) . ",1)";
                    $query3 = "SELECT img_id FROM img 
                    WHERE product_id=:product_id LIMIT " . ($img->id - 1) . ",1";
                    $stmt3 = $this->conn->prepare($query3);
                    $stmt3->bindParam(':product_id', $this->product_id);
                    // $read = $stmt3->execute();
                    // $num = $read->rowCount();
                    if ($stmt3->fetchColumn() > 0) {
                        $stmt2 = $this->conn->prepare($query2);
                        $stmt2->bindParam(':product_id', $this->product_id);
                        $stmt2->bindParam(':pathImg', $path);
                        if (!$stmt2->execute()) {
                            return false;
                        }
                    } else {
                        $query2 = "INSERT INTO img SET img = :pathImg, product_id=:product_id";
                        $stmt2 = $this->conn->prepare($query2);
                        $stmt2->bindParam(':product_id', $this->product_id);
                        $stmt2->bindParam(':pathImg', $path);
                        if (!$stmt2->execute()) {
                            return false;
                        }
                    }
                };
            }
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
        $query = "DELETE FROM product WHERE product.product_id=:product_id";
        $query2 = "DELETE FROM img WHERE product_id=:product_id";
        $stmt = $this->conn->prepare($query);
        $stmt2 = $this->conn->prepare($query2);
        // bind data 
        if (!$this->product_id) {
            return false;
        }
        $stmt->bindParam(':product_id', $this->product_id);
        $stmt2->bindParam(':product_id', $this->product_id);
        if ($stmt2->execute() && $stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
}
