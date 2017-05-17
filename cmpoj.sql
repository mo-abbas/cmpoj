-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 17, 2017 at 03:59 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cmpoj`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `id` int(12) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `password` varchar(60) NOT NULL,
  `email` varchar(40) NOT NULL,
  `team_id` int(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`id`, `first_name`, `last_name`, `password`, `email`, `team_id`) VALUES
(21, 'Mohamed', 'Abbas', '$2y$10$NmM3ZDQ5MzJlMTY2OGZhMeFGvPFfPz0eRs5QsdkwSTYKHe5PPpeui', 'mamaams@gmail.com', NULL),
(22, 'Mohamed', 'Abbas', '$2y$10$OWI1ZTRmYmU2ZjIxYTRkM.AhzngszY8pE39vsXGjiZYIxWF0TdDUO', 'mohamed@gmail.com', 37),
(23, 'Mohamed', 'Abbas', '$2y$10$YTc4YzkwZjE3Mjk4OGNjMuaZNM6kerdYn0kMgQv5sAxUZezpcuf4i', 'mamaams@gmail.net', NULL),
(24, 'Ahmed', 'Ali', '$2y$10$OWNlZDY2NWVkNzdiYmI3M.sYZV.bnwIbcj7p4fS4p91ty1G/ZAHC2', 'test1@gmail.com', NULL),
(25, 'Youssef', 'Hamdy', '$2y$10$Y2E4N2I0Njc5MGNkNTliYek6NOhBryuJpD6a6nKDGHcImBZsrriRS', 'test2@gmail.com', NULL),
(26, 'Kareem', 'Hassan', '$2y$10$Mjg1ZDUwYzk5NGNiMmZhMOBvBQ9mmouMlmoiM2UJH7rVB2.E7dWjS', 'test3@gmail.com', NULL),
(27, 'Aya', 'Hassan', '$2y$10$ODZlMjg0YjEzYWExZjUxMuchbfthmdVNBmaGbtQfmeHhS1EC4ZWGO', 'test4@a.com', NULL),
(28, 'Alaa', 'Ahmed', '$2y$10$Zjk2MGNiYjczMWQ1MjkxMegH9G6QlYGHrRuuyz5KXW83bexPBPlQy', 'test5@gmail.com', NULL),
(29, 'Ahmed', 'Refaat', '$2y$10$MjJjZmM4ZDdiZWZjM2MzOOxO/wCrd0foaCT0UbxeOlSFxUXMvDg1e', 'test6@gmail.com', NULL),
(32, 'Omar', 'Ahmed', '$2y$10$YzBhY2E2NDRkNWJjMDgyYOsaW09THIzWA08Zsaqy/9cq7A6zG5HBS', 'whatever@whatever.whatever', 37),
(33, 'Ali', 'Hussein', '$2y$10$YTBkMmFkNzMzZjc0NGQzN.a/MymeYUjytD5l3M5YlB06sr6jRK5eq', 'ali@whatever.com', NULL),
(35, 'asdasd', 'asdasd', '$2y$10$YzFmM2NhMGI3MjZmNjVlYuVKUIyQ/svHwT1rF1reQIMOXKrZgL0ai', 'sadkjfhk@kfjgkd.com', 37),
(36, 'asad', 'asad', '$2y$10$NGQzYTMxMWQwNTExMzIwO.ZWHVDeKtRYAMuTGWnrjCqubdPQXeM3e', 'a@a.com', 37);

-- --------------------------------------------------------

--
-- Table structure for table `available_compiler`
--

CREATE TABLE `available_compiler` (
  `contest_id` int(12) NOT NULL,
  `compiler_id` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `available_compiler`
--

INSERT INTO `available_compiler` (`contest_id`, `compiler_id`) VALUES
(13, 1),
(14, 1),
(15, 1),
(18, 1),
(20, 1),
(21, 1),
(13, 2),
(15, 2),
(18, 2),
(21, 2),
(13, 3),
(15, 3),
(18, 3);

-- --------------------------------------------------------

--
-- Table structure for table `compiler`
--

CREATE TABLE `compiler` (
  `id` int(12) NOT NULL,
  `name` varchar(20) NOT NULL,
  `version` varchar(20) NOT NULL,
  `code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `compiler`
--

INSERT INTO `compiler` (`id`, `name`, `version`, `code`) VALUES
(1, 'GNU C++', '4.8.2', 'cpp'),
(2, 'Python', '2.7.3', 'python'),
(3, 'Java', '7', 'java');

-- --------------------------------------------------------

--
-- Table structure for table `contest`
--

