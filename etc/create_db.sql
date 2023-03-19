-- Server-Version: 10.5.18-MariaDB-0+deb11u1
-- PHP-Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

CREATE TABLE `active_cards` (
  `card_src` varchar(255) NOT NULL,
  `card_id` int(255) NOT NULL,
  `card_name` varchar(255) NOT NULL,
  `left_val` varchar(255) NOT NULL,
  `top_val` varchar(255) NOT NULL,
  `game_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE `active_games` (
  `game_id` varchar(10) NOT NULL,
  `creation_date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

CREATE TABLE `hidden_cards` (
  `hidden_card_id` int(255) NOT NULL,
  `card_name` varchar(255) NOT NULL,
  `left_val` varchar(255) NOT NULL,
  `top_val` varchar(255) NOT NULL,
  `game_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `active_cards`
  ADD PRIMARY KEY (`card_id`);
ALTER TABLE `active_games`
  ADD PRIMARY KEY (`game_id`);
ALTER TABLE `hidden_cards`
  ADD PRIMARY KEY (`hidden_card_id`);

ALTER TABLE `active_cards`
  MODIFY `card_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
ALTER TABLE `hidden_cards`
  MODIFY `hidden_card_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
COMMIT;

