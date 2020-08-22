CREATE TABLE `users`
(
    `id`             INT          NOT NULL AUTO_INCREMENT,
    `deleted`        INT(1)       NOT NULL,
    `email`          VARCHAR(100) NOT NULL,
    `username`       VARCHAR(100) NOT NULL,
    `password`       VARCHAR(100) NOT NULL,
    `role`           VARCHAR(45)  NOT NULL,
    `created_date`   VARCHAR(45)  NOT NULL,
    `last_logged_in` VARCHAR(45)  NOT NULL,
    `first_name`     VARCHAR(45)  NOT NULL,
    `last_name`      VARCHAR(45)  NOT NULL,
    `company`        VARCHAR(100) NOT NULL,
    `title`          VARCHAR(45)  NOT NULL,
    `phone`          VARCHAR(45)  NOT NULL,
    `address`        VARCHAR(255) NOT NULL,
    `city`           VARCHAR(45)  NOT NULL,
    `province`       VARCHAR(45)  NOT NULL,
    `country`        VARCHAR(45)  NOT NULL,
    `avatar`         VARCHAR(255) NOT NULL,
    `session_id`     VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`, `username`, `email`)
);

/*****/

INSERT INTO users (deleted, email, username, password, role, created_date, last_logged_in, first_name, last_name, company, title, phone, address, city, province, country, avatar, session_id) VALUES (0, 'admin@gmail.com', 'admin', '$2y$10$z4Dntjs0wOp2lEQojGhAhuf76/TaXj3qMmhsb/TKFmoUY.yKvxYHe', 'admin', '', '','', '', '', '', '', '', '', '', '', '', '');

/*****/