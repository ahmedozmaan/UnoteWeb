<?php

/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 *
 * @author Ravi Tamada
 * @link URL Tutorial link
 */
class DbHandler {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/db_connect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
     // creating new user if not existed
    public function fechUser($phone) {
        $response = array();

        // First check if user already existed in db
        if (!$this->isUserExists($phone)) {
                // Failed to create user
                $response["error"] = true;
                $response["message"] = "Oops! An error occurred while feching users";
            } else {
                // User with same email already existed in the db
                $response["error"] = false;
                $response["user"] = $this->getUserByPhone($phone);
            }

        return $response;
    }

    // updating user GCM registration ID
    public function updateGcmID($user_id, $gcm_registration_id) {
        $response = array();
        $stmt = $this->conn->prepare("UPDATE users SET gcm_registration_id = ? WHERE user_id = ?");
        $stmt->bind_param("si", $gcm_registration_id, $user_id);

        if ($stmt->execute()) {
            // User successfully updated
            $response["error"] = false;
            $response["message"] = 'GCM registration ID updated successfully';
        } else {
            // Failed to update user
            $response["error"] = true;
            $response["message"] = "Failed to update GCM registration ID";
            $stmt->error;
        }
        $stmt->close();

        return $response;
    }

    // fetching single user by id
    public function getUser($user_id) {
        $stmt = $this->conn->prepare("SELECT user_id, name, email, phone, clazz, gcm_registration_id, created_at FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        if ($stmt->execute()) {
            // $user = $stmt->get_result()->fetch_assoc();
            $stmt->bind_result($user_id, $name, $email,$phone,$clazz, $gcm_registration_id, $created_at);
            $stmt->fetch();
            $user = array();
            $user["user_id"] = $user_id;
            $user["name"] = $name;
            $user["email"] = $email;
            $user["phone"] = $phone;
            $user["clazz"] = $clazz;
            $user["gcm_registration_id"] = $gcm_registration_id;
            $user["created_at"] = $created_at;
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }

    // fetching multiple users by ids
    public function getUsers($user_ids) {

        $users = array();
        if (sizeof($user_ids) > 0) {
            $query = "SELECT user_id, name, email, gcm_registration_id, created_at FROM users WHERE user_id IN (";

            foreach ($user_ids as $user_id) {
                $query .= $user_id . ',';
            }

            $query = substr($query, 0, strlen($query) - 1);
            $query .= ')';

            if ($result = mysqli_query($this->conn, $query)) {
                //return $result;
                foreach ($result as $key => $user) {
                $tmp = array();
                    $tmp["user_id"] = $user["user_id"];
                    $tmp["name"] = $user["name"];
                    $tmp["email"] = $user["email"];
                    $tmp["gcm_registration_id"] = $user["gcm_registration_id"];
                    $tmp["created_at"] = $user["created_at"];
                   array_push($users, $tmp);
                }
                mysqli_free_result($result);
            }else{
                return null;
            }
        }
        return $users;
    }

    // messaging in a chat room / to persional message
    public function addMessage($user_id, $chat_room_id, $message, $file_flag, $file_name, $file_size) {
        $response = array();
        $file_link =$user_id."/".$chat_room_id."/".$file_name;

        $stmt = $this->conn->prepare("INSERT INTO messages (chat_room_id, user_id, message, file_flag, file_name, file_size, file_link ) values(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisisss", $chat_room_id, $user_id, $message, $file_flag, $file_name, $file_size, $file_link);

        if ($result = $stmt->execute()) {
            $response['error'] = false;

            // get the message
            $message_id = $this->conn->insert_id;
            $stmt = $this->conn->prepare("SELECT message_id, user_id, chat_room_id, message, created_at, file_flag, file_name, file_size, file_link  FROM messages WHERE message_id = ?");
            $stmt->bind_param("i", $message_id);
            if ($stmt->execute()) {
                $stmt->bind_result($message_id, $user_id, $chat_room_id, $message, $created_at, $file_flag, $file_name, $file_size, $file_link);
                $stmt->fetch();
                $tmp = array();
                $tmp['message_id'] = $message_id;
                $tmp['chat_room_id'] = $chat_room_id;
                $tmp['message'] = $message;
                $tmp['created_at'] = $created_at;
                $tmp['file_flag'] = $file_flag;
                $tmp['file_name'] = $file_name;
                $tmp['file_size'] = $file_size;
                $tmp['file_link'] = $file_link;
                $response['message'] = $tmp;
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Failed send message ' . $stmt->error;
        }

        return $response;
    }

    // fetching all chat rooms
    public function getAllChatRooms() {
      $query = "SELECT chat_room_id, name, created_at FROM chat_rooms  ORDER BY chat_room_id ASC";
        if ($result = mysqli_query($this->conn, $query)) {
            return $result;
            mysqli_free_result($result);
        }
    }
    /*fechingall chatroooms using the costom way*/
    public function getAllStChatRooms($clazz) {
      $query = "SELECT chat_room_id, name, created_at FROM chat_rooms WHERE name IN (SELECT clazz_name FROM clazz WHERE  clazz_name = '$clazz')
            OR name IN (SELECT clazz_department FROM clazz WHERE  clazz_name = '$clazz')
            OR name IN (SELECT clazz_level FROM clazz WHERE  clazz_name = '$clazz')
            OR name IN ('University')";
        if ($result = mysqli_query($this->conn, $query)) {
            return $result;
            mysqli_free_result($result);
        }
    }

    /*get single chat room using my custom way*/
    public function getChatRoom($chat_room_id) {
      $query = "SELECT chat_room_id, name, created_at FROM chat_rooms WHERE chat_room_id='$chat_room_id' limit 1";
        if ($result = mysqli_query($this->conn, $query)) {
            return $result;
            mysqli_free_result($result);
        }
    }
    /*get single chat room messages using my custom way*/
    public function getChatRoomsMessages($chat_room_id) {
      $query = "SELECT m.message, m.message_id, m.created_at, u.user_id, u.name FROM messages m, users u WHERE m.user_id = u.user_id AND m.chat_room_id= '$chat_room_id' ORDER BY m.message_id ASC";
        if ($result = mysqli_query($this->conn, $query)) {
            return $result;
            mysqli_free_result($result);
        }
    }

    /**
     * Checking for duplicate user by phone address
     * @param String $phone phone to check in db
     * @return boolean
     */
    public function isUserExists($phone) {
        $stmt = $this->conn->prepare("SELECT user_id from users WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
    /**
     * Fetching user by phone
     * @param String $phone User phone id
     */
    public function getUserByPhone($phone) {
        $stmt = $this->conn->prepare("SELECT user_id, name, email, phone, clazz, created_at FROM users WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        if ($stmt->execute()) {
            // $user = $stmt->get_result()->fetch_assoc();
            $stmt->bind_result($user_id, $name, $email, $phone, $clazz, $created_at);
            $stmt->fetch();
            $user = array();
            $user["user_id"] = $user_id;
            $user["name"] = $name;
            $user["email"] = $email;
            $user["phone"] = $phone;
            if($clazz ==''){
            $user["clazz"] = "ALL";
            }else{
            $user["clazz"] = $clazz;
            }
            $user["created_at"] = $created_at;
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }

/////////////////////////////////////////////////////////////////////////


// retuns student list
      public function getStudents() {
      $query = "SELECT user_id, name, email, phone, clazz FROM users WHERE clazz !='' ORDER BY user_id ASC";
        if ($result = mysqli_query($this->conn, $query)) {
            return $result;
            mysqli_free_result($result);
        }
    }

    // retuns teacher list
    public function getTeachers() {
    $query = "SELECT user_id, name, email, phone FROM users WHERE clazz ='' ORDER BY user_id ASC";
        if ($result = mysqli_query($this->conn, $query)) {
        return $result;
        mysqli_free_result($result);
        }
    }

    // retuns classes list
    public function getClasses() {
    $query = "SELECT * FROM clazz";
        if ($result = mysqli_query($this->conn, $query)) {
        return $result;
        mysqli_free_result($result);
        }
    }

    // retuns chatrooms list
    public function getChatrooms() {
    $query = "SELECT * FROM chat_rooms";
        if ($result = mysqli_query($this->conn, $query)) {
        return $result;
        mysqli_free_result($result);
        }
    }

    public function registerStudent($name, $email, $clazz, $phone) {
        $response = array();

        $stmt = $this->conn->prepare("INSERT INTO users (name, email, clazz, phone ) values(?, ?, ?, ?)");
        $stmt->bind_param('ssss', $name, $email, $clazz, $phone);

        if ($result = $stmt->execute()) {
            $response['error'] = false;


        }else {
            $response['error'] = true;
            $response['message'] = 'Failed to create user ' . $stmt->error;
        }

        return $response;
    }

    public function registerTeacher($name, $email, $phone) {
        $response = array();

        $stmt = $this->conn->prepare("INSERT INTO users(name, email, phone) values(?, ?, ?)");
        $stmt->bind_param('sss', $name, $email, $phone);

        if ($result = $stmt->execute()) {
            $response['error'] = false;
        } else {
            $response['error'] = true;
            $response['message'] = 'Failed to create user ' . $stmt->error;
        }

        return $response;
    }

/////////////////////////////////////////////////////////////////////////

}

?>
