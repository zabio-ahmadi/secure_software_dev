<?php
error_reporting(0);

require_once '/var/www/html/PHPMailer/src/PHPMailer.php';
require_once '/var/www/html/PHPMailer/src/SMTP.php';


class Connection
{
    private $env;
    private $servername;
    private $username;
    private $password;
    private $DB_name;
    public $connection = NULL;

    public $USER_SESSION_DURATION = NULL;

    public function __construct()
    {
        $this->env = parse_ini_file('.env');

        $this->servername = $this->env["DB_HOST"]; // cause we are on the docker network 
        $this->username = $this->env['DB_USER'];
        $this->password = $this->env['DB_PASSWORD'];
        $this->DB_name = $this->env['DB_NAME'];

        // Create connection
        $this->connection = mysqli_connect($this->servername, $this->username, $this->password, $this->DB_name);
        // Check connection
        if (!$this->connection) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $this->USER_SESSION_DURATION = time() + $this->env['SESSION_DURATION']; // 1 hour

        $query = 'CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_name VARCHAR(64),
            age INT,
            email VARCHAR(255),
            password VARCHAR(255),
            bio VARCHAR(512),
            isAdmin Boolean default 0,
            email_verified Boolean default 0,
            verify_token varchar(255) default null,
            verified_at DATETIME,
            password_reset_token varchar(255) default null
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

    public function loggedin($obj)
    {

        //Time variable in seconds
        $timeInSeconds = $_SESSION['valid_until'];

        // Convert seconds to a timestamp
        $timestamp = strtotime("@" . $timeInSeconds);

        // Format the timestamp as desired
        $formattedTime = date("Y-m-d H:i:s", $timestamp);


        $valid = isset($_SESSION['valid_until']) && $_SESSION['valid_until'] > time();

        if (isset($_SESSION['logged_user']) && !empty($_SESSION['logged_user']) && $valid) {
            return true;

        }
        return false;
    }

    public function acountVerified($obj)
    {
        $loged_user_email = $_SESSION['logged_user'];
        $query = "SELECT * FROM users where email='$loged_user_email'";
        $result = $obj->executeQuery($query);
        $result = mysqli_fetch_assoc($result)['email_verified'];
        return ($result == 1);

    }

    public function getUserIdByEmail($obj, $email)
    {
        // echo $_SESSION['logged_user'];
        $query = "SELECT * FROM users where email='$email'";
        $result = $obj->executeQuery($query);
        return mysqli_fetch_assoc($result)['id'];
    }

    public function getUserByEmail($obj, $email)
    {
        // echo $_SESSION['logged_user'];
        $query = "SELECT * FROM users where email='$email'";
        $result = $obj->executeQuery($query);
        return mysqli_fetch_assoc($result);
    }

    public function sendMail($to, $subject, $body)
    {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $this->env['SMTP_HOST']; //gmail SMTP server
        $mail->SMTPAuth = true;
        
        $mail->Username = $this->env['SMTP_USER_MAIL']; //email
        $mail->Password = $this->env['SMTP_USER_PASSWORD']; //16 character obtained from app password created
        $mail->Port = $this->env['SMTP_PORT']; //SMTP port
        $mail->SMTPSecure = $this->env['SMTP_PROTOCOL'];

        //sender information
        $mail->setFrom($this->env['SMTP_SENDER_EMAIL_ADDRESS'], 'secure app');

        //receiver email address and name
        $mail->addAddress($to, 'secure app');

        // Add cc or bcc   
        // $mail->addCC('email@mail.com');  
        // $mail->addBCC('user@mail.com');  

        $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body = $body;

        // Send mail   
        $sended = $mail->send();
        $mail->smtpClose();

        return ($sended == true);
    }

    public function __destruct()
    {
        $this->closeConnection();
    }


}

?>