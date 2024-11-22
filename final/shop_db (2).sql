-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-11-2024 a las 14:12:26
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `shop_db`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizar_producto` (IN `p_product_id` INT, IN `p_name` VARCHAR(100), IN `p_price` VARCHAR(1000), IN `p_image` VARCHAR(100), IN `p_pdf_file` VARCHAR(250))   BEGIN
    UPDATE products 
    SET name = p_name, 
        price = p_price, 
        image = p_image, 
        pdf_file = p_pdf_file 
    WHERE id = p_product_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `agregar_producto` (IN `p_name` VARCHAR(100), IN `p_price` VARCHAR(1000), IN `p_category` VARCHAR(50), IN `p_image` VARCHAR(100), IN `p_pdf_file` VARCHAR(250))   BEGIN
    INSERT INTO products (name, price, category, image, pdf_file) 
    VALUES (p_name, p_price, p_category, p_image, p_pdf_file);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `eliminar_producto` (IN `p_product_id` INT)   BEGIN
    DELETE FROM products WHERE id = p_product_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `loginUser` (IN `p_email` VARCHAR(100), IN `p_password` VARCHAR(100))   BEGIN
    SELECT user_type, name, email, id
    FROM users
    WHERE email = p_email AND password = p_password;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `registerUser` (IN `p_name` VARCHAR(100), IN `p_email` VARCHAR(100), IN `p_password` VARCHAR(100), IN `p_user_type` VARCHAR(20))   BEGIN
    DECLARE v_count INT;
    SET v_count = (SELECT COUNT(*) FROM users WHERE email = p_email);

    IF v_count > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El usuario ya existe';
    ELSE
        INSERT INTO users (name, email, password, user_type) 
        VALUES (p_name, p_email, p_password, p_user_type);
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` varchar(1000) NOT NULL,
  `image` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `pdf_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `category`, `pdf_file`) VALUES
(68, 'Termodinámica', 'Para este proyecto de termodinámica, los estudiantes deberán trabajar en equipos de hasta 4 personas. A continuación, se describen las pautas y requisitos del proyecto: Selección del Tema: Cada grupo debe seleccionar un tema específico relacionado con la termodinámica. El tema puede incluir, pero no está limitado a: Ciclos termodinámicos (como el ciclo de Carnot). Transferencia de calor (conducción, convección y radiación). Leyes de la termodinámica (primera, segunda y tercera leyes). Aplicaciones de la termodinámica en procesos industriales o naturales. Experimento: El grupo debe diseñar y ejecutar un experimento relacionado con el tema seleccionado. El experimento debe permitir observar y medir los fenómenos termodinámicos en estudio. Objetivos del Proyecto: Comprender y aplicar los principios de la termodinámica. Desarrollar habilidades en el diseño y ejecución de experimentos científicos. Analizar y presentar los resultados de manera clara y coherente. Normas IEEE', 'termo.jpeg', 'Sistemas', 'ColectivoDocenteentrega3 (1) (1).pdf'),
(69, 'Industrias 4.0', 'Para este proyecto de termodinámica, los estudiantes deberán trabajar en equipos de hasta 4 personas. A continuación, se describen las pautas y requisitos del proyecto: Selección del Tema: Cada grupo debe seleccionar un tema específico relacionado con la termodinámica. El tema puede incluir, pero no está limitado a: Ciclos termodinámicos (como el ciclo de Carnot). Transferencia de calor (conducción, convección y radiación). Leyes de la termodinámica (primera, segunda y tercera leyes). Aplicaciones de la termodinámica en procesos industriales o naturales. Experimento: El grupo debe diseñar y ejecutar un experimento relacionado con el tema seleccionado. El experimento debe permitir observar y medir los fenómenos termodinámicos en estudio. Objetivos del Proyecto: Comprender y aplicar los principios de la termodinámica. Desarrollar habilidades en el diseño y ejecución de experimentos científicos. Analizar y presentar los resultados de manera clara y coherente. Normas IEEE', 'industria.jpeg', 'Circuitos', 'Terceraentregacolectivodocente.pdf');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`) VALUES
(5, 'Juan David', 'juan1.ruiz@ucp.edu.co', 'c4ca4238a0b923820dcc509a6f75849b', 'user'),
(6, 'yarlinson ', 'yarlinson.mosquera@ucp', '81dc9bdb52d04dc20036dbd8313ed055', 'user'),
(7, 'yarlinson ', 'yarlinson.mosquera@ucp.edu.co', '81dc9bdb52d04dc20036dbd8313ed055', 'admin'),
(8, 'Juan David', 'juandavidruizorozcoc@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'user'),
(9, 'Juan David', 'juanloquillo2323@gmail.com', 'c4ca4238a0b923820dcc509a6f75849b', 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
