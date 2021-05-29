-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 29, 2021 lúc 06:43 PM
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
(1, 'Khách Hàng A', '2014-05-09', 'UET', '144 Xuân Thuỷ, Cầu Giấy, Hà Nội', '0981111111', 'email_A@gmail.com'),
(2, 'Khách Hàng B', '2014-02-11', 'ULIS', '106 Hoàng Quốc Việt, Hà Nội', '0345611112', 'email_B@gmail.com'),
(3, 'Khách Hàng C', '2014-09-12', 'VNU', '123 Xuân Thuỷ, Cầu Giấy, Hà Nội', '0322644111', 'email_C@gmail.com'),
(4, 'Khách Hàng D', '2014-01-19', 'UET', '23 Lạc Long Quân, Cầu Giấy, Hà Nội', '0961232311', 'email_D@gmail.com'),
(5, 'Khách Hàng E', '2014-07-01', 'ULIS', '20 Phan Bội Châu, Hoàn Kiếm, Hà Nội', '0987123123', 'email_E@gmail.com'),
(6, 'Khách Hàng F', '2014-10-10', 'VNU', '127 Lạc Long Quân, Tây Hồ, Hà Nội', '0312412412', 'email_F@gmail.com'),
(7, 'Khách Hàng G', '2021-05-28', 'UET', '23 Ngô Tất Tố, Hoàng Mai, Hà Nội', '0321355112', 'email_G@gmail.com'),
(8, 'Khách Hàng J', '2021-05-29', 'ULIS', '20 Hồ Tùng Mậu, Nam Từ Liêm, Hà Nội', '0985412312', 'email_H@gmail.com');

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
(1, 1, 2, 'Khách hàng cần sản phẩm gấp', 1, NULL, 5, '2021-05-29 16:20:34'),
(2, 2, 2, 'Khách hàng yêu cầu đưa báo giá trước 09/06', 1, 5000000, NULL, '2021-05-29 16:21:53'),
(3, 3, 2, 'Khách hàng yêu cầu chi phí thấp nhất', 1, NULL, NULL, '2021-05-29 16:21:23'),
(5, 3, 2, 'Chưa chốt được chi phí cuối cùng', 2, NULL, NULL, '2021-05-29 16:22:09');

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
(13, 'Nhà phân phối số 4', 'Hồ Chí Minh', 1, '2021-05-29 16:22:48');

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
(13, 8),
(13, 9),
(13, 10),
(1, 11);

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
(1, '2021-05-21', 'Gửi báo giá cho khách hàng', 1, 1, 'Tử 27/5 đến 1/6', 1, 1, '', 1),
(2, '2021-05-13', 'Khách hàng cần demo sản phẩm', 1, 4, 'Trước 12h ngày 2021/05/21', 2, 1, 'Chưa chuẩn bị được demo', 2),
(3, '2021-05-21', 'Khách hàng cần báo giá', 1, 2, '07/31/2021', 3, 1, '', 2),
(4, '2021-06-01', 'Khách hàng cần liên lạc lại ngay khi có thể', 1, 2, 'Trước 12/7', 4, 1, 'Không gọi được cho khách hàng', 2),
(5, '2021-05-22', 'Gặp mặt tại công ti khách hàng', 2, 1, '9h30 ngày 29/5/2021', 5, 2, NULL, 1),
(6, '2021-05-29', 'Liên lạc chốt báo giá', 1, 4, 'Trước 01/06', 6, 1, NULL, 1),
(7, '2021-05-31', 'Liên lạc chốt báo giá', 1, 2, 'Trước 02/07', 3, 1, '', 1),
(8, '2021-05-24', 'Gặp mặt khách hàng', 2, 5, '12h ngày 30/5', 6, 1, ' ', 1),
(9, '2021-05-28', 'Demo sản phẩm', 2, 1, '9h ngày 20/6', 1, 2, 'Đã chuẩn bị demo', 2),
(10, '2021-05-29', 'Gửi báo giá', 1, 1, 'Trước 07/06', 1, 2, 'Đã gửi báo giá', 2);

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
(1, 1, 1, 'Khách hàng đang xem xét sản phẩm của chúng ta', 5, 100, 1, '2021-05-29 16:29:51'),
(2, 2, 2, 'Khách hàng đã liên hệ trước', 4, 100, 1, '2021-05-29 16:30:22'),
(3, 3, 2, 'Khách hàng mới được giới thiệu sản phẩm', 3, 100, 0, '2021-05-29 16:30:40'),
(7, 3, 2, 'Khách hàng đang tham khảo giá', 3, 0, 2, '2021-05-29 16:30:56'),
(8, 4, 2, 'Khách hàng cần tham khảo báo giá của bên chúng ta', 4, 0, 1, '2021-05-29 16:37:49');

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
  `sale_id` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `original_price` int(11) NOT NULL,
  `sale_price` int(11) NOT NULL,
  `update_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `quotations`
--

INSERT INTO `quotations` (`quotation_id`, `contact_id`, `sale_id`, `status`, `original_price`, `sale_price`, `update_date`) VALUES
(5, 1, 2, 2, 285000, 400000, '2021-05-29 14:23:51');

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

--
-- Đang đổ dữ liệu cho bảng `quotation_info`
--

INSERT INTO `quotation_info` (`quotation_id`, `product_id`, `quantity`, `original_price`, `sale_price`) VALUES
(5, 1, 1, 95000, 100000),
(5, 2, 1, 95000, 200000),
(5, 3, 1, 95000, 100000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quotation_status`
--

