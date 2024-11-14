-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2024-05-13 10:37:37
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `chouseikun`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `attendee_info`
--

CREATE TABLE `attendee_info` (
  `event_id` int(20) NOT NULL,
  `attendee_name` varchar(100) NOT NULL,
  `comment` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `attendee_info`
--

INSERT INTO `attendee_info` (`event_id`, `attendee_name`, `comment`) VALUES
(4, 'rin', 'comm');

-- --------------------------------------------------------

--
-- テーブルの構造 `attendee_status`
--

CREATE TABLE `attendee_status` (
  `event_id` int(20) NOT NULL,
  `attendee_name` varchar(100) NOT NULL,
  `date` varchar(100) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `attendee_status`
--

INSERT INTO `attendee_status` (`event_id`, `attendee_name`, `date`, `status`) VALUES
(4, 'rin', 'day1', 0),
(4, 'rin', 'day2', 1),
(4, 'rin', 'day3', 2);

-- --------------------------------------------------------

--
-- テーブルの構造 `event_info`
--

CREATE TABLE `event_info` (
  `event_id` int(20) NOT NULL,
  `event_name` varchar(100) NOT NULL,
  `dates` varchar(300) NOT NULL,
  `memo` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `event_info`
--

INSERT INTO `event_info` (`event_id`, `event_name`, `dates`, `memo`) VALUES
(1, 'name', 'asd', 'asd'),
(2, 'name', 'sad', 'asd'),
(3, 'name', 'asd', 'dsa'),
(4, 'name123', 'day1\r\nday2\r\nday3\r\n2024/05/09', 'aadas\r\n123'),
(5, 'nameaaa', 'asd', 'dsa');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `attendee_info`
--
ALTER TABLE `attendee_info`
  ADD PRIMARY KEY (`event_id`);

--
-- テーブルのインデックス `event_info`
--
ALTER TABLE `event_info`
  ADD PRIMARY KEY (`event_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
