<?php

include 'conexion.php';
session_start();

if (isset($_POST['submit'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, md5($_POST['password']));

    $stmt = $conn->prepare("CALL loginUser(?, ?)");
    $stmt->bind_param("ss", $email, $pass);
    $stmt->execute();
    $stmt->bind_result($user_type, $name, $email, $id);
    $stmt->fetch();
    $stmt->close();

    if ($user_type) {
        if ($user_type == 'admin') {
            $_SESSION['admin_name'] = $name;
            $_SESSION['admin_email'] = $email;
            $_SESSION['admin_id'] = $id;
            header('location:admin_pagina.php');
        } elseif ($user_type == 'user') {
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_id'] = $id;
            header('location:inicio.php');
        }
    } else {
        $message[] = '¡correo electrónico o contraseña incorrectos!';
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>iniciar sesión</title>
   <link rel="icon" id="png" href="images/icon2.png">

   <!-- enlace de cdn de font awesome  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- enlace del archivo css personalizado  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="mensaje">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
   
<div class="form-container">

   <form action="" method="post">
      <h3>iniciar sesión ahora en la página de colectivos U.C.P</h3>
      <input type="email" name="email" placeholder="ingresa tu correo electrónico" required class="caja">
      <input type="password" name="password" placeholder="ingresa tu contraseña" required class="caja">
      <input type="submit" name="submit" value="iniciar sesión ahora" class="boton">
      <p>¿no tienes una cuenta? <a href="registro.php">regístrate ahora</a></p>
   </form>

</div>

</body>
</html>
