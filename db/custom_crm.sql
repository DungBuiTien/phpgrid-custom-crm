-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 28, 2021 lúc 07:16 PM
-- Phiên bản máy phục vụ: 10.4.19-MariaDB
-- Phiên bản PHP: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `custom_crm`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contact`
--

CREATE TABLE `contact` (
  `id` int(11) UNSIGNED NOT NULL,
  `Contact_name` varchar(100) NOT NULL,
  `Date_of_Initial_Contact` date NOT NULL,
  `Company` varchar(16) DEFAULT NULL,
  `Address` varchar(38) NOT NULL,
  `Phone` varchar(14) NOT NULL,
  `Email` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `contact`
--

INSERT INTO `contact` (`id`, `Contact_name`, `Date_of_Initial_Contact`, `Company`, `Address`, `Phone`, `Email`) VALUES
(1, 'Nguyễn Văn A', '2014-05-09', 'Barnes and Wells', '52 Broadway New York, NY 12345', '(234) 432-2234', 'amir@pr.com'),
(2, 'Nguyễn Văn B', '2014-02-11', 'DEF Fluids', '456 Diesel St Philadelphia, PA 19308', '(765) 765-7755', 'dave@def.com'),
(3, 'Nguyễn Văn C', '2014-09-12', 'Ben and Jerry\'s', '123 Ice Cream Way Burlington, VT 12345', '(123) 432-1223', 'eat@benandjerrys.com'),
(4, 'Nguyễn Văn D', '2014-01-19', 'Pillsbury', '44 Reading Rd Flourtown, NJ 39485', '(555) 555-5555', 'linda@pillsbury.com'),
(5, 'Nguyễn Văn E', '2014-07-01', 'Facetech', '123 Tech Blvd Menlo Park, CA 12345', '(321) 321-1122', 'sally@facetech.com'),
(6, 'Nguyễn Văn F', '2014-10-10', 'Levis', '1 Blue Jean St. Corduroy, CO 12345', '(321) 321-4321', 'tim@levis.com'),
(7, 'Test', '2021-05-28', 'Test', 'Hà Nội', '1234', 'Test');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contact_status`
--

