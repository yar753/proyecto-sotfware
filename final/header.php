<?php
include 'conexion.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('location:login.php');
    exit();
}



?>

<header class="header">

   <div class="header-1">
      <div class="flex">
         <div class="share">
            
               <a href="https://www.instagram.com/ucatolicadepereira/" class="fab fa-instagram"></a>
            
      
      </div>
   </div>

   <div class="header-2">
      <div class="flex">
         <a href="inicio.php" class="logo">Proyectos U.C.P.</a>

         <nav class="navbar">
         <a href="categorias.php" >Categorías</a>
            <a href="inicio.php">inicio</a>
            <a href="acerca.php">acerca de</a>
      
         </nav>

         <div class="icons">
            <a href="buscar_pagina.php" class="fas fa-search"> </a>
            <div id="menu-btn" class="fas fa-bars"> </div>
            <div id="user-btn" class="fas fa-user ">  <div class="account-box">
         <p>username : <span><?php echo $_SESSION['admin_name']; ?></span></p>
         <p>email : <span><?php echo $_SESSION['admin_email']; ?></span></p>
         <div>new <a href="login.php">login</a> | <a href="registro.php">registro</a></div> </div>
            
      </div>
            <?php
            
            ?>
      
         </div>
      </div>
      </div>
   </div>

</header>
