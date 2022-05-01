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
   <form action="update_profile_db.php" method="post" enctype="multipart/form-data">

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
         <div class="inputBox">
            <div class="mb-3">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" name="firstname" value="<?php echo $row['firstname']; ?>">
            </div>
         </div>
         <div class="inputBox">
            <div class="mb-3">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="lastname" value="<?php echo $row['lastname']; ?>">
            </div>
            
         </div>
        
      </div>
        <div class="mb-3">
                <label for="profile image" class="form-label">Profile Image </label>
                <input type="file" class="form-control" name="image" accept="image/jpg, image/jpeg, image/png">
            </div>
            
      <input type="submit" value="update_profile" name="update_profile" class="btn btn-primary">
      <a href="main.php" class="btn btn-danger">Back</a>
   </form>

</div>

</body>
</html>