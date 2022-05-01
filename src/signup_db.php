<?php 
    
    session_start();
    require_once 'config/db.php';

    if (isset($_POST['signup'])) {
        $username = $_POST['username'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $password = $_POST['password'];
        $c_password = $_POST['c_password'];
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_img/'.$image;

        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);   
        if (empty($username)) {
            $_SESSION['error'] = 'กรุณากรอก username';
            header("location: register.php");
        } else if (strlen($_POST['username']) >13) {
            $_SESSION['error'] = 'username ต้องมีความยาวไม่เกิน 12 ตัวอักษร';
            header("location: register.php");
        } else if (empty($firstname)) {
            $_SESSION['error'] = 'กรุณากรอก firstname';
            header("location: register.php");
        } else if (empty($lastname)) {
            $_SESSION['error'] = 'กรุณากรอก lastname';
            header("location: register.php");
        } else if (empty($password)) {
            $_SESSION['error'] = 'กรุณากรอก password';
            header("location: register.php");
        } else if (strlen($_POST['password']) <6) {
            $_SESSION['error'] = 'password ต้องมีความยาวไม่ต่ำกว่า 6 ตัวอักษร';
            header("location: register.php");
        } else if ($uppercase || $lowercase || $number) {
            $_SESSION['error'] = 'password ต้องไม่ใช่ตัวหนังสือ ตัวเลขเรียงกัน';
            header("location: change_pass.php");
        } else if (empty($c_password)) {
            $_SESSION['error'] = 'กรุณายืนยัน password';
            header("location: register.php");
        }else if ($password != $c_password) {
            $_SESSION['error'] = 'รหัสผ่านไม่ตรงกัน';
            header("location: register.php");
        } else if ($image_size > 2000000) {
            $_SESSION['error'] = 'รูปภาพมีขนาดใหญ่เกินไป';
            header("location: register.php");
        } else {
           try {

               $check_username = $conn->prepare("SELECT username FROM users Where username = :username");
               $check_username->bindParam(":username", $username);
               $check_username->execute();
               $row = $check_username->fetch(PDO::FETCH_ASSOC);

               if ($row) {
                 if ($row['username'] == $username ) {
                   $_SESSION['warning'] = "มีผู้ใช้ username ในระบบแล้ว <a href='signin.php'>คลิกที่นี่</a> เพื่อเข้าสู่ระบบ";
                   header ("location: register.php");
              } } else if (!isset($_SESSION['error'])) {
                 
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO users(username, firstname, lastname, password , image)
                                           VALUES( :username, :firstname, :lastname, :password, :image)");
                   $stmt->bindParam(":username", $username);
                   $stmt->bindParam(":firstname", $firstname);
                   $stmt->bindParam(":lastname", $lastname);
                   $stmt->bindParam(":password", $passwordHash);
                   $stmt->bindParam(":image", $image);
                   $stmt->execute(); 
                   
                   $history_pass = $conn->prepare("INSERT INTO password_history (username , password) VALUES(:username , :password) ");
                                $history_pass->bindParam(':username',$username);
                                $history_pass->bindParam(':password',$passwordHash);
                                $history_pass->execute();
                   
                   move_uploaded_file($image_tmp_name, $image_folder);
                   $_SESSION['success'] = "สมัครสมาชิกเรียบร้อยแล้ว! ";
                   header("location: index.php");
               } else {
                   $_SESSION['error'] = "มีบางอย่างผิดพลาด";
                   header("location: register.php");
               }
         


         } catch (PDOException $e) {
               echo $e->getMessage();
           }
       }
   }
?>

