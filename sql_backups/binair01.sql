-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2016 at 05:29 PM
-- Server version: 10.1.9-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `binair01`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(75) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `usertype` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `password`, `usertype`) VALUES
('admin', '$2a$10$jUoaoQZzVZK7pyU5OSl2K.9cJZXft/yil79oSt2E4T.tIFbiM2VEq', 'admin'),
('editor', '$2a$10$UcmAsAXMHyzATiw7jlz7auJYjPnTH0aZp0nn3KNVjwnEs1GBI7XVS', 'editor');

-- --------------------------------------------------------

--
-- Table structure for table `blogposts`
--

CREATE TABLE `blogposts` (
  `id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `coverimage` text NOT NULL,
  `youtubecover` varchar(500) NOT NULL,
  `title` varchar(200) NOT NULL,
  `sortdate` date NOT NULL,
  `tags` varchar(1000) NOT NULL,
  `body` text NOT NULL,
  `galleryimages` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blogposts`
--

INSERT INTO `blogposts` (`id`, `created`, `coverimage`, `youtubecover`, `title`, `sortdate`, `tags`, `body`, `galleryimages`) VALUES
(30, '2016-02-18 13:40:15', '1455802815_7369.jpg', '', 'Lorem ipsum dolor sit amet', '2016-02-18', 'Lorem, ipsum, dolor, sit, amet', '&lt;p style=&quot;line-height: 0.7;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ut tellus faucibus, accumsan ligula ac, vulputate augue. Fusce sapien elit, efficitur vel laoreet volutpat, auctor a arcu. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Maecenas purus nunc, eleifend et mi a, aliquam dapibus magna. Sed malesuada enim vestibulum, tincidunt tellus eget, rhoncus urna. Suspendisse fringilla aliquet ex non luctus. Pellentesque ut odio sollicitudin, volutpat odio eu, sagittis turpis. Nulla pellentesque sollicitudin nisi, et maximus diam fermentum id. Nunc tincidunt faucibus magna. In hac habitasse platea dictumst. Nulla aliquet porttitor ligula sed pellentesque. Aenean consectetur efficitur augue, et sollicitudin lacus dignissim ac.&lt;br&gt;&lt;/p&gt;', ''),
(31, '2016-02-18 13:40:38', '1455802838_7645.png', '', 'Consectetur adipiscing', '2016-02-18', 'Lorem, ipsum, dolor, sit', '&lt;p style=&quot;line-height: 0.7;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ut tellus faucibus, accumsan ligula ac, vulputate augue. Fusce sapien elit, efficitur vel laoreet volutpat, auctor a arcu. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Maecenas purus nunc, eleifend et mi a, aliquam dapibus magna. Sed malesuada enim vestibulum, tincidunt tellus eget, rhoncus urna. Suspendisse fringilla aliquet ex non luctus. Pellentesque ut odio sollicitudin, volutpat odio eu, sagittis turpis. Nulla pellentesque sollicitudin nisi, et maximus diam fermentum id. Nunc tincidunt faucibus magna. In hac habitasse platea dictumst. Nulla aliquet porttitor ligula sed pellentesque. Aenean consectetur efficitur augue, et sollicitudin lacus dignissim ac.&lt;br&gt;&lt;/p&gt;', ''),
(39, '2016-02-19 10:40:45', '1455878444_2979.jpg', '', 'Lorem ipsum dolor long title test for wrapping et cetera', '2016-02-19', 'Lorem, ipsum, dolor ', '&lt;p style=&quot;line-height: 1.4;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam volutpat, ipsum non tristique tincidunt, leo mi dignissim lorem, quis pellentesque nunc augue sit amet nibh. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur malesuada commodo ligula. Suspendisse ut vestibulum neque. Praesent vel tortor sit amet nibh condimentum eleifend nec at augue. Phasellus feugiat pulvinar quam, quis sollicitudin nibh dignissim eu. Aenean cursus hendrerit erat ac bibendum. Sed egestas semper porta. Phasellus pretium risus et vulputate auctor. Quisque placerat dictum massa, quis luctus justo lacinia non. Donec tempor a elit ullamcorper pellentesque. Ut ultricies vel purus a porta.&lt;br&gt;&lt;/p&gt;', ''),
(40, '2016-02-19 10:40:55', '1455878455_4711.jpg', '', 'Dolor Donor', '2016-02-19', 'Lorem, ipsum, dolor ', '&lt;p style=&quot;line-height: 1.4;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam volutpat, ipsum non tristique tincidunt, leo mi dignissim lorem, quis pellentesque nunc augue sit amet nibh. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur malesuada commodo ligula. Suspendisse ut vestibulum neque. Praesent vel tortor sit amet nibh condimentum eleifend nec at augue. Phasellus feugiat pulvinar quam, quis sollicitudin nibh dignissim eu. Aenean cursus hendrerit erat ac bibendum. Sed egestas semper porta. Phasellus pretium risus et vulputate auctor. Quisque placerat dictum massa, quis luctus justo lacinia non. Donec tempor a elit ullamcorper pellentesque. Ut ultricies vel purus a porta.&lt;br&gt;&lt;/p&gt;', ''),
(41, '2016-02-19 10:41:11', '1455878471_9376.jpg', '', 'Upsadoek Pannekoek', '2016-02-19', 'Lorem, ipsum, dolor ', '&lt;p style=&quot;line-height: 1.4;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam volutpat, ipsum non tristique tincidunt, leo mi dignissim lorem, quis pellentesque nunc augue sit amet nibh. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur malesuada commodo ligula. Suspendisse ut vestibulum neque. Praesent vel tortor sit amet nibh condimentum eleifend nec at augue. Phasellus feugiat pulvinar quam, quis sollicitudin nibh dignissim eu. Aenean cursus hendrerit erat ac bibendum. Sed egestas semper porta. Phasellus pretium risus et vulputate auctor. Quisque placerat dictum massa, quis luctus justo lacinia non. Donec tempor a elit ullamcorper pellentesque. Ut ultricies vel purus a porta.&lt;br&gt;&lt;/p&gt;', ''),
(42, '2016-02-19 10:41:47', '1455878507_5994.jpg', '', 'Hoppekee Weg Ermee', '2016-03-17', 'Lorem, ipsum, dolor ', '&lt;p style=&quot;line-height: 1.4;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam volutpat, ipsum non tristique tincidunt, leo mi dignissim lorem, quis pellentesque nunc augue sit amet nibh. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur malesuada commodo ligula. Suspendisse ut vestibulum neque. Praesent vel tortor sit amet nibh condimentum eleifend nec at augue. Phasellus feugiat pulvinar quam, quis sollicitudin nibh dignissim eu. Aenean cursus hendrerit erat ac bibendum. Sed egestas semper porta. Phasellus pretium risus et vulputate auctor. Quisque placerat dictum massa, quis luctus justo lacinia non. Donec tempor a elit ullamcorper pellentesque. Ut ultricies vel purus a porta.&lt;br&gt;&lt;/p&gt;', ''),
(43, '2016-02-19 11:05:52', '1456273908_4756.png', '', 'Testing long titles to see the effect on wrapping and soforth', '2016-02-19', 'test', '&lt;p style=&quot;line-height: 1.4;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam volutpat, ipsum non tristique tincidunt, leo mi dignissim lorem, quis pellentesque nunc augue sit amet nibhPellentesque habitant morbi tristique senectus&amp;nbsp;. et netus et malesuada fames ac turpis egestas. Curabitur malesuada commodo ligula. Suspendisse ut vestibulum neque. Praesent vel tortor sit amet nibh condimentum eleifend nec at augue. Phasellus feugiat pulvinar quam, quis sollicitudin nibh dignissim eu. Aenean cursus hendrerit erat ac bibendum. Sed egestas semper porta. Phasellus pretium risus et vulputate auctor. Quisque placerat dictum massa, quis luctus justo lacinia non. Donec tempor a elit ullamcorper pellentesque. Ut ultricies vel purus a porta.&lt;/p&gt;&lt;p style=&quot;line-height: 1.4;&quot;&gt;&lt;a href=&quot;http://localhost/binair01/admin.php?editingPost=1&amp;amp;id=43&quot;&gt;link&lt;/a&gt;&lt;/p&gt;&lt;p style=&quot;line-height: 1.4;&quot;&gt;&lt;br&gt;&lt;/p&gt;', ''),
(44, '2016-02-23 11:32:44', '1456227163_8095.jpg', 'https://www.youtube.com/watch?v=5yVMrkScy78', 'TestYoutubeCover', '2016-02-23', 'youtube, video, ipsum, lor', 'Must be a youtube link! If this field is filled in, the coverimage on the newspage will be replaced by the youtube video.Coverimage is still required for the small preview on the main page.Must be a youtube link! If this field is filled in, the coverimage on the newspage will be replaced by the youtube video.Coverimage is still required for the small preview on the main page.Must be a youtube link! If this field is filled in, the coverimage on the newspage will be replaced by the youtube video.Coverimage is still required for the small preview on the main page.', '');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `coverimage` text NOT NULL,
  `title` varchar(200) NOT NULL,
  `venue` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `ticketsurl` varchar(200) NOT NULL,
  `sortdate` date NOT NULL,
  `hour` varchar(5) NOT NULL,
  `tags` varchar(500) NOT NULL,
  `preview` text NOT NULL,
  `body` text NOT NULL,
  `galleryimages` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `created`, `coverimage`, `title`, `venue`, `address`, `ticketsurl`, `sortdate`, `hour`, `tags`, `preview`, `body`, `galleryimages`) VALUES
