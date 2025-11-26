<?php
class Database {
    private $host = "localhost";
    private $db_name = "rut24";
    private $username = "root";
    private $password = "";
    public $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
            "mysql:host={$this->host};port=3307;dbname={$this->db_name}",
            $this->username,
            $this->password
        );

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }
        return $this->conn;
    }
    
// Updated function to fetch holidays using PDO
    public function getHolidays($tbname, $cond = "") {
        // Establish a connection using the already connected PDO object
        $conn = $this->connect(); 

        // Construct SQL query with optional conditions
        if ($cond != "") {
            $sql = "SELECT * FROM " . $tbname . " WHERE " . $cond;
        } else {
            $sql = "SELECT * FROM " . $tbname;
        }

        // Prepare and execute the query
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Fetch the results
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($data, $row);
        }

        return $data; // Return the data as an array
    }
}
    
//     public function getUsers($tbname, $cond)
// {
//     $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME,3307);
//     if ($cond != "") {
//         $sql = "SELECT * FROM " . $tbname . " WHERE " . $cond;
//     } else {
//         $sql = "SELECT * FROM " . $tbname;
//     }
//     $result = mysqli_query($conn, $sql);
//     if (!$result) {
//         die("Query execution failed: " . mysqli_error($conn));
//     }
//     $data = [];
//     while ($row = mysqli_fetch_assoc($result)) {
//         array_push($data, $row);
//     }
//     return json_encode($data);
//   //  $conn->close();
// }