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

    //new dec 10

    protected $db;

    function __construct() {
        $this->db = new Database();
    }

    function add() {
        session_start();
    
        // Ensure the admin_id is set in the session
        if (!isset($_SESSION['account']['id']) || $_SESSION['account']['role'] != 'admin') {
            die("Error: Admin not logged in or invalid role.");
        }
        
        $admin_id = $_SESSION['account']['id'];

        $currentDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime($this->date . ' + ' . $this->duration . ' days'));
        $status = ($currentDate > $endDate) ? 'done' : 'not done';
    
        $sql = "INSERT INTO prescribe (product_code, name, product_name, description, dosage, quantity, price, duration, date, status, admin_id, user_id) 
                VALUES (:product_code, :name, :product_name, :description, :dosage, :quantity, :price, :duration, :date, :status, :admin_id, :user_id)";
    
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
            //new dec 9
            $query->bindParam(':admin_id', $admin_id);
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
    
        $sql = "SELECT * FROM prescribe WHERE id = :id AND (user_id = :user_id OR :role = 'admin')";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':id', $id);
        $query->bindParam(':user_id', $user_id);
        $query->bindParam(':role', $_SESSION['account']['role']);  // Check if the user is admin
        $query->execute();
        
        $record = $query->fetch(PDO::FETCH_ASSOC);
    
        if (!$record) {
            echo "You do not have permission to edit this prescription.";
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
            $query->bindParam(':status', $status);
            $query->bindParam(':id', $id);
    
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
    
    /*function fetchRecord($recordID) {
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
    }*/

    //dec12
    function fetchRecord($id) {
        $sql = "SELECT * FROM prescribe WHERE id = :id";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    
    

    function delete($recordID) {
        $user_id = $_SESSION['account']['id'];

        $sql = "DELETE FROM prescribe WHERE id = :recordID AND user_id = :user_id";

        $query = $this->db->connect()->prepare($sql);

        $query->bindParam(':recordID', $recordID);
        $query->bindParam(':user_id', $user_id);

        return $query->execute();
    }

    public function showAll() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    
        $role = $_SESSION['account']['role']; 
        $admin_id = $_SESSION['account']['id']; 
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
    
        if ($role == 'superadmin') {

            $sql = "SELECT * FROM prescribe WHERE name LIKE :searchTerm ORDER BY id ASC";
            $query = $this->db->connect()->prepare($sql);
            $query->bindParam(':searchTerm', $wildcardSearch);
        } elseif ($role == 'admin') {

            $sql = "SELECT * FROM prescribe WHERE admin_id = :admin_id AND name LIKE :searchTerm ORDER BY id ASC";
            $query = $this->db->connect()->prepare($sql);
            $query->bindParam(':admin_id', $admin_id);
            $query->bindParam(':searchTerm', $wildcardSearch);
        } else {

            $sql = "SELECT * FROM prescribe WHERE user_id = :user_id AND name LIKE :searchTerm ORDER BY id ASC";
            $query = $this->db->connect()->prepare($sql);
            $query->bindParam(':user_id', $admin_id);
            $query->bindParam(':searchTerm', $wildcardSearch);
        }
    
        $wildcardSearch = '%' . $searchTerm . '%';
    
        $data = null;
    
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
    
        return $data;
    }
    
    
    public function showByAdmin($adminId)   {
    $sql = "SELECT * FROM prescribe WHERE admin_id = :admin_id";
    $query = $this->db->connect()->prepare($sql);
    $query->bindParam(':admin_id', $adminId, PDO::PARAM_INT);
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
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

    public function fetchStatus() {
        // Fetch distinct statuses from the prescribe table
        $sql = "SELECT DISTINCT status AS id, status AS name FROM prescribe ORDER BY status ASC;";
        
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC); 
        }
        
        return $data;
    }
    
    
}

// Uncomment the lines below to test the Prescribe class methods.

// $obj = new Prescribe();
// var_dump($obj->showAll());
?>
