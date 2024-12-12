<?php

require_once 'database.php';

class Account {
    public $id = '';
    public $first_name = '';
    public $last_name = '';
    public $sex = '';
    public $age = '';
    public $contact_number = '';
    public $pwd_id = '';
    public $address = '';
    public $username = '';
    public $password = '';
    public $role = '';
    public $is_staff = false;
    public $is_admin = false;

    protected $db;

    function __construct(){
        $this->db = new Database();
    }

    function add(){
        $sql = "INSERT INTO account (first_name, last_name, sex, age, contact_number, pwd_id, address, username, password, role, is_staff, is_admin) VALUES (:first_name, :last_name, :sex, :age, :contact_number, :pwd_id, :address, :username, :password, :role, :is_staff, :is_admin);";
        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':first_name', $this->first_name);
        $query->bindParam(':last_name', $this->last_name);
        $query->bindParam(':sex', $this->sex);
        $query->bindParam(':age', $this->age);
        $query->bindParam(':contact_number', $this->contact_number);
        $query->bindParam(':pwd_id', $this->pwd_id);
        $query->bindParam(':address', $this->address);
        $query->bindParam(':username', $this->username);
        $hashpassword = password_hash($this->password, PASSWORD_DEFAULT);
        $query->bindParam(':password', $hashpassword);
        $query->bindParam(':role', $this->role);
        $query->bindParam(':is_staff', $this->is_staff);
        $query->bindParam(':is_admin', $this->is_admin);

        return $query->execute();
    }

    function usernameExist($username, $excludeID = null){
        $sql = "SELECT COUNT(*) FROM account WHERE username = :username";
        if ($excludeID){
            $sql .= " and id != :excludeID";
        }

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':username', $username);

        if ($excludeID){
            $query->bindParam(':excludeID', $excludeID);
        }

        $count = $query->execute() ? $query->fetchColumn() : 0;

        return $count > 0;
    }

    function login($username, $password){
        $sql = "SELECT * FROM account WHERE username = :username LIMIT 1;";
        $query = $this->db->connect()->prepare($sql);

        $query->bindParam('username', $username);

        if($query->execute()){
            $data = $query->fetch();
            if($data && password_verify($password, $data['password'])){
                return true;
            }
        }

        return false;
    }

    function fetch($username){
        $sql = "SELECT * FROM account WHERE username = :username LIMIT 1;";
        $query = $this->db->connect()->prepare($sql);

        $query->bindParam('username', $username);
        $data = null;
        if($query->execute()){
            $data = $query->fetch();
        }

        return $data;
    }

    function fetchById($user_id){
        $sql = "SELECT * FROM account WHERE id = :user_id LIMIT 1;";
        $query = $this->db->connect()->prepare($sql);
    
        $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $query->execute();
    
        $data = $query->fetch();
        return $data;
    }
    
    public function update($user_id) {
        $sql = "UPDATE account SET first_name = :first_name, last_name = :last_name, sex = :sex, age = :age, contact_number = :contact_number, pwd_id = :pwd_id, address = :address, username = :username, password = :password WHERE id = :id";
        $query = $this->db->connect()->prepare($sql);
    
        $query->bindParam(':first_name', $this->first_name);
        $query->bindParam(':last_name', $this->last_name);
        $query->bindParam(':sex', $this->sex);
        $query->bindParam(':age', $this->age);
        $query->bindParam(':contact_number', $this->contact_number);
        $query->bindParam(':pwd_id', $this->pwd_id);
        $query->bindParam(':address', $this->address);
        $query->bindParam(':username', $this->username);
        $query->bindParam(':password', $this->password);
        $query->bindParam(':id', $user_id);
    
        return $query->execute();
    }

    public function updateProfilePicture($user_id, $filePath) {
        $sql = "UPDATE account SET profile_picture = :profile_picture WHERE id = :id";
        $query = $this->db->connect()->prepare($sql);
    
        $query->bindParam(':profile_picture', $filePath);
        $query->bindParam(':id', $user_id);
    
        return $query->execute();
    }
    
    public function getProfilePicture($user_id) {
        $sql = "SELECT profile_picture FROM account WHERE id = :id";
        $query = $this->db->connect()->prepare($sql);
    
        $query->bindParam(':id', $user_id);
        $query->execute();
    
        $data = $query->fetch();
        return $data['profile_picture'] ?? 'uploads/default.jpg';
    }
    
    //new dec 10
    public function getDoctors() {
        $sql = "SELECT id, first_name, last_name, username FROM account WHERE role = 'admin' ORDER BY id ASC";
        $query = $this->db->connect()->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    } 

    public function addDoctor($first_name, $last_name, $username, $password) {
        // Insert into the `account` table first
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO account (first_name, last_name, username, password, role, is_admin, is_staff) VALUES (:first_name, :last_name, :username, :password, 'admin', 1, 1)";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':first_name', $first_name);
        $query->bindParam(':last_name', $last_name);
        $query->bindParam(':username', $username);
        $query->bindParam(':password', $hashedPassword);

        if ($query->execute()) {
            // Get the last inserted ID for the new admin
            $admin_id = $this->db->connect()->insert_id;

            // Insert the same data into the `admins` table
            $sql = "INSERT INTO admins (id, first_name, last_name, username, password) VALUES (:id, :first_name, :last_name, :username, :password)";
            $query1 = $this->db->connect()->prepare($sql);
            $query1->bindParam(':id', $id);
            $query1->bindParam(':first_name', $first_name);
            $query1->bindParam(':last_name', $last_name);
            $query1->bindParam(':username', $username);
            $query1->bindParam(':password', $hashedPassword);

            if ($query1->execute()) {
                return true;  // Successfully added to both tables
            } else {
                // If the second insert fails, delete the entry from `account`
                $stmt3 = $this->db->prepare("DELETE FROM account WHERE id = ?");
                $stmt3->bind_param("i", $admin_id);
                $stmt3->execute();
                return false;
            }
        }
        return false;
    }

    public function showById($userId) {

        $sql = "SELECT * FROM account WHERE id = :user_id";
        
        $query = $this->db->connect()->prepare($sql);
        
        $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
        
        $query->execute();
        
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            return $result;  
        } else {
            return null; 
        }
    }
    
}

/*
$obj = new Account();
$obj->first_name = 'Secretary';
$obj->last_name = 'sec';
$obj->sex = '';
$obj->age = '';
$obj->contact_number = '';
$obj->pwd_id = '';
$obj->address = '';
$obj->username = 'staff';
$obj->password = 'staff';
$obj->role = 'staff';
$obj->is_staff = true;
$obj->is_admin = false;

if ($obj->add()) {
    echo "Account added successfully.";
} else {
    echo "Failed to add account.";
}*/


// $obj = new Account();

// $obj->add();

?>