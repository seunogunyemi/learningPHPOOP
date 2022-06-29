<?php
include_once 'Dbh.php';

class UserAuth extends Dbh{
    // private static $db;

    public function __construct(){
        $this->db = new Dbh();
    }

    public function register($fullname, $email, $password, $confirmPassword, $country, $gender){
        $conn = $this->db->connect();
         if($this->getUserByEmail($email)){
             echo "email already exists";
         } else {
            if($this->confirmPasswordMatch($password, $confirmPassword)){
                $sql = "INSERT INTO Students (`fullnames`, `email`, `password`, `country`, `gender`) VALUES ('$fullname','$email', '$password', '$country', '$gender')";
                if($conn->query($sql)){
                   echo "Ok";
                } else {
                    echo "Opps". $conn->error;
                }
            }
    
        }
        
        
    }

protected function login($email, $password){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM Students WHERE email='$email' AND `password`='$password'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $_SESSION['email'] = $email;
            header("Location: ./dashboard.php");
        } else {
            header("Location: forms/login.php");
        }
    }

    public function getUser($email){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM students WHERE email = '$email'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function getAllUsers(){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM Students";
        $result = $conn->query($sql);

        echo"<html>
        <head>
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
        </head>
        <body>
        <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
        <table class='table table-bordered' border='0.5' style='width: 80%; background-color: smoke; border-style: none'; >
        <tr style='height: 40px'>
            <thead class='thead-dark'> <th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th>
        </thead></tr>";
        if($result->num_rows > 0){
            while($data = mysqli_fetch_assoc($result)){
                //show data
                echo "<tr style='height: 20px'>".
                    "<td style='width: 50px; background: gray'>" . $data['id'] . "</td>
                    <td style='width: 150px'>" . $data['fullnames'] .
                    "</td> <td style='width: 150px'>" . $data['email'] .
                    "</td> <td style='width: 150px'>" . $data['gender'] . 
                    "</td> <td style='width: 150px'>" . $data['country'] . 
                    "</td>
                    <td style='width: 150px'> 
                    <form action='action.php' method='post'>
                    <input type='hidden' name='id'" .
                     "value=" . $data['id'] . ">".
                    "<button class='btn btn-danger' type='submit', name='delete'> DELETE </button> </form> </td>".
                    "</tr>";
            }
            echo "</table></table></center></body></html>";
        }
    }

    public function deleteUser($id){
        $conn = $this->db->connect();
        $sql = "DELETE FROM `students` WHERE `id` = '$id'";
        if($conn->query($sql) === TRUE){
            header("location: action.php?all");
        } else {
            header("location: action.php?all");
        }
    }

    public function updateUser($email, $password){
        $conn = $this->db->connect();
        if($this->getUserByEmail($email)){
            $sql = "UPDATE students SET password = '$password' WHERE email = '$email'";
            if($conn->query($sql) === TRUE){
                header("Location: ./dashboard.php?update=success");
            } else {
                header("Location: forms/resetpassword.php?error=1");
            }
            
        }else{
            header("Location: ./dashboard.php?update=error");
        }
        
    }

    public function getUserByEmail($email){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM students WHERE email = '$email'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return true;
        } else {
            return false;
        }
    }
    public function logout($email){
        session_destroy();
        header('Location: index.php');
    }

    public function confirmPasswordMatch($password, $confirmPassword){
        if($password === $confirmPassword){
            return true;
        } else {
            return false;
        }
    }
}