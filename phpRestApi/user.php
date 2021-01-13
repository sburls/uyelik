<?php


class user
{
    private $conn;


    public function __construct($myDB)
    {

        $this->conn=$myDB;
    }

    public function getUser($userName){
        $query='Select userName,userPersonalName,userPersonalSurname,userEmail,userTelephone From users where userName=:userName';
        $stmt=$this->conn->prepare($query);

        $stmt->execute(array("userName"=>trim($userName)));
        $rowCount=$stmt->rowCount();
        if($rowCount>0)
            return $stmt->fetch();
        return null;
    }

    public function checkUser($userName,$userPass){

        $query='Select userName,userPersonalName,userPersonalSurname,userEmail,userTelephone From users where userName=:userName and userPassword=:userPass';
        $stmt=$this->conn->prepare($query);
        $stmt->execute(array("userName"=>trim($userName),"userPass"=>trim($userPass)));
        $rowCount=$stmt->rowCount();
        if($rowCount>0)
            return $stmt->fetch();
        return null;
    }

    public function addUser($userName,$userPersonalName,$userPersonalSurname,$userEmail,$userTelephoneNumber,$userPass){

        if($this->getUser($userName)===null){
            $query="Insert Into users(userName,userPassword,userPersonalName,userPersonalSurname,userEmail,userTelephone) Values(:userName,:userPass,:userPersonalName,:userPersonalSurname,:userEmail,:userTelephone)";
            $stmt=$this->conn->prepare($query);
            $stmt->execute(array("userName"=>trim($userName),"userPass"=>trim($userPass),"userPersonalName"=>trim($userPersonalName),"userPersonalSurname"=>trim($userPersonalSurname),"userEmail"=>trim($userEmail),"userTelephone"=>trim($userTelephoneNumber)));

            if($stmt)
                return array("status"=>"Success","message"=>"Add User Successful");
            return array("status"=>"Error","message"=>"Add User Failed");
        }

        return array("status"=>"Error","message"=>"User Already Exist");
    }

    public function updateUser($userName,$userPersonalName,$userPersonalSurname,$userEmail,$userTelephoneNumber,$userPass){

        if($this->getUser($userName)!==null){

            $query="Update users set userPassword=:userPass,userPersonalName=:userPersonalName, userPersonalSurname=:userPersonalSurname,userTelephone=:userTelephone,userEmail=:userEmail where userName=:userName";
            $stmt=$this->conn->prepare($query);
            $stmt->execute(array("userName"=>trim($userName),"userPersonalName"=>trim($userPersonalName),"userPersonalSurname"=>trim($userPersonalSurname),"userEmail"=>trim($userEmail),"userTelephone"=>trim($userTelephoneNumber),"userPass"=>trim($userPass)));

            if($stmt)
                return array("status"=>"Success","message"=>"Update User Successful");
            return array("status"=>"Error","message"=>"Update User Failed");
        }

        return array("status"=>"Error","message"=>"User Not Found");
    }


}