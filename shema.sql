CREATE DATABASE `mnogoruba`;

CREATE TABLE `user` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `date_reg` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `user_name` VARCHAR(255),
    `user_telephone` VARCHAR(20) UNIQUE,
    `user_email` VARCHAR(255) UNIQUE,
    `user_password` VARCHAR(255),
    `user_ip` VARCHAR(15),
    `user_address` VARCHAR(255),
    `user_rating` INT,
    `user_role` CHAR(55)
);

CREATE TABLE `category_menu` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `category_title` ENUM(
        'poke',
        'rolls',
        'soups',
        'hot',
        'wok',
        'appetizers',
        'sandwiches',
        'desserts',
        'beverages',
        'sauce',
        'constructor-poke'
    ),
    `category_name` ENUM(
        'поке',
        'роллы',
        'супы',
        'горячее',
        'вок',
        'закуски',
        'сэндвичи',
        'десерты',
        'напитки',
        'соус',
        'авторский поке'
    )
);

CREATE TABLE `menu` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `title` VARCHAR(255),
    `img` VARCHAR(255),
    `description` TEXT,
    `price` INT,
    `cooking_time` INT,
    `category_id` INT
);

CREATE TABLE `orders` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `customer_id` INT,
    `total_amount` INT,
    `order_id` CHAR(13) UNIQUE
);

CREATE TABLE `order_items` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `product_id` INT,
    `quantity` INT,
    `unit_price` INT,
    `tableName` ENUM('poke', 'menu'),
    `order_id` CHAR(13)
);

CREATE TABLE `poke` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `title` VARCHAR(255),
    `img` VARCHAR(255),
    `description` TEXT,
    `price` INT,
    `cooking_time` INT,
    `category_id` INT
);

CREATE TABLE `components` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `title` VARCHAR(255),
    `img` VARCHAR(255),
    `price` INT,
    `component_type` ENUM(
        'protein',
        'protein-add',
        'base',
        'filler',
        'topping',
        'sauce',
        'crunch'
    ) NOT NULL,
    `component_name` VARCHAR(255)
);

CREATE TABLE `poke_consists` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `poke_id` INT,
    `component_id` INT,
    `quantity` int
);

CREATE TABLE `cafe_address` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `city` VARCHAR(255),
    `address_name` VARCHAR(255)
);

CREATE TABLE `user_address` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `city` VARCHAR(255),
    `address_name` VARCHAR(255),
    `user_id` INT
);

ALTER TABLE
    `menu`
ADD
    FOREIGN KEY (`category_id`) REFERENCES `category_menu` (`id`);

ALTER TABLE
    `orders`
ADD
    FOREIGN KEY (`customer_id`) REFERENCES `user` (`id`);

ALTER TABLE
    `order_items`
ADD
    FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

ALTER TABLE
    `poke`
ADD
    FOREIGN KEY (`category_id`) REFERENCES `category_menu` (`id`);

ALTER TABLE
    `poke_consists`
ADD
    FOREIGN KEY (`poke_id`) REFERENCES `poke` (`id`);

ALTER TABLE
    `poke_consists`
ADD
    FOREIGN KEY (`component_id`) REFERENCES `components` (`id`);

ALTER TABLE
    `user_address`
ADD
    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);