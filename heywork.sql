-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 26, 2026 at 12:05 AM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `heywork`
--

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` int(11) NOT NULL,
  `hotel_name` varchar(50) NOT NULL,
  `location` varchar(255) NOT NULL,
  `website` varchar(50) DEFAULT NULL,
  `description` text NOT NULL,
  `size` int(11) DEFAULT NULL,
  `logo` text NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` smallint(1) NOT NULL,
  `deleted_at` datetime NOT NULL,
  `deleted_by` smallint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(1) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `position` varchar(100) NOT NULL,
  `job_date_start` date NOT NULL,
  `job_date_end` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `category` enum('daily_worker','casual') DEFAULT 'daily_worker',
  `fee` int(1) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `requirement_skill` text DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `created_at` datetime DEFAULT NULL,
  `created_by` int(1) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `hotel_id`, `position`, `job_date_start`, `job_date_end`, `start_time`, `end_time`, `category`, `fee`, `location`, `description`, `requirement_skill`, `status`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 'Housekeeping', '2026-01-19', '2026-01-20', '07:30:00', '17:30:00', 'daily_worker', 100000, NULL, NULL, NULL, 'open', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 1, 'Public Area', '2026-01-21', '2026-01-22', '16:21:45', '16:22:45', 'casual', 150000, NULL, NULL, NULL, 'open', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` int(1) NOT NULL,
  `job_id` int(1) NOT NULL,
  `user_id` int(1) NOT NULL,
  `status` enum('pending','accepted','rejected','completed') DEFAULT 'pending',
  `applied_at` datetime DEFAULT NULL,
  `accepted_at` datetime DEFAULT NULL,
  `accepted_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`id`, `job_id`, `user_id`, `status`, `applied_at`, `accepted_at`, `accepted_by`) VALUES
