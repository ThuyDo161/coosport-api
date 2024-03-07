<?php
class Account
{
    private $conn;

    // users properties
    public $users_id;
    public $user_code;
    public $username;
    public $password;
    public $name;
    public $address;
    public $user_tel;
    public $status;
    public $createddate;

    // role properties
    public $rolename;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login($flgAdm)
    {
        $pass = md5($this->password);
        $query = "SELECT * FROM users,role,account 
        WHERE users.role_id = role.role_id AND account.user_id = users.users_id
        AND account.password = :password AND account.username = :username AND users.status = 1";

        if ($flgAdm) $query = "SELECT * FROM users,role,account 
        WHERE users.role_id = role.role_id AND account.user_id = users.users_id
        AND account.password = :password AND account.username = :username AND users.status = 1 AND (role.role_id = 1 OR role.role_id = 2)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $pass);
        $stmt->execute();
        return $stmt;
    }

    public function user()
    {
        $query = "SELECT * FROM users,role, account 
        WHERE users.role_id = role.role_id AND account.user_id = users.users_id
        AND account.username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        return $stmt;
    }

    public function changePass()
    {
        $newPass = md5($this->password);
        $query = "UPDATE `account` SET `password`=:newPass WHERE `username`=:username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':newPass', $newPass);
        if ($stmt->execute()) {
            return 200;
        }
        return false;
    }

    public function update()
    {
        $query = "SELECT * FROM account WHERE account.username =:username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();

        if (($stmt->rowCount()) > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->users_id = $row['user_id'];

            $query1 = "UPDATE `users` SET `name`=:name,`address`=:address,`user_tel`=:user_tel WHERE `users_id`=:users_id";
            $stmt1 = $this->conn->prepare($query1);

            $stmt1->bindParam(':users_id', $this->users_id);
            $stmt1->bindParam(':name', $this->name);
            $stmt1->bindParam(':address', $this->address);
            $stmt1->bindParam(':user_tel', $this->user_tel);

            if ($stmt1->execute()) {
                return 200;
            }
            printf('Error %s \n', $stmt1->error);
            return 204;
        }
        printf('Error %s \n', $stmt->error);
        return 204;
    }

    public function register()
    {
        $this->user_code = $this->username;
        do {
            $query = "SELECT * FROM users WHERE user_code=:user_code";
            $stmt = $this->conn->prepare($query);

            $this->user_code = htmlspecialchars(strip_tags($this->user_code));

            $stmt->bindParam(':user_code', $this->user_code);
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0) {
                $slug = explode('-', $this->user_code);
                $this->user_code = $slug[0] . '-' . mt_rand();
            }
        } while ($num > 0);
        $query = "SELECT username FROM account WHERE username =:username";
        $query1 = "INSERT INTO users 
        SET name=:name, address=:address, user_tel=:user_tel, user_code =:user_code, status= 1, createddate = current_timestamp(), role_id = 4";
        $query2 = "INSERT INTO account SET user_id = (SELECT users_id FROM users WHERE user_code =:user_code), username = :username, password = :password";
        $stmt = $this->conn->prepare($query);
        $stmt1 = $this->conn->prepare($query1);
        $stmt2 = $this->conn->prepare($query2);

        // clean data
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->user_tel = htmlspecialchars(strip_tags($this->user_tel));

        if ($this->username == "" || $this->password == "" || $this->address == "" || $this->user_tel == "") {
            return 202;
        }

        // bind data 
        $pass = md5($this->password);
        $stmt->bindParam(':username', $this->username);

        $stmt1->bindParam(':name', $this->name);
        $stmt1->bindParam(':address', $this->address);
        $stmt1->bindParam(':user_tel', $this->user_tel);
        $stmt1->bindParam(':user_code', $this->user_code);

        $stmt2->bindParam(':user_code', $this->user_code);
        $stmt2->bindParam(':username', $this->username);
        $stmt2->bindParam(':password', $pass);

        $stmt->execute();
        $num = $stmt->rowCount();
        if ($num > 0) {
            return 201;
        }

        if ($stmt1->execute() && $stmt2->execute()) {
            return 200;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
}
