<?php 
    session_start();
    require_once 'config/db.php';

    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (empty($username)) {
            $_SESSION['error'] = 'กรุณากรอก username';
            header("location: index.php");
         }else if (empty($password)) {
            $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
            header("location: index.php");
        }  else {
           try {

               $check_data = $conn->prepare("SELECT * FROM users Where username = :username");
               $check_data->bindParam(":username", $username);
               $check_data->execute();
               $row = $check_data->fetch(PDO::FETCH_ASSOC);

                if ($check_data->rowCount() > 0) {

                    if ($username == $row['username']) {
                        if (password_verify($password, $row['password'])) {
                            $_SESSION['user_login'] = $row['id'];
                            $_SESSION['user_username'] = $row['username'];
                            header("location: main.php");
                        } else {
                            $_SESSION['error'] = 'รหัสผ่านผิด';
                            header("location: index.php");
                            }   
                    } else {
                        $_SESSION['error'] = 'username ไม่ถูกต้อง';
                        header("location: index.php");
                }
            } else {
                $_SESSION['error'] = "ไม่มีข้อมูลในระบบ";
                header("location: index.php");
            }

         
            } catch (PDOException $e) {
               echo $e->getMessage();
            }
        }
    }
?>

