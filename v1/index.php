<?php

error_reporting(-1);
ini_set('display_errors', 'On');

require_once '../include/db_handler.php';
require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();


// User login
$app->post('/user/login', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('phone'));

    // reading post params
    $phone = $app->request->post('phone');

    $db = new DbHandler();
    $response = $db->fechUser($phone);

    // echo json response
    echoRespnse(200, $response);
});


/* * *
 * Updating user
 *  we use this url to update user's gcm registration id
 */
$app->put('/user/:id', function($user_id) use ($app) {
    global $app;

    verifyRequiredParams(array('gcm_registration_id'));

    $gcm_registration_id = $app->request->put('gcm_registration_id');

    $db = new DbHandler();
    $response = $db->updateGcmID($user_id, $gcm_registration_id);

    echoRespnse(200, $response);
});
/* * *
 * fetching all chat rooms
 */
$app->post('/user_chat_rooms', function() use ($app){

    verifyRequiredParams(array('clazz'));

    $clazz = $app->request->post('clazz');
    // fetching all user tasks

    $response = array();
    $db = new DbHandler();
    $result = $db->getAllStChatRooms($clazz);

    $response["error"] = false;
    $response["chat_rooms"] = array();

    // pushing single chat room into array
    while ($chat_room = $result->fetch_assoc()) {
        $tmp = array();
        $tmp["chat_room_id"] = $chat_room["chat_room_id"];
        $tmp["name"] = $chat_room["name"];
        $tmp["created_at"] = $chat_room["created_at"];
        array_push($response["chat_rooms"], $tmp);
    }

    echoRespnse(200, $response);
});
/* * *
 * fetching all chat rooms
 */
$app->get('/chat_rooms', function() {
    $response = array();
    $db = new DbHandler();

    // fetching all user tasks
    $result = $db->getAllChatrooms();

    $response["error"] = false;
    $response["chat_rooms"] = array();

    // pushing single chat room into array
    while ($chat_room = $result->fetch_assoc()) {
        $tmp = array();
        $tmp["chat_room_id"] = $chat_room["chat_room_id"];
        $tmp["name"] = $chat_room["name"];
        $tmp["created_at"] = $chat_room["created_at"];
        array_push($response["chat_rooms"], $tmp);
    }

    echoRespnse(200, $response);
});

/**
 * Messaging in a chat room
 * Will send push notification using Topic Messaging
 *  */
$app->post('/chat_rooms/:id/message', function($chat_room_id) {
    global $app;
    $db = new DbHandler();

    verifyRequiredParams(array('user_id','user_name', 'message', 'file_flag', 'file_name', 'file_size'));

    $user_id = $app->request->post('user_id');
    $user_name = $app->request->post('user_name');
    $message = $app->request->post('message');
    $file_flag = $app->request->post('file_flag');
    $file_name = $app->request->post('file_name');
    $file_size = $app->request->post('file_size');

    $response = $db->addMessage($user_id, $chat_room_id, $message, $file_flag, $file_name, $file_size);

    if ($response['error'] == false) {
        require_once __DIR__ . '/../libs/gcm/gcm.php';
        require_once __DIR__ . '/../libs/gcm/push.php';
        $gcm = new GCM();
        $push = new Push();

        // get the user using userid
        //$user = $db->getUser($user_id);
        $user = array();
        $user["sender_id"] = $user_id;
        $user["sender_name"] = $user_name;
        $data = array();
        $data['user'] = $user;
        $data['message'] = $response['message'];
        //$data['chat_room_id'] = $chat_room_id;

        $push->setTitle("UNIVERSITY NOTE MESSAGING");
        $push->setIsBackground(FALSE);
        $push->setFlag(PUSH_FLAG_CHATROOM);
        $push->setData($data);

        // echo json_encode($push->getPush());exit;
        // sending push message to a topic
        $gcm->sendToTopic($chat_room_id, $push->getPush());

        $response['user'] = $user;
        $response['error'] = false;
    }

    echoRespnse(200, $response);
});


/**
 * Sending push notification to a single user
 * We use user's gcm registration id to send the message
 * * */
$app->post('/users/:id/message', function($to_user_id) {
    global $app;
    $db = new DbHandler();

    verifyRequiredParams(array('message'));

    $from_user_id = $app->request->post('user_id');
    $message = $app->request->post('message');

    require_once __DIR__ . '/../libs/gcm/gcm.php';
    require_once __DIR__ . '/../libs/gcm/push.php';
    $gcm = new GCM();
    $push = new Push();

    $fromuser = $db->getUser($from_user_id);
    $user = $db->getUser($to_user_id);

    $msg = array();
    $msg['message'] = $message;
    $msg['message_id'] = '';
    $msg['chat_room_id'] = '';
    $msg['created_at'] = date('Y-m-d G:i:s');

    $data = array();
    $data['user'] = $fromuser;
    $data['message'] = $msg;
    $data['image'] = '';

    $push->setTitle("UNIVERSITY NOTE MESSAGING");
    $push->setIsBackground(FALSE);
    $push->setFlag(PUSH_FLAG_USER);
    $push->setData($data);

    // sending push message to single user
    $gcm->send($user['gcm_registration_id'], $push->getPush());

    $response['user'] = $user;
    $response['error'] = false;


    echoRespnse(200, $response);
});

