CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment_content_id` int(11) NOT NULL,
  `comment_parent_id` int(11) NOT NULL,
  `comment_name` varchar(256) NOT NULL,
  `comment_time` datetime NOT NULL,
  `comment_body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comment_time` (`comment_time`),
  ADD KEY `comment_parent_id` (`comment_parent_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;