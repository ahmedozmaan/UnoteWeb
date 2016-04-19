
<?php

/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 *
 * @author Ravi Tamada
 * @link URL Tutorial link
 */
class Demo {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/include/db_connect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

    public function getAllChatRooms() {
      $query = "SELECT chat_room_id, name, created_at FROM chat_rooms WHERE 1";
        if ($result = mysqli_query($this->conn, $query)) {
            return $result;
            mysqli_free_result($result);
        }
    }

    public function getAllUsers() {
        $query = "SELECT user_id, name, email, gcm_registration_id, created_at FROM users WHERE 1";
        if ($result = mysqli_query($this->conn, $query)) {
            return $result;
            mysqli_free_result($result);
        }
    }

    public function getDemoUser() {
        $name = 'AndroidHive';
        $email = 'admin@androidhive.info';
        
        $stmt = $this->conn->prepare("SELECT user_id from users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        if ($num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();
            return $user_id;
        } else {
            $stmt = $this->conn->prepare("INSERT INTO users(name, email) values(?, ?)");
            $stmt->bind_param("ss", $name, $email);
            $result = $stmt->execute();
            $user_id = $stmt->insert_id;
            $stmt->close();
            return $user_id;
        }
    }

}

?>
