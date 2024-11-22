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
      $message[] = 'Ya está agregado al carrito!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'Producto agregado al carrito!';
   }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Inicio</title>
   <link rel="icon" id="png" href="images/icon2.png">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="inicio">
   <div class="contenedor">
      <h3>Proyectos</h3>
      <a href="acerca.php" class="boton-blanco">Descubrir Más</a>
   </div>
</section>

<section class="show-products">
    <h1 class="title">Proyectos por Categoría</h1>

    <?php 
    $categorias = ['Sistemas', 'Telecomunicaciones', 'Circuitos'];

    foreach ($categorias as $categoria) {
        echo "<h2 class='categoria-title'>" . ucfirst($categoria) . "</h2>";
        echo "<div class='caja-container'>";

        $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE category = '$categoria' LIMIT 6") or die('query failed');
        if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
    ?>
                <div class="caja">
                    <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
                    <div class="name">Nombre: <?php echo $fetch_products['name']; ?></div>
                    <div class="price">Descripción: <?php echo $fetch_products['price']; ?></div>
                    <div class="name">PDF: <a href="<?php echo "uploaded_pdf/" . $fetch_products['pdf_file']; ?>" target="_blank">Ver PDF</a></div>
                </div>
    <?php
            }
        } else {
            echo "<p>No hay productos disponibles en esta categoría.</p>";
        }
        echo "</div>"; // Cierra la caja-container
    }
    ?>
</section>




<?php include 'footer.php'; ?>

<script src="js/script.js"></script>
</body>
</html>
