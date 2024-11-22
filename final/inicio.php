<?php

include 'conexion.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'already added to cart!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'product added to cart!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>inicio1</title>
   <link rel="icon" id="png" href="images/icon2.png">

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="inicio">

   <div class="contenedor">
      <h3>colectivos</h3>
     
      <a href="acerca.php" class="boton-blanco">Descubrir Más</a>
   </div>

</section>

<section class="show-products">

   <h1 class="title">Los Últimos colectivos</h1>

   <div class="caja-container">

      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `products` LIMIT 6") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
     <form action="" method="post" class="">
     <div class="caja">
                    <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
                    <div class="name">Nombre: <?php echo $fetch_products['name']; ?></div>
                    <div class="price">Descripción: <?php echo $fetch_products['price']; ?></div>
                    <div class="name">PDF: <a href="<?php echo "uploaded_pdf/" . $fetch_products['pdf_file']; ?>" target="_blank">Ver PDF</a></div>
                </div>
     </form>
      <?php
         }
      }else{
         echo '<p class="vacio">Todavía no se han añadido productos!</p>';
      }
      ?>
   </div>

   

</section>

<section class="acerca">

   <div class="flex">

 

      

   </div>



<?php include 'footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>