$app->post('/users/push_test', function() {
    global $app;

    verifyRequiredParams(array('message', 'api_key', 'token'));

    $message = $app->request->post('message');
    $apiKey = $app->request->post('api_key');
    $token = $app->request->post('token');
    $image = $app->request->post('include_image');

    $data = array();
    $data['title'] = 'Google Cloud Messaging';
    $data['message'] = $message;
    if ($image == 'true') {
        $data['image'] = 'http://api.androidhive.info/gcm/panda.jpg';
    } else {
        $data['image'] = '';
    }
    $data['created_at'] = date('Y-m-d G:i:s');

    $fields = array(
        'to' => $token,
        'data' => $data,
    );

    // Set POST variables
    $url = 'https://gcm-http.googleapis.com/gcm/send';

    $headers = array(
        'Authorization: key=' . $apiKey,
        'Content-Type: application/json'
    );
    // Open connection
    $ch = curl_init();

    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $response = array();

    // Execute post
    $result = curl_exec($ch);
    if ($result === FALSE) {
        $response['error'] = TRUE;
        $response['message'] = 'Unable to send test push notification';
        echoRespnse(200, $response);
        exit;
    }

    // Close connection
    curl_close($ch);

    $response['error'] = FALSE;
    $response['message'] = 'Test push message sent successfully!';

    echoRespnse(200, $response);
});


/**
 * Sending push notification to multiple users
 * We use gcm registration ids to send notification message
 * At max you can send message to 1000 recipients
 * * */
$app->post('/users/message', function() use ($app) {

    $response = array();
    verifyRequiredParams(array('user_id', 'to', 'message'));

    require_once __DIR__ . '/../libs/gcm/gcm.php';
    require_once __DIR__ . '/../libs/gcm/push.php';

    $db = new DbHandler();

    $user_id = $app->request->post('user_id');
    $to_user_ids = array_filter(explode(',', $app->request->post('to')));
    $message = $app->request->post('message');

    $user = $db->getUser($user_id);
    $users = $db->getUsers($to_user_ids);

    $registration_ids = array();

    // preparing gcm registration ids array
    foreach ($users as $u) {
        array_push($registration_ids, $u['gcm_registration_id']);
    }

    // insert messages in db
    // send push to multiple users
    $gcm = new GCM();
    $push = new Push();

    // creating tmp message, skipping database insertion
    $msg = array();
    $msg['message'] = $message;
    $msg['message_id'] = '';
    $msg['chat_room_id'] = '';
    $msg['created_at'] = date('Y-m-d G:i:s');

    $data = array();
    $data['user'] = $user;
    $data['message'] = $msg;
    $data['image'] = '';

    $push->setTitle("Google Cloud Messaging");
    $push->setIsBackground(FALSE);
    $push->setFlag(PUSH_FLAG_USER);
    $push->setData($data);

    // sending push message to multiple users
    $gcm->sendMultiple($registration_ids, $push->getPush());

    $response['error'] = false;

    echoRespnse(200, $response);
});

$app->post('/users/send_to_all', function() use ($app) {

    $response = array();
    verifyRequiredParams(array('user_id', 'message'));

    require_once __DIR__ . '/../libs/gcm/gcm.php';
    require_once __DIR__ . '/../libs/gcm/push.php';

    $db = new DbHandler();

    $user_id = $app->request->post('user_id');
    $message = $app->request->post('message');

    require_once __DIR__ . '/../libs/gcm/gcm.php';
    require_once __DIR__ . '/../libs/gcm/push.php';
    $gcm = new GCM();
    $push = new Push();

    // get the user using userid
    $user = $db->getUser($user_id);

    // creating tmp message, skipping database insertion
    $msg = array();
    $msg['message'] = $message;
    $msg['message_id'] = '';
    $msg['chat_room_id'] = '';
    $msg['created_at'] = date('Y-m-d G:i:s');

    $data = array();
    $data['user'] = $user;
    $data['message'] = $msg;
    $data['image'] = 'http://api.androidhive.info/gcm/panda.jpg';

    $push->setTitle("Google Cloud Messaging");
    $push->setIsBackground(FALSE);
    $push->setFlag(PUSH_FLAG_USER);
    $push->setData($data);

    // sending message to topic `global`
    // On the device every user should subscribe to `global` topic
    $gcm->sendToTopic('global', $push->getPush());

    $response['user'] = $user;
    $response['error'] = false;

    echoRespnse(200, $response);
});