CREATE TABLE `quotation_status` (
  `status` int(1) NOT NULL,
  `value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `quotation_status`
--

INSERT INTO `quotation_status` (`status`, `value`) VALUES
(0, 'Báo giá bị từ chối'),
(1, 'Báo giá mới được tạo'),
(2, 'Báo giá đã được gửi'),
(3, 'Báo giá được chấp thuận');

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
(1, 'Nhân viên bán hàng'),
(2, 'Quản lý'),
(3, 'Nhà cung cấp'),
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
(1, 'Đang thực hiện'),
(2, 'Đã hoàn thành');

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
(1, 'Gửi Email cho khách hàng'),
(2, 'Gọi điện thoại tư vấn'),
(3, 'Gặp mặt khách hàng'),
(4, 'Demo sản phẩm'),
(5, 'Tư vấn giải pháp'),
(6, 'Gửi báo giá'),
(7, 'Khác');

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
(1, 'Công việc'),
(2, 'Buổi gặp mặt');

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
(1, 'rep@test.com', 1, 1, 'test', '$2y$10$qBV41wx0BdIIzetgiKgoM.1UqffuHMPbQ18hhvJwiJw36M3BK3ZwK', 'Nhân Viên'),
(2, 'rep2@test.com', 1, 1, 'sale', '$2y$10$qBV41wx0BdIIzetgiKgoM.1UqffuHMPbQ18hhvJwiJw36M3BK3ZwK', 'Nguyễn Văn A'),
(3, 'manager@test.com', 2, 1, 'manager', '$2y$10$qBV41wx0BdIIzetgiKgoM.1UqffuHMPbQ18hhvJwiJw36M3BK3ZwK', 'Quản Lý'),
(4, 'sm@sm.com', 3, 1, 'vendor', '$2y$10$qBV41wx0BdIIzetgiKgoM.1UqffuHMPbQ18hhvJwiJw36M3BK3ZwK', 'Nhà Cung Cấp'),
(5, 'test@test.com', 4, 1, 'admin', '$2y$10$qBV41wx0BdIIzetgiKgoM.1UqffuHMPbQ18hhvJwiJw36M3BK3ZwK', 'Admin'),
(8, 'test2@gmail.com', 1, 1, 'test1234', '$2y$10$7uXhDlytCs/Tz/FhVEddA.jjwzj.ltla2.wlfx/eLLyRvOpmQZRT.', 'Test'),
(9, 'lazy.skynet', 1, 1, 'test1', '$2y$10$jkIKMWuTDpzlm9QnAfUGP.VbNUZcN9AIgUNIwFugtYxsRw/Z.0v32', 'Test'),
(10, 'test3@gmail.com', 2, 1, 'test3', '$2y$10$Qp7WTU9tU51nMYSc6ju2suq1iXUwTHPWaiCVNfxU28YQZsp4/lWJu', 'Test'),
(11, 'bdung@gmail.com', 1, 1, 'bdung', '$2y$10$VxeZsznd2EV3rp/K2h9Oa.67n90mZf3VFixZmuczmp8.VjwHp.TMm', 'Bui Dung');

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
(1, 'Đang hoạt động'),
(2, 'Ngừng hoạt động'),
(3, 'Chờ được cấp phép');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `contact`
--
ALTER TABLE `contact`
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
-- Chỉ mục cho bảng `quotation_status`
--
ALTER TABLE `quotation_status`
  ADD PRIMARY KEY (`status`);

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `opportunities`
--
ALTER TABLE `opportunities`
  MODIFY `opportunities_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `quotations`
--
ALTER TABLE `quotations`
  MODIFY `quotation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
