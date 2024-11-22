<?php

include 'conexion.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

if (isset($_POST['add_product'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $price = mysqli_real_escape_string($conn, $_POST['price']);
   $category = mysqli_real_escape_string($conn, $_POST['category']);
   $pdf_file = $_FILES['pdf_file']['name'];
   $pdf_tmp_name = $_FILES['pdf_file']['tmp_name'];
   $pdf_folder = 'uploaded_pdf/' . $pdf_file;

   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/' . $image;

   $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name = '$name'") or die('fallo en la consulta');

   if (mysqli_num_rows($select_product_name) > 0) {
       $message[] = 'El nombre del proyecto ya ha sido añadido';
   } else {
       if ($image_size > 2000000) {
           $message[] = 'El tamaño de la imagen es demasiado grande';
       } else {
           move_uploaded_file($image_tmp_name, $image_folder);
           move_uploaded_file($pdf_tmp_name, $pdf_folder);
           $stmt = $conn->prepare("CALL agregar_producto(?, ?, ?, ?, ?)");
           $stmt->bind_param("sssss", $name, $price, $category, $image, $pdf_file);
           $stmt->execute();
           $stmt->close();
           $message[] = 'Proyecto añadido exitosamente';
       }
   }
}




if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('fallo en la consulta');
    $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
    unlink('uploaded_img/' . $fetch_delete_image['image']);
    $stmt = $conn->prepare("CALL eliminar_producto(?)");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header('location:admin_productos.php');
}

if (isset($_POST['update_product'])) {
   $update_p_id = $_POST['update_p_id'];
   $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
   $update_price = mysqli_real_escape_string($conn, $_POST['update_price']);
   $update_image = $_FILES['update_image']['name'];
   $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
   $update_image_size = $_FILES['update_image']['size'];
   $update_old_image = $_POST['update_old_image'];
   $update_pdf_file = $_FILES['update_pdf_file']['name'];
   $update_pdf_tmp_name = $_FILES['update_pdf_file']['tmp_name'];
   $update_old_pdf = $_POST['update_old_pdf'];

   if ($update_image_size > 0) {
       move_uploaded_file($update_image_tmp_name, "uploaded_img/$update_image");
       unlink("uploaded_img/$update_old_image");
   } else {
       $update_image = $update_old_image;
   }

   if (!empty($update_pdf_file)) {
       move_uploaded_file($update_pdf_tmp_name, "uploaded_pdf/$update_pdf_file");
       unlink("uploaded_pdf/$update_old_pdf");
   } else {
       $update_pdf_file = $update_old_pdf;
   }

   $stmt = $conn->prepare("CALL actualizar_producto(?, ?, ?, ?, ?)");
   $stmt->bind_param("issss", $update_p_id,$update_name, $update_price, $update_image, $update_pdf_file);
   $stmt->execute();
   $stmt->close();
   header('location:admin_productos.php');
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Proyectos</title>

   <link rel="icon" id="png" href="images/icon2.png">

   <!-- enlace a font awesome cdn -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- enlace a archivo css personalizado de admin -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

   <h1 class="title">Colectivos</h1>

   <form action="" method="post" enctype="multipart/form-data">
   <h3>Añadir Colectivo</h3>
   <input type="text" name="name" class="caja" placeholder="Ingrese el nombre del proyecto" required>
   <input type="text" name="price" class="caja" placeholder="Ingrese una descripción breve" required>
   <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="caja" required>
   <input type="file" name="pdf_file" accept="application/pdf" class="caja" required>
   <select name="category" class="caja" required>
      <option value="">Selecciona una categoría</option>
      <option value="Sistemas">Sistemas</option>
      <option value="Telecomunicaciones">Telecomunicaciones</option>
      <option value="Circuitos">Circuitos</option>
   </select>
   <input type="submit" value="Añadir Proyecto" name="add_product" class="boton">
</form>


   <section class="show-products">
   <div class="caja-container">
      <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('fallo en la consulta');
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
      ?>
    <div class="caja">
   <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
   <div class="name">Nombre: <?php echo $fetch_products['name']; ?></div>
   <div class="price">Descripción: <?php echo $fetch_products['price']; ?></div>
   <div class="name">PDF: <a href="<?php echo "uploaded_pdf/" . $fetch_products['pdf_file']; ?>" target="_blank">Ver PDF</a></div>
   <a href="admin_productos.php?update=<?php echo $fetch_products['id']; ?>" class="boton-opcion">Actualizar</a>
   <a href="admin_productos.php?delete=<?php echo $fetch_products['id']; ?>" class="boton-eliminar" onclick="return confirm('¿Eliminar este proyecto?');">Eliminar</a>
</div>



      <?php
            }
         } else {
            echo "<p>No hay más proyectos disponibles.</p>";
         }
      ?>
   </div>
</section>



<section class="edit-product-form">

   <?php
      if (isset($_GET['update'])) {
         $update_id = $_GET['update'];
         $update_query = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('fallo en la consulta');
         if (mysqli_num_rows($update_query) > 0) {
            while ($fetch_update = mysqli_fetch_assoc($update_query)) {
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="update_p_id" value="<?php echo $fetch_update['id']; ?>">
      <input type="hidden" name="update_old_image" value="<?php echo $fetch_update['image']; ?>">
      <img src="uploaded_img/<?php echo $fetch_update['image']; ?>" alt="">
      <input type="text" name="update_name" value="<?php echo $fetch_update['name']; ?>" class="caja" required placeholder="Ingrese el nombre del producto">
      <input type="text" name="update_price" value="<?php echo $fetch_update['price']; ?>" class="caja" required placeholder="Ingrese el precio del producto">
      <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png" class="caja">
      <input type="submit" value="Actualizar producto" name="update_product" class="boton">
      <input type="submit" value="Cancelar" class="boton" onclick="window.location.href='admin_productos.php'">
   </form>
   <?php
               }
            }
         } else {
            echo '<script>document.querySelector(".edit-product-form").style.display = "none";</script>';
         }
      ?>
</section>




<!-- enlace del archivo js personalizado  -->
<script src="js/script.js"></script>

</body>
</html>
