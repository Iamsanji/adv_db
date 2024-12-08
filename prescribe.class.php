<?php
    
require_once 'database.php';
require_once('header.php');

class Prescribe {
    public $id = '';
    public $user_id = '';
    
    public $admin_id = '';
    public $product_code = '';
    public $name = '';
    public $product_name = '';
    public $description = '';
    public $dosage = '';
    public $quantity = '';
    public $price = '';
    public $date = '';

    //new dec 8
    public $status = '';
    public $duration = '';

    
    protected $db;

    function __construct() {
        $this->db = new Database();
    }

    function add() {
        $currentDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime($this->date . ' + ' . $this->duration . ' days'));
        $status = ($currentDate > $endDate) ? 'done' : 'not done';
    
        $sql = "INSERT INTO prescribe (product_code, name, product_name, description, dosage, quantity, price, duration, date, status, user_id) 
                VALUES (:product_code, :name, :product_name, :description, :dosage, :quantity, :price, :duration, :date, :status, :user_id)";
    
        try {
            $query = $this->db->connect()->prepare($sql);
            $query->bindParam(':product_code', $this->product_code);
            $query->bindParam(':name', $this->name);
            $query->bindParam(':product_name', $this->product_name);
            $query->bindParam(':description', $this->description);
            $query->bindParam(':dosage', $this->dosage);
            $query->bindParam(':quantity', $this->quantity);
            $query->bindParam(':price', $this->price);
            $query->bindParam(':duration', $this->duration);
            $query->bindParam(':date', $this->date);
            $query->bindParam(':status', $status);
            $query->bindParam(':user_id', $this->user_id);  // Use the selected patient ID
    
            if ($query->execute()) {
                return true;
            } else {
                echo "Error: ";
                print_r($query->errorInfo());
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    
    function edit() {
        session_start();
        $user_id = $_SESSION['account']['id'];
    
        $id = clean_input($_GET['id']);
        
        if (empty($id)) {
            echo "Invalid prescription ID.";
            return false;
        }
    
        $currentDate = date('Y-m-d');

        $endDate = date('Y-m-d', strtotime($this->date . ' + ' . $this->duration . ' days'));
    
        $status = ($currentDate > $endDate) ? 'done' : 'not done';
    
        $sql = "UPDATE prescribe 
                SET product_code = :product_code, 
                    name = :name, 
                    product_name = :product_name, 
                    description = :description, 
                    dosage = :dosage, 
                    quantity = :quantity, 
                    price = :price, 
                    duration = :duration,
                    date = :date, 
                    status = :status 
                WHERE id = :id";
    
    
        try {
            $query = $this->db->connect()->prepare($sql);
    
            $query->bindParam(':product_code', $this->product_code);
            $query->bindParam(':name', $this->name);
            $query->bindParam(':product_name', $this->product_name);
            $query->bindParam(':description', $this->description);
            $query->bindParam(':dosage', $this->dosage);
            $query->bindParam(':quantity', $this->quantity);
            $query->bindParam(':price', $this->price);
            $query->bindParam(':date', $this->date);
            $query->bindParam(':duration', $this->duration);
            $query->bindParam(':status', $status);  // Use the calculated status
    
            $query->bindParam(':id', $id);
    
            if ($_SESSION['account']['role'] != 'admin') {
                $query->bindParam(':user_id', $user_id);
            }
    
            if ($query->execute()) {
                return true;
            } else {
                echo "Error: ";
                print_r($query->errorInfo());
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    

    function fetchRecord($recordID) {
        $user_id = $_SESSION['account']['id']; 
        $role = $_SESSION['account']['role'];
    
        
        if ($role == 'admin') { // Admin role
            $sql = "SELECT * FROM prescribe WHERE id = :recordID"; 
        } else {
            $sql = "SELECT * FROM prescribe WHERE id = :recordID AND user_id = :user_id";
        }
    
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':recordID', $recordID);
    
        
        if ($role != 'admin') {
            $query->bindParam(':user_id', $user_id);
        }
    
        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
    
        return $data;
    }
    

    function delete($recordID) {
        $user_id = $_SESSION['account']['id'];

        $sql = "DELETE FROM prescribe WHERE id = :recordID AND user_id = :user_id";

        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':recordID', $recordID);
        $query->bindParam(':user_id', $user_id);

        return $query->execute();
    }

    function showAll() {
        
        $role = $_SESSION['account']['role']; 
        $user_id = $_SESSION['account']['id'];
    
        if ($role == 'admin') { 

            $sql = "SELECT * FROM prescribe ORDER BY id ASC";
            $query = $this->db->connect()->prepare($sql);
        } else {
            
            $sql = "SELECT * FROM prescribe WHERE user_id = :user_id ORDER BY id ASC";
            $query = $this->db->connect()->prepare($sql);
            $query->bindParam(':user_id', $user_id);
        }
    
        $data = null;
    
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
    
        return $data;
    }
    

    function codeExist($product_code) {
        $sql = "SELECT COUNT(*) FROM prescribe WHERE product_code = :product_code AND user_id = :user_id";
        $query = $this->db->connect()->prepare($sql);
    
        $query->bindParam(':product_code', $product_code);
        $query->bindParam(':user_id', $_SESSION['account']['id']);
    
        $query->execute();
    
        $count = $query->fetchColumn();
    
        return $count > 0;
    }

    //new dec 8
    public function updateStatus($id, $status) {
        $sql = "UPDATE prescribe SET status = :status WHERE id = :id";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':status', $status);
        $query->bindParam(':id', $id);
        $query->execute();
    }
    
}

// Uncomment the lines below to test the Prescribe class methods.

// $obj = new Prescribe();
// var_dump($obj->showAll());
?>
