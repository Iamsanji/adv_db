<?php
    
    require_once 'database.php';

    class Prescribe {
        public $id = '';
        public $product_code = '';
        public $name = '';
        public $product_name = '';
        public $dosage = '';
        public $quantity = '';
        public $price = '';
        //public $total = '';
        public $date = '';

        protected $db;

        function __construct() {
            $this->db = new Database();
        }

        function add() {
            $sql = "INSERT INTO prescribe (product_code, name, product_name, dosage, quantity, price, date) VALUES (:product_code, :name, :product_name, :dosage, :quantity, :price, :date);";

            $query = $this->db->connect()->prepare($sql);

            $query->bindParam(':product_code', $this->product_code);
            $query->bindParam(':name', $this->name);
            $query->bindParam(':product_name', $this->product_name);
            $query->bindParam(':dosage', $this->dosage);
            $query->bindParam(':quantity', $this->quantity);
            $query->bindParam(':price', $this->price);
           // $query->bindParam(':total', $this->total);
            $query->bindParam(':date', $this->date);

            return $query->execute();
        }

        function edit() {
            $sql = "UPDATE prescribe set producte_code = :product_code, name = :name, product_name = :product_name, dosage = :dosage, quantity = :quantity, price = :price, date = :date WHERE id = :id;";
            $query = $this->db->connect()->prepare($sql);

            $query->bindParam(':product_code', $this->product_code);
            $query->bindParam(':name', $this->name);
            $query->bindParam(':product_name', $this->product_name);
            $query->bindParam(':dosage', $this->dosage);
            $query->bindParam(':quantity', $this->quantity);
            $query->bindParam(':price', $this->price);
           // $query->bindParam(':total', $this->total);
            $query->bindParam(':date', $this->date);

            return $query->execute();
        }

        function fetchRecord($recordID) {
            $sql = "SELECT * FROM prescribe WHERE id = :recordID;";

            $query = $this->db->connect()->prepare($sql);

            $query->bindParam(':recordID', $recordID);

            $data = null;

            if ($query->execute()) {
                $data = $query->fetch();
            }

            return $data;
        }

        function delete($recordID) {
            $sql ="DELETE FROM prescribe where id = :recordID;";

            $query = $this->db->connect()->prepare($sql);

            $query->bindParam(':recordID', $recordID);

            return $query->execute();
        }

        function showAll() {
            $sql = "SELECT * FROM prescribe ORDER BY id ASC;";

            $query = $this->db->connect()->prepare($sql);
            $data = null;

            if ($query->execute()) {
                $data = $query->fetchAll();
            }
            return $data;
        }

        function codeExist($product_code) {
            $sql = "SELECT COUNT(*) FROM prescribe WHERE product_code = :product_code";
            $query = $this->db->connect()->prepare($sql);
        
            // Bind the parameter
            $query->bindParam(':product_code', $product_code);
        
            // Execute the query
            $query->execute();
        
            // Fetch the count. If it's greater than 0, the code already exists
            $count = $query->fetchColumn();
        
            // Return true if count > 0 (code exists), otherwise false
            return $count > 0;
        }
        

    }

    // Uncomment the lines below to test the Product class methods.

    // Create a new Product instance and display all products.
    // $obj = new Prescribe();
    // var_dump($obj->showAll());

?>