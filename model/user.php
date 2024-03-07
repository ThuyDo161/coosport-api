<?php
class Users
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
    public $role_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // create data
    public function create()
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
        $kt = 0;
        do {
            $query = "SELECT username FROM account WHERE username =:username";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':username', $this->username);
            $stmt->execute();
            $num = $stmt->rowCount();
            if ($num > 0) {

                $slug = explode('-', $this->username);
                $this->username = $slug[0] . '-' . ++$kt;
            }
        } while ($num > 0);
        $query1 = "INSERT INTO users 
        SET name=:name, address=:address, user_tel=:user_tel, user_code =:user_code, status= 1, createddate = current_timestamp(), role_id =:role_id";
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

        if ($this->username == "" || $this->password == "" || $this->address == "" || $this->user_tel == "" || $this->role_id == "") {
            return 202;
        }

        // bind data 
        $pass = md5($this->password);

        $stmt1->bindParam(':name', $this->name);
        $stmt1->bindParam(':address', $this->address);
        $stmt1->bindParam(':user_tel', $this->user_tel);
        $stmt1->bindParam(':user_code', $this->user_code);
        $stmt1->bindParam(':role_id', $this->role_id);

        $stmt2->bindParam(':user_code', $this->user_code);
        $stmt2->bindParam(':username', $this->username);
        $stmt2->bindParam(':password', $pass);

        if ($stmt1->execute() && $stmt2->execute()) {
            return 200;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }

    public function read()
    {
        $query = "SELECT users.users_id, name, address, user_tel, status, rolename,
         role.role_id, users.createddate, users.modifieddate, username, password
        FROM users LEFT JOIN account ON users.users_id = account.user_id
         LEFT JOIN role ON users.role_id = role.role_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function readRole()
    {
        $query = "SELECT * FROM role ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // update data
    public function update()
    {
        $query = "UPDATE users
         SET name=:name, address=:address, user_tel=:user_tel, status=:status,
         role_id=:role_id WHERE users_id=:users_id";
        $stmt = $this->conn->prepare($query);

        // clean data
        $this->users_id = htmlspecialchars(strip_tags($this->users_id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->user_tel = htmlspecialchars(strip_tags($this->user_tel));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->role_id = htmlspecialchars(strip_tags($this->role_id));

        if ($this->users_id == "" || $this->address == "" || $this->user_tel == "") {
            return false;
        }

        // bind data 
        $stmt->bindParam(':users_id', $this->users_id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':user_tel', $this->user_tel);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':role_id', $this->role_id);

        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
    // delete data
    public function resetPassword()
    {
        $defaultPassword = md5("abc123");
        $query = "UPDATE account SET password = :default
         WHERE user_id=:users_id";
        $stmt = $this->conn->prepare($query);
        // bind data 
        if (!$this->users_id) {
            return false;
        }
        $stmt->bindParam(':users_id', $this->users_id);
        $stmt->bindParam(':default', $defaultPassword);
        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
    // delete data
    public function delete()
    {
        $query = "DELETE FROM users
         WHERE users_id=:users_id";
        $stmt = $this->conn->prepare($query);
        // bind data 
        if (!$this->users_id) {
            return false;
        }
        $stmt->bindParam(':users_id', $this->users_id);
        if ($stmt->execute()) {
            return true;
        }
        printf('Error %s \n', $stmt->error);
        return false;
    }
}