(2, '2016-02-24 14:43:37', '1456325017_4704.jpg', '# gare centrale # Long Title test ok yeah ok test', 'Kunstencentrum Vooruit', 'Kantien 9, 3000 Gent', 'http://getbootstrap.com/2.3.2/scaffolding.html', '2016-03-01', '21h30', 'tag, tag, tag', '&lt;p style=&quot;line-height: 1.4;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam&lt;br&gt;&lt;/p&gt;', '&lt;p style=&quot;line-height: 1.4;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam volutpat, ipsum non tristique tincidunt, leo mi dignissim lorem, quis pellentesque nunc augue sit amet nibh. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur malesuada commodo ligula. Suspendisse ut vestibulum neque. Praesent vel tortor sit amet nibh condimentum eleifend nec at augue. Phasellus feugiat pulvinar quam, quis sollicitudin nibh dignissim eu. Aenean cursus hendrerit erat ac bibendum. Sed egestas semper porta. Phasellus pretium risus et vulputate auctor. Quisque placerat dictum massa, quis luctus justo lacinia non. Donec tempor a elit ullamcorper pellentesque. Ut ultricies vel purus a porta.&lt;br&gt;&lt;/p&gt;', ''),
(3, '2016-02-24 15:18:46', '1456327126_1985.jpg', 'Title', 'Kunstencentrum Vooruit', 'Kantien 9, 3000 Gent', 'http://tvb-design.com/', '2016-03-31', '21h30', 'tag, tag, tag', '&lt;p style=&quot;line-height: 1.4;&quot;&gt;Lots of great bands!&lt;/p&gt;&lt;ul&gt;&lt;li style=&quot;line-height: 1.4;&quot;&gt;Band number 1&lt;/li&gt;&lt;li style=&quot;line-height: 1.4;&quot;&gt;Band number 2&lt;/li&gt;&lt;li style=&quot;line-height: 1.4;&quot;&gt;Also band Number 3!&lt;/li&gt;&lt;/ul&gt;', '&lt;p style=&quot;line-height: 1.4;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam volutpat, ipsum non tristique tincidunt, leo mi dignissim lorem, quis pellentesque nunc augue sit amet nibh. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur malesuada commodo ligula. Suspendisse ut vestibulum neque. Praesent vel tortor sit amet nibh condimentum eleifend nec at augue. Phasellus feugiat pulvinar quam, quis sollicitudin nibh dignissim eu. Aenean cursus hendrerit erat ac bibendum. Sed egestas semper porta. Phasellus pretium risus et vulputate auctor. Quisque placerat dictum massa, quis luctus justo lacinia non. Donec tempor a elit ullamcorper pellentesque. Ut ultricies vel purus a porta.&lt;br&gt;&lt;/p&gt;', ''),
(4, '2016-02-24 16:09:53', '1456330192_6455.jpg', 'New Event', 'Vooruit', 'Gent', 'http://www.wordreference.com/enfr/wall', '2016-02-01', '21h30', 'tag, lorem, vooruit', '', '&lt;p style=&quot;line-height: 1.4;&quot;&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam volutpat, ipsum non tristique tincidunt, leo mi dignissim lorem, quis pellentesque nunc augue sit amet nibh. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Curabitur malesuada commodo ligula. Suspendisse ut vestibulum neque. Praesent vel tortor sit amet nibh condimentum eleifend nec at augue. Phasellus feugiat pulvinar quam, quis sollicitudin nibh dignissim eu. Aenean cursus hendrerit erat ac bibendum. Sed egestas semper porta. Phasellus pretium risus et vulputate auctor. Quisque placerat dictum massa, quis luctus justo lacinia non. Donec tempor a elit ullamcorper pellentesque. Ut ultricies vel purus a porta.&lt;br&gt;&lt;/p&gt;', ''),
(5, '2016-02-25 11:44:12', '1456400651_5871.jpg', 'Old Event', 'Op Café', 'hiere', 'https://translate.google.com/', '2015-12-14', '21h30', 'ld, lorem', 'Donec id elit non mi porta gravida at&amp;nbsp;eget metus. Fusce dapibus, tellus ac cursus commodo,&amp;nbsp;tortormauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod.&amp;nbsp;Donec sed odio dui.', 'Donec id elit non mi porta gravida at&amp;nbsp;eget metus. Fusce dapibus, tellus ac cursus commodo,&amp;nbsp;tortormauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod.&amp;nbsp;Donec sed odio dui.Donec id elit non mi porta gravida at&amp;nbsp;eget metus. Fusce dapibus, tellus ac cursus commodo,&amp;nbsp;tortormauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod.&amp;nbsp;Donec sed odio dui.', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogposts`
--
ALTER TABLE `blogposts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogposts`
--
ALTER TABLE `blogposts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
