-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Giu 19, 2019 alle 18:12
-- Versione del server: 10.1.31-MariaDB
-- Versione PHP: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s265353`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `parametri`
--

DROP TABLE IF EXISTS `parametri`;
CREATE TABLE `parametri` (
  `file` int(11) NOT NULL,
  `colonne` int(11) NOT NULL,
  `totPosti` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `parametri`
--

INSERT INTO `parametri` (`file`, `colonne`, `totPosti`) VALUES
(10, 6, 60);

-- --------------------------------------------------------

--
-- Struttura della tabella `prenotazioni`
--

DROP TABLE IF EXISTS `prenotazioni`;
CREATE TABLE `prenotazioni` (
  `posto` varchar(1) NOT NULL,
  `fila` int(3) NOT NULL,
  `stato` enum('booked','occupied','','') NOT NULL,
  `utente` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `prenotazioni`
--

INSERT INTO `prenotazioni` (`posto`, `fila`, `stato`, `utente`) VALUES
('A', 4, 'occupied', 1),
('B', 2, 'booked', 2),
('B', 3, 'booked', 2),
('B', 4, 'booked', 2),
('D', 4, 'occupied', 1),
('F', 4, 'occupied', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

DROP TABLE IF EXISTS `utenti`;
CREATE TABLE `utenti` (
  `id` int(2) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `username`, `password`) VALUES
(1, 'u1@p.it', '$2y$10$5azO9tkewLKBChL4JwiV3ukrQZENYA2BM0kxCjc7faPWEF65tkU2K'),
(2, 'u2@p.it', '$2y$10$Xuwtp6aXZLBL/lY6T6JlBOo/DsDr6Rbv9TLqzeyjjFIGQUSrtqS0a');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `parametri`
--
ALTER TABLE `parametri`
  ADD PRIMARY KEY (`colonne`,`file`);

--
-- Indici per le tabelle `prenotazioni`
--
ALTER TABLE `prenotazioni`
  ADD PRIMARY KEY (`posto`,`fila`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
