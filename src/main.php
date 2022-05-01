<?php 

    session_start();
    require_once 'config/db.php';
 //   if (!isset($_SESSION['user_login'])) {
 //       $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ!';
 //       header('location: signin.php');
 //   }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration System PDO</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container" >
    
        <div class="profile">
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
            // echo '<pre>';
            // print_r($_SESSION);
            // echo '</pre>';
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
        <h3 class="mt-4">Welcome, <?php echo $row['firstname'] . ' ' . $row['lastname'] ?></h3>
        <a href="update_profile.php" class="btn btn-primary">Update Profile</a>
        <a href="change_pass.php" class="btn btn-primary">Change Password</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>
</body>
</html>