CREATE TABLE `contest` (
  `id` int(12) NOT NULL,
  `name` varchar(20) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `type` tinyint(1) NOT NULL,
  `judge_id` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contest`
--

INSERT INTO `contest` (`id`, `name`, `start_time`, `end_time`, `type`, `judge_id`) VALUES
(13, 'First Contest', '2015-01-09 03:14:00', '2018-01-10 01:00:00', 0, 21),
(14, 'test', '2015-12-01 01:00:00', '2016-01-01 01:00:00', 1, 21),
(15, 'Team contest', '2015-01-09 04:28:00', '2016-01-01 01:00:00', 1, 21),
(18, 'contest 1', '2015-01-11 17:19:00', '2015-01-15 17:19:00', 0, 21),
(19, 'contest 2', '2015-01-11 17:19:00', '2015-01-15 17:19:00', 0, 21),
(20, 'contest21', '2017-01-21 01:01:00', '2018-01-22 01:01:00', 1, 27),
(21, 'asdas', '2017-01-30 12:59:00', '2017-12-31 12:59:00', 1, 36),
(22, 'contest21', '2017-10-21 01:01:00', '2018-01-22 01:01:00', 1, 27),
(26, 'contest21', '2017-10-21 01:01:00', '2018-01-22 01:01:00', 1, 27);

-- --------------------------------------------------------

--
-- Table structure for table `contestant`
--

CREATE TABLE `contestant` (
  `id` int(12) NOT NULL,
  `handle` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contestant`
--

INSERT INTO `contestant` (`id`, `handle`) VALUES
(33, 'alii'),
(36, 'asad'),
(35, 'asdasd'),
(21, 'besoo'),
(23, 'besoo1'),
(22, 'mohamed'),
(32, 'OmarAhmed'),
(30, 'TeamA'),
(31, 'TeamB'),
(34, 'teamH'),
(24, 'test1'),
(25, 'test2'),
(26, 'test3'),
(27, 'test4'),
(28, 'test5'),
(29, 'test6'),
(37, 'Testers');

-- --------------------------------------------------------

--
-- Table structure for table `contestant_joins`
--

CREATE TABLE `contestant_joins` (
  `contestant_id` int(12) NOT NULL,
  `contest_id` int(12) NOT NULL,
  `rank` int(11) NOT NULL DEFAULT '2147483647',
  `score` int(11) NOT NULL DEFAULT '0',
  `acc_problems` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contestant_joins`
--

INSERT INTO `contestant_joins` (`contestant_id`, `contest_id`, `rank`, `score`, `acc_problems`) VALUES
(22, 13, 2, 142, 2),
(23, 13, 1, 130, 2),
(25, 13, 7, 0, 0),
(33, 13, 3, 7490, 2);

-- --------------------------------------------------------

--
-- Table structure for table `problem`
--

CREATE TABLE `problem` (
  `id` int(12) NOT NULL,
  `title` varchar(30) NOT NULL,
  `level` int(11) NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `contest_id` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `problem`
--

INSERT INTO `problem` (`id`, `title`, `level`, `text`, `contest_id`) VALUES
(10, 'Problem 1', 1, 'This is a test problem, just print out "Hello" without quotes for the correct answer.\n\nTHERE IS NO INPUT FOR THIS PROBLEM..', 13),
(11, 'Problem 2', 4, 'In this problem we want the number of zeros that end factorial of a specific number.\n\nFor example:\n\nfactorial(5) = 20 and it has 1 zero at the right.\n\n\n\nThe input will start with a number \'t\' that represents number of test cases.\n\nFollowing there are t lines each line has a number a (1 <= a <= 10^9)\n\n\n\noutput each test case on a separate line.', 13),
(12, 'test', 1, 'asd ', 14),
(13, 'test 1', 1, 'asdasd ', 15),
(18, 'problem 1', 1, 'test for problem ', 18),
(19, 'Problem 2', 1, 'test for another problem ', 18),
(20, 'Problem 2', 1, 'test for another problem ', 19),
(21, 'problem 1', 1, 'test for problem ', 19),
(22, 'one one', 1, ' one oneone oneone one', 21),
(23, 'one one', 1, ' one oneone oneone one', 22);

-- --------------------------------------------------------

--
-- Table structure for table `problem_category`
--

CREATE TABLE `problem_category` (
  `problem_id` int(12) NOT NULL,
  `category` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `problem_category`
--

INSERT INTO `problem_category` (`problem_id`, `category`) VALUES
(10, 'Test'),
(11, 'Greedy'),
(12, 'asd'),
(13, 'Greedy'),
(18, 'Greedy'),
(19, 'Greedy'),
(22, 'one one');

-- --------------------------------------------------------

--
-- Table structure for table `samples`
--

CREATE TABLE `samples` (
  `problem_id` int(12) NOT NULL,
  `input` text NOT NULL,
  `output` text NOT NULL,
  `id` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `samples`
--

INSERT INTO `samples` (`problem_id`, `input`, `output`, `id`) VALUES
(10, '*No Input*', 'Hello', 13),
(11, '1\n\n5', '1', 14),
(11, '1\n\n4', '0', 15),
(12, ' asd', ' asd', 16),
(13, 'asd ', ' asd', 17),
(18, '1234 ', '1234 ', 18),
(19, '12345 ', '123456 ', 19),
(22, ' one one', ' one one', 20);

-- --------------------------------------------------------

--
-- Table structure for table `solves`
--

CREATE TABLE `solves` (
  `problem_id` int(12) NOT NULL,
  `contestant_id` int(12) NOT NULL,
  `ac_time` datetime NOT NULL DEFAULT '1000-01-01 00:00:00',
  `submission_num` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `solves`
--

INSERT INTO `solves` (`problem_id`, `contestant_id`, `ac_time`, `submission_num`) VALUES
(10, 22, '2015-01-09 03:55:06', 3),
(10, 23, '2015-01-09 04:19:28', 1),
(10, 25, '1000-01-01 00:00:00', 1),
(10, 33, '2015-01-11 17:27:46', 1),
(11, 22, '2015-01-09 04:14:56', 1),
(11, 23, '2015-01-09 04:19:39', 1),
(11, 33, '2015-01-11 17:31:19', 2);

-- --------------------------------------------------------

--
-- Table structure for table `submission`
--

CREATE TABLE `submission` (
  `id` int(12) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT ' Pending',
  `time` datetime NOT NULL,
  `code` text NOT NULL,
  `compiler_id` int(12) NOT NULL,
  `contestant_id` int(12) NOT NULL,
  `problem_id` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `submission`
--

INSERT INTO `submission` (`id`, `status`, `time`, `code`, `compiler_id`, `contestant_id`, `problem_id`) VALUES
(374, 'Compile error', '2015-01-09 03:54:50', '#include <iostream>\n\n#include <string>\n\n\n\nusing namespace std;\n\n\n\nint main()\n\n{\n\n	cout << "Hello" << endl\n\n	return 0;\n\n}', 1, 22, 10),
(375, 'Wrong answer', '2015-01-09 03:54:59', '#include <iostream>\n\n#include <string>\n\n\n\nusing namespace std;\n\n\n\nint main()\n\n{\n\n	cout << " Hello" << endl;\n\n	return 0;\n\n}', 1, 22, 10),
(376, 'Accepted', '2015-01-09 03:55:06', '#include <iostream>\n\n#include <string>\n\n\n\nusing namespace std;\n\n\n\nint main()\n\n{\n\n	cout << "Hello" << endl;\n\n	return 0;\n\n}', 1, 22, 10),
(377, 'Wrong Answer', '2015-01-09 03:59:46', '#include <iostream>\n\n\n\n//I don\'t actually remember the solution for this problem so I\'ll consider it accepted anyway :D\n\n\n\nusing namespace std;\n\n\n\nint main()\n\n{\n\n	int t;\n\n	cin >> t;\n\n	long long answer;\n\n	while(t--)\n\n	{\n\n		cout << answer << endl;\n\n	}\n\n	return 0;\n\n}', 1, 22, 11),
(381, 'Accepted', '2015-01-09 04:19:28', 'a', 1, 23, 10),
(382, 'Accepted', '2015-01-09 04:19:39', 'b', 1, 23, 11),
(384, 'Wrong answer', '2015-01-09 04:36:06', 'asd', 1, 31, 13),
(385, 'Runtime error', '2015-01-09 04:39:13', 'asdas', 1, 31, 13),
(386, 'Accepted', '2015-01-09 04:40:04', 'asd', 1, 31, 13),
(387, 'Accepted', '2015-01-09 04:47:09', '123', 1, 30, 13),
(388, ' Pending', '2015-01-09 05:06:33', 'sad', 1, 23, 10),
(389, 'Runtime error', '2015-01-09 19:17:25', '#include <ioaksfhosdfg\n\ndfglksdfjg;sdfgsdfg\n\ndf\n\ndfg\n\nld;sfg', 1, 25, 10),
(390, 'Accepted', '2015-01-11 17:27:46', '#include <iostream>\n\nusing namespace std;\n\n\n\nint main()\n\n{\n\n	return 0;\n\n}', 1, 33, 10),
(391, 'Compile error', '2015-01-11 17:31:06', 'klsdjnfksd', 1, 33, 11),
(392, 'Accepted', '2015-01-11 17:31:19', 'dfmsdmf.,', 1, 33, 11),
(393, 'Accepted', '2015-01-11 17:46:27', 'asndm,fn ', 1, 34, 13);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(12) NOT NULL,
  `coach_id` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `coach_id`) VALUES
(37, 33);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `account_ibfk_1` (`team_id`);

--
-- Indexes for table `available_compiler`
--
ALTER TABLE `available_compiler`
  ADD PRIMARY KEY (`contest_id`,`compiler_id`),
  ADD KEY `available_compiler_ibfk_2` (`compiler_id`);

--
-- Indexes for table `compiler`
--
ALTER TABLE `compiler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contest`
--
ALTER TABLE `contest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_judges` (`judge_id`);

--
-- Indexes for table `contestant`
--
ALTER TABLE `contestant`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `handle` (`handle`);

--
-- Indexes for table `contestant_joins`
--
ALTER TABLE `contestant_joins`
  ADD PRIMARY KEY (`contestant_id`,`contest_id`),
  ADD KEY `contestant_joins_ibfk_2` (`contest_id`);

--
-- Indexes for table `problem`
--
ALTER TABLE `problem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `problem_ibfk_1` (`contest_id`);

--
-- Indexes for table `problem_category`
--
ALTER TABLE `problem_category`
  ADD PRIMARY KEY (`problem_id`,`category`);

--
-- Indexes for table `samples`
--
ALTER TABLE `samples`
  ADD PRIMARY KEY (`id`),
  ADD KEY `problem_id` (`problem_id`);

--
-- Indexes for table `solves`
--
ALTER TABLE `solves`
  ADD PRIMARY KEY (`problem_id`,`contestant_id`),
  ADD KEY `solves_ibfk_1` (`contestant_id`);

--
-- Indexes for table `submission`
--
ALTER TABLE `submission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contestant_submits` (`contestant_id`),
  ADD KEY `submission_ibfk_1` (`problem_id`),
  ADD KEY `compiler_id` (`compiler_id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coach_id` (`coach_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `compiler`
--
ALTER TABLE `compiler`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `contest`
--
ALTER TABLE `contest`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `contestant`
--
ALTER TABLE `contestant`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `problem`
--
ALTER TABLE `problem`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `samples`
--
ALTER TABLE `samples`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `submission`
--
ALTER TABLE `submission`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=401;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_contestant` FOREIGN KEY (`id`) REFERENCES `contestant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `available_compiler`
--
ALTER TABLE `available_compiler`
  ADD CONSTRAINT `available_compiler_ibfk_1` FOREIGN KEY (`contest_id`) REFERENCES `contest` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `available_compiler_ibfk_2` FOREIGN KEY (`compiler_id`) REFERENCES `compiler` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `contest`
--
ALTER TABLE `contest`
  ADD CONSTRAINT `account_judges` FOREIGN KEY (`judge_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `contestant_joins`
--
ALTER TABLE `contestant_joins`
  ADD CONSTRAINT `contestant_joins_ibfk_1` FOREIGN KEY (`contestant_id`) REFERENCES `contestant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contestant_joins_ibfk_2` FOREIGN KEY (`contest_id`) REFERENCES `contest` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `problem`
--
ALTER TABLE `problem`
  ADD CONSTRAINT `problem_ibfk_1` FOREIGN KEY (`contest_id`) REFERENCES `contest` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `problem_category`
--
ALTER TABLE `problem_category`
  ADD CONSTRAINT `problem_category_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `samples`
--
ALTER TABLE `samples`
  ADD CONSTRAINT `samples_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `solves`
--
ALTER TABLE `solves`
  ADD CONSTRAINT `solves_ibfk_1` FOREIGN KEY (`contestant_id`) REFERENCES `contestant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `solves_ibfk_2` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `submission`
--
ALTER TABLE `submission`
  ADD CONSTRAINT `contestant_submits` FOREIGN KEY (`contestant_id`) REFERENCES `contestant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `submission_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `problem` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `submission_ibfk_2` FOREIGN KEY (`compiler_id`) REFERENCES `compiler` (`id`),
  ADD CONSTRAINT `submission_ibfk_3` FOREIGN KEY (`compiler_id`) REFERENCES `compiler` (`id`);

--
-- Constraints for table `team`
--
ALTER TABLE `team`
  ADD CONSTRAINT `team_ibfk_1` FOREIGN KEY (`id`) REFERENCES `contestant` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `team_ibfk_2` FOREIGN KEY (`coach_id`) REFERENCES `account` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
