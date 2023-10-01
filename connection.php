<?php
error_reporting(0);
class Connection
{
    private $servername = "mysql"; // cause we are on the docker network 
    private $username = "root";
    private $password = "admin";
    private $DB_name = 'social_network';
    public $connection = NULL;

    public function __construct()
    {
        
        // Create connection
        $this->connection = mysqli_connect($this->servername, $this->username, $this->password, $this->DB_name);
        // Check connection
        if (!$this->connection) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $query = 'CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_name VARCHAR(64),
            age INT,
            email VARCHAR(255),
            password VARCHAR(255),
            bio VARCHAR(512),
            isAdmin Boolean default 0
        )';

        // Execute the query
        $result = mysqli_query($this->connection, $query);
        // Check if the query was successful
        if (!$result) {
            die("Query failed: " . mysqli_error($this->connection));
        }

        $query = 'CREATE TABLE IF NOT EXISTS posts (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255),
            body VARCHAR(1024),
            image_url varchar(255),
            user_id INT, 
            foreign key (user_id) references users (id)
        )';

        // Execute the query
        $result = mysqli_query($this->connection, $query);
        // Check if the query was successful
        if (!$result) {
            die("Query failed: " . mysqli_error($this->connection));
        }


    }

    public function executeQuery($query)
    {
        // Execute the query
        $result = mysqli_query($this->connection, $query);
        // Check if the query was successful
        if (!$result) {
            die("Query failed: " . mysqli_error($this->connection));
        }
        return $result;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    // Close the database connection// Close the database connection
    public function closeConnection()
    {
        if ($this->connection) {
            mysqli_close($this->connection);
            $this->connection = NULL;
        }
    }

    public function __destruct()
    {
        $this->closeConnection();
    }

    public function loggedin()
    {
        if (isset($_SESSION['logged_user']) && !empty($_SESSION['logged_user'])) {
            return true;

        }
        return false;
    }

    public function getUserIdByEmail($obj, $email)
    {
        // echo $_SESSION['logged_user'];
        $query = "SELECT * FROM users where email='$email'";
        $result = $obj->executeQuery($query);

        return mysqli_fetch_assoc($result)['id'];

    }


}

?>