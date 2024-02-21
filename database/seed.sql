SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `shop`;

CREATE TABLE `categories` (
  `ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `parent` int(11) NOT NULL,
  `Ordering` int(11) DEFAULT NULL,
  `Visibility` tinyint(4) NOT NULL DEFAULT 0,
  `Allow_Comment` tinyint(4) NOT NULL DEFAULT 0,
  `Allow_Ads` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `categories` (`ID`, `Name`, `Description`, `parent`, `Ordering`, `Visibility`, `Allow_Comment`, `Allow_Ads`) VALUES
(9, 'Computers', 'Computers Items', 0, 2, 0, 0, 0),
(10, 'Cell Phones', 'Cell Phones', 0, 32, 0, 0, 0),
(11, 'Clothing', 'Clothing And Fashion', 8, 4, 1, 1, 1),
(12, 'Tools', 'Home Tools', 0, 5, 0, 0, 0),
(17, 'Games', 'Hand Made Games ', 12, 3, 0, 0, 0),
(18, 'Sports', 'cat about sports', 0, 2, 0, 0, 0);

CREATE TABLE `comments` (
  `c_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `comment_date` date NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `comments` (`c_id`, `comment`, `status`, `comment_date`, `item_id`, `user_id`) VALUES
(16, 'consectetur adipisicing elit. Nemo mollitia, quam deserunt id .', 0, '2023-05-22', 35, 36);

CREATE TABLE `items` (
  `Item_ID` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` varchar(255) NOT NULL,
  `Add_Date` date NOT NULL,
  `Country_Made` varchar(255) NOT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `Status` varchar(255) DEFAULT NULL,
  `Rating` smallint(6) DEFAULT NULL,
  `Approve` tinyint(4) NOT NULL DEFAULT 0,
  `Cat_ID` int(11) NOT NULL,
  `Member_ID` int(11) NOT NULL,
  `tags` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `items` (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Image`, `Status`, `Rating`, `Approve`, `Cat_ID`, `Member_ID`, `tags`) VALUES
(34, 'lorem', 'ipsum dolor sit amet consectetur adipisicing elit.', '55', '2023-05-22', 'Morocco', NULL, '1', NULL, 1, 10, 36, 'mmm,kkk'),
(35, 'lorem', 'ipsum dolor sit amet consectetur adipisicing elit.', '12', '2023-05-22', 'Morocco', NULL, '1', NULL, 1, 11, 36, 'mmm,kkk'),
(36, 'lorem', 'ipsum dolor sit amet consectetur adipisicing elit.', '21', '2023-05-22', 'Morocco', NULL, '1', NULL, 1, 10, 36, 'mmm,kkk'),
(38, 'lorem', 'ipsum dolor sit amet consectetur adipisicing elit.', '23', '2023-05-31', 'Zambia', NULL, '1', NULL, 1, 10, 36, 'sport,stuff'),
(39, 'lorem', 'ipsum dolor sit amet consectetur adipisicing elit.', '16', '2023-05-31', 'Morocco', NULL, '1', NULL, 1, 12, 36, 'wwwww'),
(41, 'lorem', 'ipsum dolor sit amet consectetur adipisicing elit.', '10', '2023-05-31', 'Morocco', NULL, '2', NULL, 1, 18, 36, 'ddddd'),
(42, 'lorem', 'ipsum dolor sit amet consectetur adipisicing elit.', '14', '2023-05-31', 'Morocco', NULL, '1', NULL, 1, 18, 1, 'ccccc'),
(45, 'lorem', 'ipsum dolor sit amet consectetur adipisicing elit.', '8', '2023-05-31', 'Morocco', NULL, '2', NULL, 1, 12, 36, 'eeee'),
(46, 'lorem', 'ipsum dolor sit amet consectetur adipisicing elit.', '59', '2023-05-31', 'Morocco', NULL, '2', NULL, 1, 18, 36, 'sport,stuff'),
(47, 'lorem', 'ipsum dolor sit amet consectetur adipisicing elit.', '4', '2023-05-31', 'Morocco', NULL, '1', NULL, 1, 17, 36, 'mmm,kkk');

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `StatusID` tinyint(4) NOT NULL DEFAULT 1,
  `Order_Date` date 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `orders` (`OrderID`, `UserID`, `StatusID`, `Order_Date`) VALUES
(1, 36, 1, '2023-06-11'),
(2, 36, 1, '2023-06-11'),
(3, 40, 1, '2023-06-11'),
(4, 40, 1, '2023-06-11'),
(5, 40, 1, '2023-06-11'),
(6, 40, 1, '2023-06-11'),
(7, 40, 1, '2023-06-11'),
(8, 40, 1, '2023-06-11'),
(9, 52, 1, '2023-06-11');

CREATE TABLE `order_items` (
  `OrderID` int(11) NOT NULL,
  `ItemID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `UnitPrice` decimal(4,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `order_items` (`OrderID`, `ItemID`, `Quantity`, `UnitPrice`) VALUES
(1, 35, 2, '45'),
(6, 46, 1, '59'),
(7, 34, 5, '55'),
(8, 34, 5, '55'),
(9, 46, 5, '59');

CREATE TABLE `order_statuses` (
  `ID` tinyint(4) NOT NULL,
  `Name` varchar(50) NOT NULL DEFAULT '"Pending"'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `order_statuses` (`ID`, `Name`) VALUES
(1, '\"Pending\"'),
(2, '\"shipped\"'),
(3, '\"Delivered\"');

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL COMMENT 'To Identify User',
  `Username` varchar(255) NOT NULL COMMENT 'Username To Login',
  `Password` varchar(255) NOT NULL COMMENT 'Password To Login',
  `Email` varchar(255) NOT NULL,
  `FullName` varchar(255) DEFAULT '',
  `GroupID` int(11) NOT NULL DEFAULT 0 COMMENT 'Identify User Group',
  `TrustStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'Seller Rank',
  `RegStatus` int(11) NOT NULL DEFAULT 0 COMMENT 'User Approval',
  `Date` date NOT NULL,
  `avatar` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`UserID`, `Username`, `Password`, `Email`, `FullName`, `GroupID`, `TrustStatus`, `RegStatus`, `Date`, `avatar`) VALUES
(1, 'root', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'root@gmail.com', 'ahmed imame', 1, 0, 1, '0000-00-00', 'default.png'),
(36, 'bilal', '67a45630518b1fff86ba289aa54b70ba4ed11317', 'bilal@gmail.com', 'Bilal Ali', 0, 0, 1, '2023-05-22', 'default.png'),
(40, 'iman', 'c129b324aee662b04eccf68babba85851346dff9', 'iman@gmail.com', 'Iman Amri', 0, 0, 1, '2023-06-05', 'default.png'),
(41, 'Hamid', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'hamid@gmail.com', 'Hamid El', 0, 0, 1, '2023-06-05', 'default.png'),
(52, 'alan', 'c129b324aee662b04eccf68babba85851346dff9', 'alan@gmail.com', '', 0, 0, 0, '2023-06-11', 'default.png');


ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Name` (`Name`);

ALTER TABLE `comments`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `items_comment` (`item_id`),
  ADD KEY `comment_user` (`user_id`);

ALTER TABLE `items`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `member_1` (`Member_ID`),
  ADD KEY `cat_1` (`Cat_ID`);

ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`,`UserID`,`StatusID`),
  ADD KEY `fk_orders_users` (`UserID`),
  ADD KEY `fk_orders_order_statuses` (`StatusID`);

ALTER TABLE `order_items`
  ADD PRIMARY KEY (`OrderID`,`ItemID`),
  ADD KEY `ItemID` (`ItemID`);

ALTER TABLE `order_statuses`
  ADD PRIMARY KEY (`ID`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`);


ALTER TABLE `categories`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

ALTER TABLE `comments`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

ALTER TABLE `items`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `order_statuses`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'To Identify User', AUTO_INCREMENT=53;


ALTER TABLE `comments`
  ADD CONSTRAINT `comment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `items_comment` FOREIGN KEY (`item_id`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `items`
  ADD CONSTRAINT `cat_1` FOREIGN KEY (`Cat_ID`) REFERENCES `categories` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_1` FOREIGN KEY (`Member_ID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_order_statuses` FOREIGN KEY (`StatusID`) REFERENCES `order_statuses` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_orders_users` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`ItemID`) REFERENCES `items` (`Item_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

create user shop@localhost IDENTIFIED by "shop";
GRANT all on shop.* to shop;

