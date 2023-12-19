CREATE DATABASE mnogoruba;

USE mnogoruba;

CREATE TABLE `user` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `date_reg` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `telephone` VARCHAR(20) UNIQUE,
    `email` VARCHAR(255) UNIQUE,
    `password` VARCHAR(255),
    `user_ip` VARCHAR(15),
    `address` VARCHAR(255),
    `rating` INT,
    `role` CHAR(55)
);


CREATE TABLE `category_menu` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `category__name` ENUM(
        'поке',
        'роллы',
        'супы',
        'горячее',
        'вок',
        'закуски',
        'сэндвичи',
        'десерты',
        'напитки',
        'соус'
    )
);

CREATE TABLE `menu` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255),
    `img` VARCHAR(255),
    `description` VARCHAR(255),
    `price` INT,
    `cooking_time` INT,
    `category__id` INT,
    FOREIGN KEY (`category__id`) REFERENCES `category_menu` (`id`)
);

CREATE TABLE `order` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_time` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `menu_id` INT,
    FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`)
);

CREATE TABLE `component` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255),
    `img` VARCHAR(255),
    `price` INT,
    `component_type` ENUM(
        'protein',
        'base',
        'filler',
        'topping',
        'sauce',
        'crunch'
    ) NOT NULL
);

CREATE TABLE `poke` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255),
    `img` VARCHAR(255),
    `description` VARCHAR(255),
    `price` INT
);

CREATE TABLE `poke_component` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `component_id` INT,
    `poke_id` INT,
    FOREIGN KEY (`component_id`) REFERENCES `component` (`id`),
    FOREIGN KEY (`poke_id`) REFERENCES `poke` (`id`)
);