/**
 * Fetching single chat room including all the chat messages
 *  */
$app->get('/chat_rooms/:id', function($chat_room_id) {
    global $app;
    $db = new DbHandler();

        $response["error"] = "false";
        $response["messages"]= array();
        $response['chat_room'] = array();
        $messages =  $db->getChatRoomsMessages($chat_room_id);
        $chat_room  = $db->getChatRoom($chat_room_id);
        foreach ($messages as $key => $message) {
            // message node
                $cmt = array();
                $cmt["message"] = $message["message"];
                $cmt["message_id"] = $message["message_id"];
                $cmt["created_at"] = $message["created_at"];

                // user node
                $user = array();
                $user['user_id'] = $message['user_id'];
                $user['username'] = $message['name'];
                $cmt['user'] = $user;

                array_push($response["messages"], $cmt);
        }

        foreach ($chat_room as $key => $room) {
            $tmp = array();
                $tmp["chat_room_id"] = $room["chat_room_id"];
                $tmp["name"] = $room["name"];
                $tmp["created_at"] = $room["created_at"];
                $response['chat_room'] = $tmp;
        }

    echoRespnse(200, $response);
});

////////////////////////////////////////////////////////////////////////
$app->get('/users/students', function() {
    $response = array();
    $db = new DbHandler();

    // fetching all user tasks
    $result = $db->getStudents();

    if (!$result) {
        throw new Exception("Database Error");
    }

    while ($student = $result->fetch_assoc()) {
        $tmp = array();

        $tmp["id"] = $student["user_id"];
        $tmp["name"] = $student["name"];
        $tmp["email"] = $student["email"];
        $tmp["class"] = $student["clazz"];
        $tmp["phone"] = $student["phone"];
        array_push($response, $tmp);
    }

    echoRespnse(200, $response);
});

$app->get('/users/teachers', function() {
    $response = array();
    $db = new DbHandler();

    $result = $db->getTeachers();


    while ($teacher = $result->fetch_assoc()) {
        $tmp = array();

        $tmp["id"] = $teacher["user_id"];
        $tmp["name"] = $teacher["name"];
        $tmp["email"] = $teacher["email"];
        $tmp["phone"] = $teacher["phone"];
        array_push($response, $tmp);
    }

    echoRespnse(200, $response);
});

$app->get('/classes', function() {
    $response = array();
    $db = new DbHandler();

    $result = $db->getClasses();


    while ($classes = $result->fetch_assoc()) {
        $tmp = array();

        $tmp["name"] = $classes["clazz_name"];
        $tmp["department"] = $classes["clazz_department"];
        $tmp["level"] = $classes["clazz_level"];
        array_push($response, $tmp);
    }

    echoRespnse(200, $response);
});

$app->get('/chatrooms', function() {
    $response = array();

    $db = new DbHandler();

    $result = $db->getChatRooms();


    while ($chatrooms = $result->fetch_assoc()) {
        $temp = array();
        $temp["name"] = $chatrooms["name"];
        $temp["classes"] = $chatrooms["classes"];
        array_push($response, $temp);
    }

    echoRespnse(200, $response);
});

function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}

$app->post('/users/students', function() {
    global $app;
    $db = new DbHandler();

    verifyRequiredParams(array('name', 'email', 'class', 'phone'));

    $name = $app->request->post('name');
    $email = $app->request->post('email');
    $clazz = $app->request->post('class');
    $phone = $app->request->post('phone');

    $response = $db->registerStudent($name, $email, $clazz, $phone);

    if ($response['error'] == false) {
        $response['error'] = false;
    }

    echoRespnse(200, $response);
});

$app->post('/users/teachers', function() {
    global $app;
    $db = new DbHandler();

    verifyRequiredParams(array('name', 'email', 'phone'));

    $name = $app->request->post('name');
    $email = $app->request->post('email');
    $phone = $app->request->post('phone');
    console_log($name);
    $response = $db->registerTeacher($name, $email, $phone);

    if ($response['error'] == false) {
        $response['error'] = false;
    }

    echoRespnse(200, $response);
});

////////////////////////////////////////////////////////////////////////

/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoRespnse(400, $response);
        $app->stop();
    }
}

/**
 * Validating email address
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoRespnse(400, $response);
        $app->stop();
    }
}

function IsNullOrEmptyString($str) {
    return (!isset($str) || trim($str) === '');
}

/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

$app->run();
?>
