<?php
    
require_once 'database.php';
require_once('header.php');

class Prescribe {
    public $id = '';
    public $user_id = '';
    //new
    public $admin_id = '';
    public $product_code = '';
    public $name = '';
    public $product_name = '';
    public $description = '';
    public $dosage = '';
    public $quantity = '';
    public $price = '';
    public $date = '';

    protected $db;

    function __construct() {
        $this->db = new Database();
    }

    function add() {
        
        if (empty($this->user_id)) {
            echo "User ID is not set in the class.";
            return false;
        }

        $sql = "INSERT INTO prescribe (product_code, name, product_name, description, dosage, quantity, price, date, user_id)
                VALUES (:product_code, :name, :product_name, :description, :dosage, :quantity, :price, :date, :user_id)";

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
            $query->bindParam(':user_id', $this->user_id); 
            
            
            $query->execute();

            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    function edit() {
        $user_id = $_SESSION['account']['id'];

        $sql = "UPDATE prescribe SET product_code = :product_code, name = :name, product_name = :product_name, description = :description, 
                dosage = :dosage, quantity = :quantity, price = :price, date = :date 
                WHERE id = :id AND user_id = :user_id";

        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':product_code', $this->product_code);
        $query->bindParam(':name', $this->name);
        $query->bindParam(':product_name', $this->product_name);
        $query->bindParam(':description', $this->description);
        $query->bindParam(':dosage', $this->dosage);
        $query->bindParam(':quantity', $this->quantity);
        $query->bindParam(':price', $this->price);
        $query->bindParam(':date', $this->date);
        $query->bindParam(':id', $this->id);
        $query->bindParam(':user_id', $user_id);

        return $query->execute();
    }

    function fetchRecord($recordID) {
        $user_id = $_SESSION['account']['id'];

        $sql = "SELECT * FROM prescribe WHERE id = :recordID AND user_id = :user_id";

        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':recordID', $recordID);
        $query->bindParam(':user_id', $user_id);

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
        // Check the role of the logged-in account
        $role = $_SESSION['account']['role']; // Assuming role is stored in the session
        $user_id = $_SESSION['account']['id'];
    
        if ($role == 'admin') { // Role 2 is Admin
            // Admin sees all prescriptions
            $sql = "SELECT * FROM prescribe ORDER BY id ASC";
            $query = $this->db->connect()->prepare($sql);
        } else {
            // Regular users see only their own prescriptions
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
}

// Uncomment the lines below to test the Prescribe class methods.

// $obj = new Prescribe();
// var_dump($obj->showAll());
?>
