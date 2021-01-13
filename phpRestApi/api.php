<?php
require "database.php";
require "user.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$db=(new database())->connection();



$user=new user($db);

$requestMethod = $_SERVER["REQUEST_METHOD"];

if($requestMethod==="GET"){

    if(isset($_GET["userName"])){
        if($user->getUser(htmlspecialchars($_GET["userName"], ENT_QUOTES, 'UTF-8')!==null))
            echo json_encode(array("status"=>"Success","userInfo"=>filterUserInfo($user->getUser(strip_tags($_GET["userName"])))));
        else

            echo json_encode(array("status"=>"Error","message"=>"User Not Found"));
    }
    else

        echo json_encode(array("status"=>"Error","message"=>"Username is NULL"));
}

else if($requestMethod==="POST"){

    $data=json_decode(file_get_contents("php://input"));

    if(isset($data->action)){

        $action=$data->action;
        $userName=$data->userName;
        $userPass=$data->userPass;

        switch ($action){
            case "loginUser":

                $result=$user->checkUser($userName,$userPass);
                if($result!==null)
					echo json_encode(array("status"=>"Success","userInfo"=>filterUserInfo($result)));
                else

                    echo json_encode(array("status"=>"Error","message"=>"User Not Found"));
                break;
            case "signInUser":

                if(isset($data->userPersonalName) && isset($data->userEmail) && isset($data->userPersonalSurname) && isset($data->userTelephone) ){

                    $userEmail=$data->userEmail;
                    $userTelephone=$data->userTelephone;
                    $userPersonalName=$data->userPersonalName;
                    $userPersonalSurname=$data->userPersonalSurname;
                    echo json_encode($user->addUser($userName,$userPersonalName,$userPersonalSurname,$userEmail,$userTelephone,md5($userPass)));
                }
                else

                    echo json_encode(array("status"=>"Error","message"=>"All parameter required"));
                break;
            case "updateUser":

                if(isset($data->userPersonalName) && isset($data->userEmail) && isset($data->userPersonalSurname) && isset($data->userTelephone) ){

                    $userEmail=$data->userEmail;
                    $userTelephone=$data->userTelephone;
                    $userPersonalName=$data->userPersonalName;
                    $userPersonalSurname=$data->userPersonalSurname;
                    echo json_encode($user->updateUser($userName,$userPersonalName,$userPersonalSurname,$userEmail,$userTelephone,md5($userPass)));
                }
                else

                    echo json_encode(array("status"=>"Error","message"=>"All parameter required"));
                break;
            default:

                echo json_encode(array("status"=>"Error","message"=>"Wrong Action"));
        }
    }
    else

        echo json_encode(array("status"=>"Error","message"=>"Action parameter required"));
}



function filterUserInfo($result){
    $userInfo["userName"]=$result["userName"];
    $userInfo["userPersonalName"]=$result["userPersonalName"];
    $userInfo["userPersonalSurname"]=$result["userPersonalSurname"];
    $userInfo["userTelephone"]=$result["userTelephone"];
    $userInfo["userEmail"]=$result["userEmail"];
    return $userInfo;
}