(1, 1, 2, 'completed', '2026-01-19 16:53:39', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_attendances`
--

CREATE TABLE `job_attendances` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('checkin','checkout') NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `photo_path` varchar(255) NOT NULL,
  `device_info` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` smallint(1) NOT NULL,
  `deleted_at` datetime NOT NULL,
  `deleted_by` smallint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_attendances`
--

INSERT INTO `job_attendances` (`id`, `job_id`, `application_id`, `user_id`, `type`, `latitude`, `longitude`, `photo_path`, `device_info`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 1, 2, 'checkin', -6.5944441, 106.7891234, 'uploads/attendance/checkin_1_2_1705654800.jpg', 'Android 13 | Samsung A34 | Chrome Mobile', '2026-01-19 07:28:45', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0),
(2, 1, 1, 2, 'checkout', -6.5944510, 106.7891102, 'uploads/attendance/checkout_1_2_1705689600.jpg', 'Android 13 | Samsung A34 | Chrome Mobile', '2026-01-19 17:32:10', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(1) NOT NULL,
  `job_id` int(1) NOT NULL,
  `application_id` int(1) NOT NULL,
  `worker_id` int(1) NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `review` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refresh_tokens`
--

CREATE TABLE `refresh_tokens` (
  `id` int(1) NOT NULL,
  `user_id` int(1) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `refresh_tokens`
--

INSERT INTO `refresh_tokens` (`id`, `user_id`, `token`, `expires_at`, `created_at`) VALUES
(1, 1, '9d0cb9b1e472fbf4499260f3eb10aca208c260e0c0239964ab0730eb766d31c2bd678a6f872119c6814977e4ecbedf4ae035737786511af1679477d403dbd658', '2026-02-17 08:06:41', '2026-01-18 08:06:41'),
(2, 1, 'fa9523e98358ba1244822259c6cd6afa0a935e708e311b21f4d285e3b43f6f5c5df33b7b12be71663a2ca2dddf5401238509ab836d14cca49674a2c4057a79ae', '2026-02-17 08:08:35', '2026-01-18 08:08:35'),
(3, 1, '96ad5b02074ffc7e9f718ce7e26b9674c3e3c726adab30ff8e2683c46ecd2fd0c3c29511a817cb98aeebb58aad3b86accae43cc22919f6a3a1bd12e544530328', '2026-02-17 08:08:49', '2026-01-18 08:08:49'),
(4, 1, '9c25c660966db227cc7245335bf68bc86bfdbd53aa454a4bdf839d46745eee91cf87c8a82cdb9d11a57c374da71df5687b744582bbedec8e1a3d0306d2c6059a', '2026-02-17 08:34:43', '2026-01-18 08:34:43'),
(5, 1, 'c5363c19e7ad13e1c0e6c08e0ae7a487727b1af8b89f76525fdf06d0a38cd572dd47667b36fe2ee768ffbaf5f160d1aebc639555c5b280cc5c5695811de4eda5', '2026-02-17 08:59:05', '2026-01-18 08:59:05'),
(6, 1, '3a27a24b5f3b84f680133fabc852de907a85b5ed4e14dd7b5e3f448477967ca294a46968739a904a1ca5883ca86736c7c32a0e5d5808ef1ca13811a1c746457b', '2026-02-17 09:02:49', '2026-01-18 09:02:49'),
(7, 1, '4d2ebfaf3ade27d8e43663be70edc9c14752920aeb43b02c60931634d96c71a184b36a3cc050d734534c89492d7bca81862bd90508da3cf22ed502bc58aa3b6c', '2026-02-17 09:17:19', '2026-01-18 09:17:19'),
(8, 1, '6a0c8a17ffe2d69bee95401dbe55a3e3f69518f36a2903c0478234aae6ecd68c816e988381175beb3f2fa5fdaa2444dbe260a225ec4dbc62b989387eb9f558a1', '2026-02-17 09:24:12', '2026-01-18 09:24:12'),
(9, 1, '9a7479f1d84c99408a667daa701983d05615490be047d9f69ab9dda1ecf4689874fae12ccc7ee0c6932895b3131417e546de0db83df8c0980a13f99611255f45', '2026-02-17 09:24:39', '2026-01-18 09:24:39'),
(10, 1, 'f71f9f805d4f254b5834c1802ec0aa7727807e80f97dca4a8b378d0c4a4781bc1e044b7e8ec4a1d9327d10a7f3a47b8be6f1da96056fa1451f68d7ebeaa5bcb2', '2026-02-17 09:25:36', '2026-01-18 09:25:36'),
(11, 1, 'b1a1063c9a2ae3f7eaf8459d7c2eb1db5e8097c595f74c7725b3dca12e2ef90bf063d170e212cb34eb9ddf8090017b6d8fb5793155943f2bcf138bfeeda9b403', '2026-02-17 09:26:21', '2026-01-18 09:26:21'),
(12, 1, 'd21af9f937be587ba30460d310136a074df29ccbc31ab957562deac8e6966389af54e38307192f3821c63bc5aeeed7a42a8ba34608292b5589a5dcbdf53e1e89', '2026-02-17 09:26:40', '2026-01-18 09:26:40'),
(13, 1, '9e5295775397876c8183afa54c125579e029e8dcf12851f88f33ce32ef2088e43c583a721894392120793c6e3fca5208ab09e7d22f241ebc165b89b95ae5c453', '2026-02-17 09:40:56', '2026-01-18 09:40:56'),
(14, 1, 'ba67f1190910081e9e76f4af87e6ae4d7ee7f46cf0c43e8e395729dd78a826059dbde35051360578bd48ea22e7fef391c6ba56b7e9d3a6892e62cb159d206a66', '2026-02-17 09:41:20', '2026-01-18 09:41:20'),
(15, 1, 'fd2560048bb30aac6061c13675114448545ec39f33d840ff45fbc720f6a93b2b0f4a184ac01efb527617f572abb28af783bfc70cfd6b940e1926e5f2c071f011', '2026-02-17 09:43:35', '2026-01-18 09:43:35'),
(16, 1, '7edb5fb2ab52e966e98b1e09c4b70af11f0634d78214c721f811656625e9f39cbb984855bc075b56b7c8b0693c8475437b6c578b51697ac91bc6aac60018c630', '2026-02-17 09:49:45', '2026-01-18 09:49:45'),
(17, 1, '9ee28a754365f6360f69f8aca3206c960853139550b6f26aa5550f59ac9946c73a886c7f20a9f50fec89deff10db602686c5b4d2462d5b9760b65d5f62bae7be', '2026-02-17 09:50:43', '2026-01-18 09:50:43'),
(18, 1, '672c13c8f7ba788ce81cefcf738ddcdbfc1a8fcd3010db6d1dfb7752faa09838ce17d399b94c3c12e5dcea00dda82f17195ed9e679803af9b119efe0f0081a30', '2026-02-17 10:08:51', '2026-01-18 10:08:51'),
(19, 1, '36588d96e7c238d038161a6d026a79b30e26fc9f2fcc91c911f00fef7c6205d4ff82451dfd796319a3ae5f12782dc644552590a22e37f74d08dc568d8a491a4b', '2026-02-17 10:11:47', '2026-01-18 10:11:47'),
(20, 1, '7dac2e67c183f0397deb092f939191b714bb396553e407450cc5212c0b2bdd587ae13940a005f24fe29e86efff15cebd15d2b8cbe05becdd8d566e337a311e5d', '2026-02-17 10:14:23', '2026-01-18 10:14:23'),
(21, 1, 'd464b5e4ec4a4dcd0125e4ab6f9e7a3b0df6d502a0f7c9cd00bf8b51355f9ea151e8786ad43c86669bebcbdd066dcfcfc7eb16812964150272a4716e740d426c', '2026-02-17 10:16:48', '2026-01-18 10:16:48'),
(22, 1, 'db12a185fb97503e95b9b356c4ebf03041c5f3e9aa41958022b3efe9a739363035b166311b9b3aec9d0f34a932a258e75696b1296eca95cb057f26423a6ecddf', '2026-02-17 10:18:40', '2026-01-18 10:18:40'),
(23, 1, '15ed788fa7ed045e78bd9be54fb3c55678939bad0036f93b3a7b3ebc31cc7863bf95a599ca7eaa594e2c7ea3ae4e3f86b12887fb2d840155d3373fc1e0f11c4f', '2026-02-17 10:24:09', '2026-01-18 10:24:09'),
(24, 1, 'e6f003b00b0fb68597ce005d6e0d2bebbde0227ee65d7c17eec6d65a62c1259f7cfd43db85f6d7e322bb434a36a077d399c3e5833f0ef916665aa9ee951800bf', '2026-02-17 10:29:41', '2026-01-18 10:29:41'),
(25, 1, '5f3ab4fa44c2f219b8ac4adb8fbf2e77498101a8628ae1cf84808abcd8a178c0bdb232ab3ded73458e89b7d096071a38264891c9194cd29b80fa02e2cafbf967', '2026-02-17 10:31:33', '2026-01-18 10:31:33'),
(26, 1, 'dbfb4adca93a0b7ea87f00d89f230fbd699ec13d6231689cfbdd6a34cce8e6ea3404d73d1155a3714895de8ea25c25390f653e43cef55bbaed63b9d0aa24e252', '2026-02-17 10:34:32', '2026-01-18 10:34:32'),
(27, 1, 'ccd429f10691888aed08766d0b0a9cc30c5df4f803866546e364ee291e866df69306cf96ee5d8ba64ac2dea1297e9cc8a5c4097d93dbddf46019b67801f46a03', '2026-02-17 10:48:52', '2026-01-18 10:48:52'),
(28, 1, '6bdd9f095f9f089fefc89c0655eefd8cec3c850f8ccfd82590c427056fc3266122fa5587cccf7018a98f1709ae6df99721fda10b492515f1654e61e84b74ed54', '2026-02-17 10:51:36', '2026-01-18 10:51:36'),
(29, 1, 'c90c1cdf2bf20a68dd454872ba4d1ec98c1603d37bc20d736f6031b006ec1e68a84662c3204f028083ea3adfdb0a860dede50898ad6205670d0c00b648604a15', '2026-02-17 11:12:53', '2026-01-18 11:12:53'),
(30, 1, 'b38cae0efb1b369681868422f6d3bbe0d55d0dae57042897d27106bf1be2b387218d4bdfce46a1d5c6841d230fa550819b585b63324a80593a69489fd365b7a0', '2026-02-17 11:14:45', '2026-01-18 11:14:45'),
(31, 1, '8e66e5c08fc53d4bd55b0343471677c48c67a2858b634ebf85094e61bbd863a67c16cf1d16449c993f045f434a8ce9c7a1b908775fb1cd12fd6e4eb3e4849031', '2026-02-17 11:50:58', '2026-01-18 11:50:58'),
(32, 1, '7a7ca2d018f18ad963e412468449d11e62080bcb4e71760510cbd86d537ef17a53c9a2ad672ca6e153fbaeb48356b2b81045e40be8d7f39eae1348210290ada5', '2026-02-17 11:56:09', '2026-01-18 11:56:09'),
(33, 1, '3f11962e446a710cf5a199b3dee4b22e57b53fcd7d5b99229e77d41c7c260ab087058a78dee6314f94469b2d78354786ad9443a8c9ad27a71c49fd1001f91422', '2026-02-17 11:57:55', '2026-01-18 11:57:55'),
(34, 1, '58d6bdfd967a6bc88ca150307d1af7ad5caf22b82dbc602f5d0881b19d72864cb67a335d0162e9fdcdb6893ae10e17fd5c525ebc8e1e5f6d162a9809a17deebf', '2026-02-17 11:59:28', '2026-01-18 11:59:28'),
(35, 1, '5ac35ca4444c232fb52d01594284d24686de29844d1657430c55d983b3c7e75d1fed5dd2404e6361dfcd5413e90d357d498c64fdc1ba7aee645e5bfb693aaf7f', '2026-02-17 11:59:32', '2026-01-18 11:59:32'),
(36, 1, 'c1a90043c07d85d872202e0531b51a7a141fdd9407137fe4d5329ca7424a4e2245e41ed9c094d27263547463843f0d68031c3a19932afeb7b9fb79f2601e2fc8', '2026-02-17 18:06:09', '2026-01-18 18:06:09'),
(37, 1, 'ff0d2c288b2e4703512f01315f6d00762eab66d043626c6b7e55e67a18f5ce6ff6f1f3b2c9bdcc41ada532ccd595e26df2ff5fbd85fcf288cfb74061fdf7197a', '2026-02-17 18:06:26', '2026-01-18 18:06:26'),
(38, 1, 'a76e5144ca876db29098c5e40da7148fa07de30c3825da6874b63b01cba66b4ca16841f99aa360c8643d78be7451f71f1013f96f3dadcf43eff9edd9abe3fd2f', '2026-02-17 18:06:36', '2026-01-18 18:06:36'),
(39, 1, 'fbf6a407fb1f63d8feb3911eace882acc564e2728056f974aa1a4724d6322fb0a1063cb19b7ee6bb0ce0115e3ba3a90b31bb13f6a2fed3a2595e36aee132016b', '2026-02-17 18:06:45', '2026-01-18 18:06:45'),
(40, 1, '44ea97aa25be7db71b78ca9928427c965b8b5ee8894ae2530fa323e5ab32d432404f23e4314fc4c03947263c1bcc3ae548d2ce84a3cb263552bfd1902b473b35', '2026-02-17 18:08:53', '2026-01-18 18:08:53'),
(41, 1, '236fb6d7d6dadd3691b9f5a16142d19bd4b70aa19d403d2e22859ae4169d700706bdd0561eec0b24a9bebd97ef24ff2b2cd8eee994e629c5e67a01a7266dd881', '2026-02-17 18:09:45', '2026-01-18 18:09:45'),
(42, 1, '3f0860b91940d2f842a7d113b1c1df973a11c6eb5c22ef560374f3cd6db896778746df50686f38c1f1f1ca34bbf2a7f122b6e46a1b39dddee252d8e0bab23344', '2026-02-17 18:09:48', '2026-01-18 18:09:48'),
(43, 1, '12cbcfdd21d971657e5946fb92dc4e0e30ca6bf33f83f01373e65f3a8cae132a2d6f833cbee58c00862b0d30223f57e9bc3f68b2d6fdfd49cbeb1b82f711721b', '2026-02-17 18:09:53', '2026-01-18 18:09:53'),
(44, 1, '065bc00ce1806a1d25cd430b6c37d5cc9ab9419faa35b7ab26b85211c3321f1fd4a2006c1fb944ef0781b577b75ece5939e5b18e1ab86707e2498c3dc8ddf18c', '2026-02-17 18:10:19', '2026-01-18 18:10:19'),
(45, 1, '2b3b069f78adc0b0459fd9b6136d7c88b3f1cd6c139d8b7dc3892e50c5ea54aee0d96efc34f65cb90a2be6045fb08b6b5959c260a1ebcca31ea3c28291c5aa52', '2026-02-17 18:10:32', '2026-01-18 18:10:32'),
(46, 1, '116a5f7db2ddf294ce95990f0aa6c9f2c97e005d11834152d43931a87ab8bb9e71b20dd6149cc4b2ef8e099527b5fd74c20a15098e42a8f45be46fca9e507723', '2026-02-17 18:10:43', '2026-01-18 18:10:43'),
(47, 1, 'e1631f38600516b77040ec40935d95bc911f9b307c770fb31cbeddfdef6e1bb1e197183d0b6cdfe069ff1bc1e9aafb2f0310885ffdd8892f4e7aa9ebf299f342', '2026-02-17 18:26:31', '2026-01-18 18:26:31'),
(48, 1, 'a2b1ce152c75f1bc1d01dbc044381a0f4fd90ea4e440597fe57caeb48009c5a38e88991d18b2633ec41fade1124e760851d1e1a29a5f719e06fae32073b04917', '2026-02-17 18:33:27', '2026-01-18 18:33:27'),
(49, 1, '2b310a3d9681a4fb4c9cdd255706055c44148dc574b18d36f3aac4f63a35bc7553b9d7365e07e8dd416efa3522f40125eb507be3fa103bfd653a802b0b0eaedc', '2026-02-17 18:33:35', '2026-01-18 18:33:35'),
(50, 1, '5dcc273c5c5a873ff779bb3112c0363de8dbd4d803ff814c6dd5f6d6df95c8f3b0263e733e77399f0e991118544014ec3c035ad06aa3045036ea1014bf459f98', '2026-02-17 18:34:11', '2026-01-18 18:34:11'),
(51, 1, '60168061b2eaf6055560d469a56dc6f6154f4d8111a4192b4171719c74774d3416e76eddc47423b8740d8433a91a25ec55bb7d4b14a769efc1d399c8d6b6f34d', '2026-02-17 18:43:48', '2026-01-18 18:43:48'),
(52, 1, '631cb8dbeb55dbbfc6af20d9bb671e64053891031040d451a32fb573a2d99d1a7562a5fd67fc4fb07b8744481f7941048f56445ce01e9519de9dc93ddbb96d87', '2026-02-17 18:44:40', '2026-01-18 18:44:40'),
(53, 2, '68550d6393fe4888e935468fb4c5fd1935b8f50a39f8459a55d3fb78416f96964a8783258f5d7245d281d7fd398a56ed9a8063e4df20873f85a1a7bdf509311a', '2026-02-17 18:59:59', '2026-01-18 18:59:59'),
(54, 2, '701e411ea377f58aa2e99eef4b88e0e62578f26654e58a93391b5100327fb999fd2b5ba58c4e1d948e4c7235c5f41acd6186776fc6fe722d494af4e2b2397e4d', '2026-02-18 07:26:53', '2026-01-19 07:26:53'),
(55, 2, '770e29e3e3bf2ac9bb84dc81a8b6eb0021a27f9ecf2327f6a98d8e4778c1024c069e2485bdb5ae22510caa13fe57518dec056fefc73c588e06390a2a93040e53', '2026-02-18 07:28:25', '2026-01-19 07:28:25'),
(56, 2, 'd7cdb3723e1ecf3080a30143b95538ff32661db24af61417d7637b3a0731e453ca6490ef1e9ae0203f584db1741385b8f99892d932c062599c740f047f508bca', '2026-02-18 07:35:31', '2026-01-19 07:35:31'),
(57, 2, 'e6aaba74a663f133445711796867f74c3d931a4903068f23dcb2efd295960847b1624c858b5bd094ac83324c904f2943bdf6a8101e1fb70b33240e6f63b6e6c5', '2026-02-18 07:35:40', '2026-01-19 07:35:40'),
(58, 2, 'fdee2a9f2ad613c402ef9f1133680c660dce00f3b06fcd7f3b21ce135d4250dae5ec193e0f7befb88458b37d5914c7f1f2aeca529d0d10c853fd470c21c8e1a3', '2026-02-18 07:35:42', '2026-01-19 07:35:42'),
(59, 2, '586ed6731b110b42bfd8decd3f57c322b06746deff4b076ff61fd4ebc28bcf94408ea5504468036437784bc494333724806ca36fbdee2ef438611705836e6d80', '2026-02-18 07:35:46', '2026-01-19 07:35:46'),
(60, 2, 'e3937f347ba1abe4d7cf24a718f160527dd948492a0eb9311ab43246c468c97c1bac74f50db1db0a6dad629d36a88a6fd499d3420a8cba0a59636d751bb92da9', '2026-02-18 07:36:07', '2026-01-19 07:36:07'),
(61, 2, '10715b08b2cfcf86b1db449d75d5a30549ba7172433c80fd1ca270ab2686f327eb41bbdc12033c1a4eb3d5b4260d7215ec4dd5f89b71aab5308aed90c74c7cca', '2026-02-18 07:37:11', '2026-01-19 07:37:11'),
(62, 2, '0899e6f7419d5ef097cdb76c1db491adea4451fc3b4c0c41d3aa880dd4460ee80838354207071a254f8d5bf4a8a0990f595964cabf734dc04143f5aca5dae737', '2026-02-18 07:40:51', '2026-01-19 07:40:51'),
(63, 2, '18e914df12418d1bb51e3808429f2d250d85330b455bccff6e2c424617158f428dbcbfba3057b44998de62c2e45f0321e031b6ea32b79fd5ac6f6b6f292e084b', '2026-02-18 07:42:00', '2026-01-19 07:42:00'),
(64, 2, '81a6b0d29b705576b8c9be90154e0354b2383fc440d6bc339d5cc6b7b13ad7a4a875955e498029755ec1a48433c68b846c79ae4b8f40ff735067b26fb7f66b6d', '2026-02-18 07:51:42', '2026-01-19 07:51:42'),
(65, 2, '4931352c193f495873ec5ca1ee560929fc13f48133c434f709d485c8865d634aef1be88c2a2cd4a8505b63e40f5f7669db3006e78fae5fecbadbbead55702037', '2026-02-18 08:13:29', '2026-01-19 08:13:29'),
(66, 2, '6106c9aa216f582e3ba070485c97877af32cae06b629d7332bc40019c82a5113bf35e746c65e329cc28e3ff002eebf76e6d6d945d8519a03bc618c63d8216885', '2026-02-18 08:38:22', '2026-01-19 08:38:22'),
(67, 2, 'a16a1d1a692f75f297221f279866dfe2e9d82048e4da2daede19d39f686b958b948db6f1df95a10bb00b2c8f597a88b6f5fcea42bb858b3250f1944b1d65cec2', '2026-02-18 09:01:09', '2026-01-19 09:01:09'),
(68, 2, '6b95342bfc6b13a34eac9eec1e33fb17e23e98a8bd4b5fb0bf5424b2861d47c3d0eaaa93d710ef03ad08851c7de7f39cf9ee8dbce420473c18dfd79ee625179b', '2026-02-18 10:43:32', '2026-01-19 10:43:32'),
(69, 3, 'c592be923bb925d01a6e3cbf25a2da0ab78d5aba61013753086ebd6175e593c3503e7834d523e60ee7add5255fa86d7847dc73b1dcba9a1ada0d7084d9bc4011', '2026-02-18 10:53:17', '2026-01-19 10:53:17'),
(70, 3, 'bf8c371dd9600c2f874891ff04ff3986cd3d07d55a8f5b50513b6228dae31d5007826a55b3a5ff2aab8e9e2d65845f2a60b3dfa9776b56e0e3ba9ce708dac5a3', '2026-02-18 11:06:36', '2026-01-19 11:06:36'),
(71, 3, 'ec5ccf549e911326be9994b64e279fc7c10624c7c5f55eb1b4573056a9797b24f191a18f864cb3d1dcc2327634036983f5a4cffdf14f9d84aeaefdc29e889d42', '2026-02-22 11:14:45', '2026-01-23 11:14:45'),
(72, 3, '359d688eb2bb021a5bc0daf4325187d637eaf06f94696a136d55b7c691bc969e4b6135f20c8f4d9f4ac711ea92e885cf95a877b1a68efbd795969e880ccfe8e3', '2026-02-22 11:15:00', '2026-01-23 11:15:00'),
(73, 3, '534f83039980f5344737513225b6fd86189d8025860b17419335cf68c0ce8c8981b412d5036e8dd205e7a8b412d9bf61610ef0f00bf55c67101046e1b724d269', '2026-02-24 13:28:50', '2026-01-25 13:28:50'),
(74, 3, 'e3f5ec6e6689ca92d096862288f11d10d48745abf8bb6a6c95345455240d11077688182555ae14143dc5810831a1683004a77b43f7d9b11018fa11c7702c8409', '2026-02-24 15:16:09', '2026-01-25 15:16:09'),
(75, 3, '92fc624415ba82c84913681e66a5e80e529f1a4044a7cb8fc372078a8f605240f6dffd3aec37c334d3c5a0f777c78cab5e65046385484dbfc14296e0412e1215', '2026-02-24 15:49:21', '2026-01-25 15:49:21');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(1) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(1) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `name`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 'Room Attendant', '2026-01-18 00:54:00', 1, NULL, NULL, NULL, NULL),
(2, 'Waiter', '2026-01-18 00:54:00', 1, NULL, NULL, NULL, NULL),
(3, 'Cook Helper', '2026-01-18 00:54:00', 1, NULL, NULL, NULL, NULL),
(4, 'Public Area', '2026-01-18 00:54:00', 1, NULL, NULL, NULL, NULL),
(5, 'Front Office', '2026-01-18 00:54:00', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(1) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `role` enum('worker','hotel_hr','hotel_fo','hotel_hk','hotel_fnb_service','hotel_fnb_production','admin') DEFAULT 'worker',
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `provider` enum('local','google','facebook') DEFAULT 'local',
  `provider_id` varchar(100) DEFAULT NULL,
  `photo` varchar(250) DEFAULT NULL,
  `is_verified` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(1) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `hotel_id`, `role`, `name`, `email`, `phone`, `password`, `provider`, `provider_id`, `photo`, `is_verified`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 0, 'admin', 'Administrator', 'admin@admin.com', '0812', '$2y$10$TYZN8k0YxaB.jxCtqA4sl.JnllEeN3/UF9oGYK5.LTvbGlCe7HE82', 'local', NULL, NULL, 0, '2026-01-18 12:25:53', 1, NULL, NULL, NULL, NULL),
(2, 0, 'worker', 'Arya Seftian', 'yerblues6@gmail.com', '895330907220', '$2y$10$ziaDpWwWk3gBjVGu6XqmoebCqmePQwtuwaRGY5ggXBpOI/.Wubhq.', 'local', NULL, 'uploads/profiles/profile_2_1768811928.png', 0, '2026-01-18 18:59:55', NULL, '2026-01-19 08:38:48', NULL, NULL, NULL),
(3, 0, 'worker', 'Muhammad', 'muhammad@gmail.com', '99988776', '$2y$10$relLlluCofLYvJKJDW65zuxFadTF4X4A.mCur9V2uEbiZVW8vGhaa', 'local', NULL, 'uploads/profiles/profile_3_1768820480.png', 0, '2026-01-19 10:53:08', NULL, '2026-01-19 11:01:20', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `worker_documents`
--

CREATE TABLE `worker_documents` (
  `id` int(1) NOT NULL,
  `user_id` int(1) NOT NULL,
  `type` enum('ktp','certificate','other') DEFAULT 'other',
  `file_path` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worker_educations`
--

CREATE TABLE `worker_educations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `title` int(11) NOT NULL,
  `instituted_name` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` smallint(1) NOT NULL,
  `deleted_at` datetime NOT NULL,
  `deleted_by` smallint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worker_experiences`
--

CREATE TABLE `worker_experiences` (
  `id` int(1) NOT NULL,
  `user_id` int(1) NOT NULL,
  `company_name` varchar(150) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(1) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worker_profiles`
--

CREATE TABLE `worker_profiles` (
  `id` int(1) NOT NULL,
  `user_id` int(1) NOT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(1) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `worker_profiles`
--

INSERT INTO `worker_profiles` (`id`, `user_id`, `gender`, `birth_date`, `address`, `bio`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`) VALUES
(1, 1, 'male', '2026-01-18', 'Bogor', 'Profile', '2026-01-18 16:47:54', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `worker_ratings`
--

CREATE TABLE `worker_ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comments` int(11) NOT NULL,
  `pancuality` int(11) NOT NULL,
  `apperance` int(11) NOT NULL,
  `knowledge` int(11) NOT NULL,
  `durability` int(11) NOT NULL,
  `ethics` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` smallint(1) NOT NULL,
  `deleted_at` datetime NOT NULL,
  `deleted_by` smallint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worker_reviews`
--

CREATE TABLE `worker_reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comments` int(11) NOT NULL,
  `stars` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `created_by` int(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` smallint(1) NOT NULL,
  `deleted_at` datetime NOT NULL,
  `deleted_by` smallint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worker_skills`
--

CREATE TABLE `worker_skills` (
  `id` int(1) NOT NULL,
  `user_id` int(1) NOT NULL,
  `skill_id` int(1) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(1) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(1) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_id` (`job_id`,`user_id`);

--
-- Indexes for table `job_attendances`
--
ALTER TABLE `job_attendances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `application_id` (`application_id`);

--
-- Indexes for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `worker_documents`
--
ALTER TABLE `worker_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `worker_experiences`
--
ALTER TABLE `worker_experiences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `worker_profiles`
--
ALTER TABLE `worker_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `worker_ratings`
--
ALTER TABLE `worker_ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `worker_reviews`
--
ALTER TABLE `worker_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `worker_skills`
--
ALTER TABLE `worker_skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`skill_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `job_attendances`
--
ALTER TABLE `job_attendances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `refresh_tokens`
--
ALTER TABLE `refresh_tokens`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `worker_documents`
--
ALTER TABLE `worker_documents`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worker_experiences`
--
ALTER TABLE `worker_experiences`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worker_profiles`
--
ALTER TABLE `worker_profiles`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `worker_ratings`
--
ALTER TABLE `worker_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worker_reviews`
--
ALTER TABLE `worker_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worker_skills`
--
ALTER TABLE `worker_skills`
  MODIFY `id` int(1) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
