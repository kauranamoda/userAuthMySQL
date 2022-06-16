<?php
session_start();

require_once "../config.php";

function user_exists($email) {
    $conn = db();
    $query = "SELECT email FROM students WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    return ($result) ? true : false;
    $stmt->close();
}

function valid_user($email, $password) {
    $conn = db();
    $query = "SELECT email, password FROM students WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    return ($result) ? true : false;
    $stmt->close();
}

//register users
function registerUser($full_name, $country, $email, $password, $gender){
    //create a connection variable using the db function in config.php
    $conn = db();
    //check if user with this email already exist in the database
   if (user_exists($email)) {
       echo 'The email already registered';
   } else {
    // register user
    $query = "INSERT INTO students (full_name, country, email, password, gender) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $full_name, $country, $email, $password, $gender);
    $stmt->execute();
    if ($stmt->affected_rows === 1) {
        echo 'Registered successfully';
    } else {
        echo 'Registration failed';
    }
    $stmt->close();
   }
}

//login users
function loginUser($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();

    //open connection to the database and check if username exist in the database
    if (user_exists($email)) {
        // validate user
        if (valid_user($email, $password)) {
            $_SESSION['email'] = $_POST['email'];
            header("Location: ../dashboard.php");
            die();
        } else {
            echo 'Invalid email and password combination';
        }
    } else {
        echo 'email does not exist';
    }
}


function resetPassword($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
    //open connection to the database and check if username exist in the database
    if (user_exists($email)) {
        $query = "UPDATE students SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $password, $email);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            echo 'Something went wrong';
        } else {
            echo 'Password reset successful';
        }
    } else {
        echo 'You can\'t do what you are trying to do';
    }
    $stmt->close();
}

function getusers(){
    $conn = db();
    $sql = "SELECT * FROM Students";
    $result = mysqli_query($conn, $sql);
    echo"<html>
    <head></head>
    <body>
    <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
    <table border='1' style='width: 700px; background-color: magenta; border-style: none'; >
    <tr style='height: 40px'><th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th></tr>";
    if(mysqli_num_rows($result) > 0){
        while($data = mysqli_fetch_assoc($result)){
            //show data
            echo "<tr style='height: 30px'>".
                "<td style='width: 50px; background: blue'>" . $data['id'] . "</td>
                <td style='width: 150px'>" . $data['full_name'] .
                "</td> <td style='width: 150px'>" . $data['email'] .
                "</td> <td style='width: 150px'>" . $data['gender'] . 
                "</td> <td style='width: 150px'>" . $data['country'] . 
                "</td>
                <form action='action.php' method='post'>
                <input type='hidden' name='id'" .
                 "value=" . $data['id'] . ">".
                "<td style='width: 150px'> <button type='submit', name='delete'> DELETE </button>".
                "</tr>";
        }
        echo "</table></table></center></body></html>";
    }
    //return users from the database
    //loop through the users and display them on a table
}

 function deleteaccount($id){
     $conn = db();
     $query = "DELETE FROM students WHERE id = ?";
     $stmt = $conn->prepare($query);
     $stmt->bind_param('i', $id);
     $stmt->execute();
     if ($stmt->affected_rows > 0)  {
         echo 'Record deleted successfull';
     } else {
         echo 'Failed to delete record';
     }
     $stmt->close();
 }