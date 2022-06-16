<?php
include "userauth.php";
include_once "../config.php";


switch(true){
    case isset($_POST['register']):
        //extract the $_POST array values for name, password and email
            $full_name = $_POST['full_name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $country = $_POST['country'];
            $gender = $_POST['gender'];
        registerUser($full_name, $country, $email, $password, $gender);
        break;

    case isset($_POST['login']):
            $email = $_POST['email'];
            $password = $_POST['password'];
        loginUser($email, $password);
        break;
    case isset($_POST["reset"]):
            $email = $_POST['email'];
            $password = $_POST['password'];
        resetPassword($email, $password);
        break;
    case isset($_POST["delete"]):
        $id = $_POST['id'];
        deleteaccount($id);
        break;
    case isset($_GET["all"]):
        getusers();
        break;
}