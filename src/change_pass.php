<?php 

    session_start();
    require_once 'config/db.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mainpage</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
   
<div class="update-profile">
   <form action="change_pass_db.php" method="post" enctype="multipart/form-data">
   <?php if(isset($_SESSION['error'])) { ?>
            <div class="alert alert-danger" role="alert">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php } ?>
        <?php if(isset($_SESSION['success'])) { ?>
            <div class="alert alert-success" role="alert">
                <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php } ?>   
      <?php
        if (isset($_SESSION['user_login'])) {
            $user_id = $_SESSION['user_login'];
            $stmt = $conn->query("SELECT * FROM users WHERE id = $user_id");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        if($row['image'] == ''){
            echo '<img src="image/default-avatar.png">';
         }else{
            echo '<img src="uploaded_img/'.$row['image'].'">';
         }
      ?>
      <div class="flex">
      <div class="inputBox1">
            <div class="mb-3">
                <label for="old_pass" class="form-label">Old Password</label>
                <input type="password" class="form-control" name="old_pass" >
            </div>
            <div class="mb-3">
                <label for="new_pass" class="form-label">New Password</label>
                <input type="password" class="form-control" name="new_pass" >
            </div>
            <div class="mb-3">
                <label for="confirm_pass" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="confirm_pass">
            </div>
           
         </div>
        
      </div>

            
      <input type="submit" value="Confirm" name="change_pass" class="btn btn-primary">
      <a href="main.php" class="btn btn-danger">Back</a>
   </form>

</div>

</body>
</html>