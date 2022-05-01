<?php 
    session_start();
    require_once 'config/db.php';

    $user_id = $_SESSION['user_login'];
    $username = $_SESSION['user_username'];

    if (isset($_POST['change_pass'])) {

        $old_pass = $_POST['old_pass'];
        $new_pass = $_POST['new_pass'];
        $confirm_pass = $_POST['confirm_pass'];

        $uppercase = preg_match('@[A-Z]@', $new_pass);
        $lowercase = preg_match('@[a-z]@', $new_pass);
        $number    = preg_match('@[0-9]@', $new_pass);  

        $check_data = $conn->prepare("SELECT * FROM users Where id = :id");
        $check_data->bindParam(":id", $user_id);
        $check_data->execute();
        $row = $check_data->fetch(PDO::FETCH_ASSOC);
        $test = crypt($old_pass, $row['password']);
        if(empty($old_pass)) {
            $_SESSION['error'] = 'กรุณากรอก Old Password';
            header("location: change_pass.php");
        }else if (empty($new_pass)) {
            $_SESSION['error'] = 'กรุณากรอก New Password';
            header("location: change_pass.php");
         }else if (strlen($_POST['new_pass']) <6) {
            $_SESSION['error'] = 'password ต้องมีความยาวไม่ต่ำกว่า 6 ตัวอักษร';
            header("location: change_pass.php");
        } else if (empty($confirm_pass)) {
            $_SESSION['error'] = 'กรุณากรอก Confirm Password';
            header("location: change_pass.php");
         }  else if ($new_pass != $confirm_pass) {
            $_SESSION['error'] = 'รหัสผ่านไม่ตรงกัน';
            header("location: change_pass.php");
        } else if (!$uppercase || !$lowercase || !$number) {
            $_SESSION['error'] = 'password ต้องไม่ใช่ตัวหนังสือ ตัวเลขเรียงกัน';
            header("location: change_pass.php");
        } else  {
            // echo '<pre>';
            // print_r($_POST);
            // echo '</pre>';
              try {
                    if (password_verify($old_pass, $row['password'])) {
                        if($check_data->rowCount() > 0) {
                            $passwordHash1 = password_hash($new_pass, PASSWORD_DEFAULT);
                            $query=$conn->prepare("SELECT * FROM password_history WHERE username=:username order by id desc limit 5");
                            $query->bindParam(':username', $username, PDO::PARAM_STR);
                            $query-> execute();
                            $resultss = $query->fetchAll(PDO::FETCH_OBJ);
                            // echo '<pre>';
                            // print_r($resultss);
                            // echo '</pre>';
                            $cnt=1;
                            $passwrd=array();
                            $checkpass = array();
                            foreach($resultss as $rt){

                            array_push($passwrd,$rt->password);
                            $checkpass[] = password_verify($new_pass, $rt->password)    ;        
                            }
                            //echo '<pre>';
                            // print_r($passwrd);
                            // echo '</pre>';
                            //echo '<pre>';
                            // print_r($checkpass);
                            // echo '</pre>';
                            if(in_array("1",$checkpass)){
                                $_SESSION['error'] = 'รหัสผ่านซ้ำกับรหัสที่เคยเปลี่ยน 5 ครั้งล่าสุด';
                                header("location: change_pass.php");
                            } else  if ($new_pass = $confirm_pass) {
                                // โค้ดถ้ารหัสตรงกัน
                                $passwordHash = password_hash($new_pass, PASSWORD_DEFAULT);
                                $update_pass = $conn->prepare("UPDATE users SET password=:password WHERE id = $user_id ");
                                $update_pass->bindParam(':password',$passwordHash);
                                $update_pass->execute();
                                
                                $history_pass = $conn->prepare("INSERT INTO password_history (username , password) VALUES(:username , :password) ");
                                $history_pass->bindParam(':username',$username);
                                $history_pass->bindParam(':password',$passwordHash);
                                $history_pass->execute();
                                $_SESSION['success'] = "แก้ไขสำเร็จ! ";
                                header("location: main.php");
                            } else {
                                $_SESSION['error'] = 'รหัสผ่านใหม่ไม่ตรงกัน';
                                header("location: change_pass.php");
                            }
                        }
                    } else {
                        $_SESSION['error'] = 'รหัสผ่านเก่าผิด';
                        header("location: change_pass.php");
                        }            
               } catch (PDOException $e) {
                  echo $e->getMessage();
               }
        }
    }

?>