CREATE TABLE `contact_status` (
  `id` int(11) UNSIGNED NOT NULL,
  `status` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `contact_status`
--

INSERT INTO `contact_status` (`id`, `status`) VALUES
(1, 'lead'),
(2, 'opportunity'),
(3, 'customer/won'),
(4, 'archive');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customers`
--

CREATE TABLE `customers` (
  `customers_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `note` varchar(500) NOT NULL,
  `status` int(1) NOT NULL,
  `budget` int(11) DEFAULT NULL,
  `quotation_id` int(11) DEFAULT NULL,
  `update_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `customers`
--

INSERT INTO `customers` (`customers_id`, `contact_id`, `sale_id`, `note`, `status`, `budget`, `quotation_id`, `update_date`) VALUES
(1, 1, 2, 'Note', 1, NULL, NULL, '2021-05-28 16:21:44'),
(2, 2, 2, 'Note', 1, NULL, NULL, '2021-05-28 16:21:47'),
(3, 3, 2, 'Note', 1, NULL, NULL, '2021-05-28 16:21:49'),
(5, 3, 2, 'Test', 1, 0, NULL, '2021-05-28 17:11:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `customer_status`
--

CREATE TABLE `customer_status` (
  `status` int(1) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `customer_status`
--

INSERT INTO `customer_status` (`status`, `value`) VALUES
(0, 'Đơn hàng thất bại'),
(1, 'Đang trao đổi với khách hàng'),
(2, 'Đã gửi báo giá'),
(3, 'Báo giá được chấp nhận');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `distributors`
--

CREATE TABLE `distributors` (
  `distributor_id` int(11) NOT NULL,
  `distributor_name` varchar(255) NOT NULL,
  `distributor_address` varchar(255) NOT NULL,
  `distributor_discount` double NOT NULL,
  `distributor_assign_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `distributors`
--

INSERT INTO `distributors` (`distributor_id`, `distributor_name`, `distributor_address`, `distributor_discount`, `distributor_assign_date`) VALUES
(1, 'Nhà phân phối số 1', 'Hà Nội', 5, '2021-05-26 15:10:46'),
(2, 'Nhà phân phối số 2', 'Đà Nẵng', 5, '2021-05-26 15:10:46'),
(3, 'Nhà phân phối số 3', 'Hải Phòng', 3, '2021-05-26 15:10:46'),
(13, 'Test', 'Test', 1, '2021-05-28 06:12:38');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `distributor_user`
--

CREATE TABLE `distributor_user` (
  `distributor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `distributor_user`
--

INSERT INTO `distributor_user` (`distributor_id`, `user_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(13, 8),
(13, 9),
(13, 10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notes`
--

CREATE TABLE `notes` (
  `id` int(11) UNSIGNED NOT NULL,
  `Date` date DEFAULT NULL,
  `Description` tinytext DEFAULT NULL,
  `Todo_Type_ID` int(11) UNSIGNED NOT NULL,
  `Todo_work_ID` int(11) UNSIGNED NOT NULL,
  `Todo_Due_Date` varchar(255) DEFAULT NULL,
  `Contact_id` int(11) UNSIGNED NOT NULL,
  `Task_Status` int(11) UNSIGNED NOT NULL,
  `Task_Update` varchar(51) DEFAULT NULL,
  `Sales_Rep` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `notes`
--

INSERT INTO `notes` (`id`, `Date`, `Description`, `Todo_Type_ID`, `Todo_work_ID`, `Todo_Due_Date`, `Contact_id`, `Task_Status`, `Task_Update`, `Sales_Rep`) VALUES
(1, '2014-07-11', 'ddddd', 1, 1, '07/23/2014 6:00pm to 8:00pm', 1, 1, '', 1),
(2, '2015-10-10', 'Demo', 1, 1, 'Trước 12h ngày 2021/05/21', 2, 1, 'Test', 2),
(3, '2015-05-21', 'sx', 1, 2, '07/31/2014', 3, 1, '', 2),
(4, '2014-06-01', 'Want to be sure to communicate weekly.', 2, 3, '07/04/2014 10:00am to 10:30am', 4, 1, 'Ongoing', 2),
(5, '2014-01-31', 'Was introduced at textile conference.zzz', 1, 1, '04/09/2014 3:45pm to 4:45pm', 5, 2, 'Great demo. All they needed to seal the deal.<br>', 1),
(6, '2014-11-11', 'Great to have this customer on board!', 1, 4, NULL, 6, 1, NULL, 1),
(7, '2017-01-27', 'test', 1, 2, '', 3, 1, '', 1),
(8, '2017-01-11', 'test123', 1, 5, NULL, 6, 1, NULL, 1),
(9, '2021-05-28', '1', 1, 1, '1', 1, 2, '1234', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `opportunities`
--

CREATE TABLE `opportunities` (
  `opportunities_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `note` varchar(500) NOT NULL,
  `priority` int(1) NOT NULL,
  `budget` double DEFAULT NULL,
  `status` int(1) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `opportunities`
--

INSERT INTO `opportunities` (`opportunities_id`, `contact_id`, `sale_id`, `note`, `priority`, `budget`, `status`, `date_added`) VALUES
(1, 1, 1, '100 Gỗ', 5, 100, 1, '2021-05-28 15:10:31'),
(2, 2, 2, '100 Gỗ', 4, 100, 1, '2021-05-28 15:10:34'),
(3, 3, 2, '100 Gỗ', 3, 100, 0, '2021-05-28 14:17:16'),
(7, 3, 2, 'Test', 1, 0, 2, '2021-05-28 17:11:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL DEFAULT '',
  `description` varchar(500) DEFAULT NULL,
  `price` double NOT NULL,
  `category` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `quantity` int(10) NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `category`, `type`, `quantity`, `update_date`) VALUES
(1, 'Ghế nhựa Hoà phát', 'Ghế nhựa của Hoà Phát', 100000, 'Ghế', 'Xanh', 100, '2021-05-26 14:26:08'),
(2, 'Ghế nhựa Hoà phát', 'Ghế nhựa của Hoà Phát', 100000, 'Ghế', 'Đỏ', 100, '2021-05-26 14:26:08'),
(3, 'Ghế nhựa Hoà phát', 'Ghế nhựa của Hoà Phát', 100000, 'Ghế', 'Vàng', 100, '2021-05-26 14:26:08'),
(4, 'Bàn nhựa Hoà Phát', 'Ghế nhựa của Hoà Phát', 100000, 'Bàn', 'Cỡ vừa', 100, '2021-05-26 14:26:08'),
(5, 'Bàn nhựa Hoà Phát', 'Ghế nhựa của Hoà Phát', 100000, 'Bàn', 'Cỡ nhỏ', 100, '2021-05-26 14:26:08'),
(6, 'Bàn nhựa Hoà Phát', 'Ghế nhựa của Hoà Phát', 100000, 'Bàn', 'Cỡ to', 100, '2021-05-26 14:26:08'),
(7, 'Tủ nhựa Hoà phát', 'Ghế nhựa của Hoà Phát', 100000, 'Tủ', 'Cỡ nhỏ', 100, '2021-05-26 14:26:08'),
(8, 'Tủ nhựa Hoà phát', 'Ghế nhựa của Hoà Phát', 100000, 'Tủ', 'Cỡ vừa', 100, '2021-05-26 14:26:08'),
(9, 'Tủ nhựa Hoà phát', 'Ghế nhựa của Hoà Phát', 100000, 'Tủ', 'Cỡ to', 100, '2021-05-26 14:26:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quotations`
--

CREATE TABLE `quotations` (
  `quotation_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quotation_info`
--

CREATE TABLE `quotation_info` (
  `quotation_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `original_price` double NOT NULL,
  `sale_price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` int(11) UNSIGNED NOT NULL,
  `role` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
(1, 'Sales Rep'),
(2, 'Sales Manager'),
(3, 'Vendor'),
(4, 'Admin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `task_status`
--

CREATE TABLE `task_status` (
  `id` int(11) UNSIGNED NOT NULL,
  `status` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `task_status`
--

INSERT INTO `task_status` (`id`, `status`) VALUES
(1, 'pending'),
(2, 'completed');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `todo_desc`
--

CREATE TABLE `todo_desc` (
  `id` int(11) UNSIGNED NOT NULL,
  `work` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `todo_desc`
--

INSERT INTO `todo_desc` (`id`, `work`) VALUES
(1, 'Follow Up Email'),
(2, 'Phone Call'),
(3, 'Lunch Meeting'),
(4, 'Tech Demo'),
(5, 'Meetup'),
(6, 'Conference'),
(7, 'Others');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `todo_type`
--

CREATE TABLE `todo_type` (
  `id` int(11) UNSIGNED NOT NULL,
  `type` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `todo_type`
--

INSERT INTO `todo_type` (`id`, `type`) VALUES
(1, 'task'),
(2, 'meeting');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `Email` varchar(16) NOT NULL,
  `User_Roles` int(11) UNSIGNED NOT NULL,
  `User_Status` int(11) UNSIGNED NOT NULL,
  `username` char(20) DEFAULT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `Email`, `User_Roles`, `User_Status`, `username`, `PASSWORD`, `name`) VALUES
(1, 'rep@test.com', 1, 1, 'test', '$2y$10$qBV41wx0BdIIzetgiKgoM.1UqffuHMPbQ18hhvJwiJw36M3BK3ZwK', 'Nguyễn Văn A'),
(2, 'rep2@test.com', 1, 1, 'sale', '$2y$10$qBV41wx0BdIIzetgiKgoM.1UqffuHMPbQ18hhvJwiJw36M3BK3ZwK', 'Nguyễn Văn A'),
(3, 'manager@test.com', 2, 1, 'manager', '$2y$10$qBV41wx0BdIIzetgiKgoM.1UqffuHMPbQ18hhvJwiJw36M3BK3ZwK', 'Nguyễn Văn A'),
(4, 'sm@sm.com', 3, 1, 'vendor', '$2y$10$qBV41wx0BdIIzetgiKgoM.1UqffuHMPbQ18hhvJwiJw36M3BK3ZwK', 'Nguyễn Văn A'),
(5, 'test@test.com', 4, 1, 'admin', '$2y$10$qBV41wx0BdIIzetgiKgoM.1UqffuHMPbQ18hhvJwiJw36M3BK3ZwK', 'Nguyễn Văn A'),
(8, '1', 1, 1, 'test1234', '$2y$10$7uXhDlytCs/Tz/FhVEddA.jjwzj.ltla2.wlfx/eLLyRvOpmQZRT.', 'Gh'),
(9, 'lazy.skynet', 1, 1, 'test1', '$2y$10$jkIKMWuTDpzlm9QnAfUGP.VbNUZcN9AIgUNIwFugtYxsRw/Z.0v32', 'Test'),
(10, '1', 2, 1, 'test3', '$2y$10$Qp7WTU9tU51nMYSc6ju2suq1iXUwTHPWaiCVNfxU28YQZsp4/lWJu', 'Gh');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_status`
--

CREATE TABLE `user_status` (
  `id` int(11) UNSIGNED NOT NULL,
  `status` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `user_status`
--

INSERT INTO `user_status` (`id`, `status`) VALUES
(1, 'active'),
(2, 'inactive'),
(3, 'pending approval');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `contact_status`
--
ALTER TABLE `contact_status`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customers_id`);

--
-- Chỉ mục cho bảng `customer_status`
--
ALTER TABLE `customer_status`
  ADD PRIMARY KEY (`status`);

--
-- Chỉ mục cho bảng `distributors`
--
ALTER TABLE `distributors`
  ADD PRIMARY KEY (`distributor_id`);

--
-- Chỉ mục cho bảng `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_EVENT_NAME` (`Todo_Type_ID`),
  ADD KEY `FK_EVENT_TYPE` (`Todo_work_ID`),
  ADD KEY `FK_CONTACT_ID` (`Contact_id`),
  ADD KEY `FK_SALES_ID` (`Sales_Rep`),
  ADD KEY `FK_TASK_STATUS` (`Task_Status`);

--
-- Chỉ mục cho bảng `opportunities`
--
ALTER TABLE `opportunities`
  ADD PRIMARY KEY (`opportunities_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Chỉ mục cho bảng `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`quotation_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `task_status`
--
ALTER TABLE `task_status`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `todo_desc`
--
ALTER TABLE `todo_desc`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `todo_type`
--
ALTER TABLE `todo_type`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_USER_STATUS` (`User_Status`),
  ADD KEY `FK_ROLE` (`User_Roles`);

--
-- Chỉ mục cho bảng `user_status`
--
ALTER TABLE `user_status`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `contact_status`
--
ALTER TABLE `contact_status`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `customers`
--
ALTER TABLE `customers`
  MODIFY `customers_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `distributors`
--
ALTER TABLE `distributors`
  MODIFY `distributor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `opportunities`
--
ALTER TABLE `opportunities`
  MODIFY `opportunities_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `quotations`
--
ALTER TABLE `quotations`
  MODIFY `quotation_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `task_status`
--
ALTER TABLE `task_status`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `todo_desc`
--
ALTER TABLE `todo_desc`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `todo_type`
--
ALTER TABLE `todo_type`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `user_status`
--
ALTER TABLE `user_status`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `FK_CONTACT_ID` FOREIGN KEY (`Contact_id`) REFERENCES `contact` (`id`),
  ADD CONSTRAINT `FK_EVENT_NAME` FOREIGN KEY (`Todo_Type_ID`) REFERENCES `todo_type` (`id`),
  ADD CONSTRAINT `FK_EVENT_TYPE` FOREIGN KEY (`Todo_work_ID`) REFERENCES `todo_desc` (`id`),
  ADD CONSTRAINT `FK_SALES_ID` FOREIGN KEY (`Sales_Rep`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_TASK_STATUS` FOREIGN KEY (`Task_Status`) REFERENCES `task_status` (`id`);

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_ROLE` FOREIGN KEY (`User_Roles`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `FK_USER_STATUS` FOREIGN KEY (`User_Status`) REFERENCES `user_status` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
