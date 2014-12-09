--
-- Database: `dbb`
--

-- --------------------------------------------------------

--
-- `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump `users`
--

INSERT INTO `users` (`id`, `name`, `token`) VALUES
(1, '<your_name>', '');
