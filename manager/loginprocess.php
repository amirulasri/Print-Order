<?php
include("../conn.php");
$manageruser = "";
$managerpass = "";
session_start();

if (isset($_POST['manageruser']) && isset($_POST['managerpass'])) {
    if (!empty($_POST['manageruser']) && !empty($_POST['managerpass'])) {
        $manageruser = $_POST['manageruser'];
        $managerpass = $_POST['managerpass'];

        try {
            $statementlogin = $conn->prepare("SELECT * FROM manageruser WHERE manageruser = ?");
            $statementlogin->execute([$manageruser]);
            $result = $statementlogin->fetch();
            if(!empty($result['manageruser'])){
                if(password_verify($managerpass, $result['managerpass'])){
                    setcookie("managerusercookie", $result['manageruser'], time()+2678400, "/"); // 86400 = 1 day
                    header('location: printorder');
                }else{
                    header('location: login?error=3'); //PASSWORD NOT MATCH
                }
            }else{
                header('location: login?error=4'); //USERNAME NOT FOUND
            }
        } catch (PDOException $e) {
            header('location: login?error=2'); //FAILED EXCEPTION
            echo $e->getMessage();
        }
    }
}
