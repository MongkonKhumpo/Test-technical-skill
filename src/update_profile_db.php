<?php 
    session_start();
    require_once 'config/db.php';

    if (isset($_POST['update_profile'])) {
        $user_id = $_SESSION['user_login'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $update_image = $_FILES['image']['name'];
        $update_image_size = $_FILES['image']['size'];
        $update_image_tmp_name = $_FILES['image']['tmp_name'];
        $update_image_folder = 'uploaded_img/'.$update_image;

        $check_username = $conn->prepare("SELECT username FROM users Where username = :username");
        $check_username->bindParam(":username", $username);
        $check_username->execute();
        $row = $check_username->fetch(PDO::FETCH_ASSOC);
        
        $update_name = $conn->prepare("UPDATE users SET firstname=:firstname , lastname=:lastname WHERE id = $user_id ");
        $update_name->bindParam(':firstname',$firstname);
        $update_name->bindParam(':lastname',$lastname);
        $update_name->execute();

        if(!empty($update_image)){
            if($update_image_size > 2000000){
                $_SESSION['error'] = 'รูปภาพใหญ่เกินไป';
            }else{
                $image_update_query = $conn->prepare("UPDATE users SET image=:image WHERE id = $user_id");
                $image_update_query->bindParam(':image', $update_image);
                
                $image_update_query->execute();
                move_uploaded_file($update_image_tmp_name, $update_image_folder);
                $_SESSION['success'] = 'เปลี่ยนรูปโปรไฟล์สำเร็จ';
                header("location: main.php");
            }
        }
        $_SESSION['success'] = 'เปลี่ยนชื่อสำเร็จ';
        header("location: main.php");
    }
    


?>

