-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 23 Lut 2023, 22:42
-- Wersja serwera: 10.4.25-MariaDB
-- Wersja PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `finance_web_application`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expenses`
--

CREATE TABLE `expenses` (
  `id_expenses` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `id_users_expenses_categories` int(11) NOT NULL,
  `id_payment` int(11) NOT NULL,
  `expense_amout` decimal(8,2) NOT NULL,
  `expense_date` date NOT NULL,
  `expense_description` varchar(100) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id_categories` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `expense_category` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `expense_categories`
--

INSERT INTO `expense_categories` (`id_categories`, `id_users`, `expense_category`) VALUES
(1, 1, 'jedzenie'),
(2, 1, 'wycieczka'),
(3, 1, 'mieszkanie'),
(4, 1, 'szkolenia'),
(5, 1, 'transport'),
(6, 1, 'książka'),
(7, 1, 'telekomunikacja'),
(8, 1, 'oszczędności'),
(9, 1, 'opieka zdrowotna'),
(10, 1, 'emerytura'),
(11, 1, 'ubranie'),
(12, 1, 'spłata długów'),
(13, 1, 'higiena'),
(14, 1, 'darowizna'),
(15, 1, 'dzieci'),
(16, 1, 'rozrywka'),
(17, 1, 'inne wydatki');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expense_deafult_categories`
--

CREATE TABLE `expense_deafult_categories` (
  `id_categories` int(11) NOT NULL,
  `expense_category` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `expense_deafult_categories`
--

INSERT INTO `expense_deafult_categories` (`id_categories`, `expense_category`) VALUES
(1, 'jedzenie'),
(2, 'wycieczka'),
(3, 'mieszkanie'),
(4, 'szkolenia'),
(5, 'transport'),
(6, 'książka'),
(7, 'telekomunikacja'),
(8, 'oszczędności'),
(9, 'opieka zdrowotna'),
(10, 'emerytura'),
(11, 'ubranie'),
(12, 'spłata długów'),
(13, 'higiena'),
(14, 'darowizna'),
(15, 'dzieci'),
(16, 'rozrywka'),
(17, 'inne wydatki');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expense_payment`
--

CREATE TABLE `expense_payment` (
  `id_payment` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `expense_payment_method` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `expense_payment`
--

INSERT INTO `expense_payment` (`id_payment`, `id_users`, `expense_payment_method`) VALUES
(1, 1, 'przelew'),
(2, 1, 'gotówka'),
(3, 1, 'karta bankowa'),
(4, 1, 'karta debetowa');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expense_payment_deafult`
--

CREATE TABLE `expense_payment_deafult` (
  `id_deafult_payment` int(11) NOT NULL,
  `expense_deafult_payment_method` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `expense_payment_deafult`
--

INSERT INTO `expense_payment_deafult` (`id_deafult_payment`, `expense_deafult_payment_method`) VALUES
(1, 'przelew'),
(2, 'gotówka'),
(3, 'karta bankowa'),
(4, 'karta debetowa');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incomes`
--

CREATE TABLE `incomes` (
  `id_incomes` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `id_users_incomes_categories` int(11) NOT NULL,
  `income_amout` decimal(8,2) NOT NULL,
  `income_date` date NOT NULL,
  `income_comment` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `incomes_deafult_categories`
--

CREATE TABLE `incomes_deafult_categories` (
  `id_categories` int(11) NOT NULL,
  `income_category` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `incomes_deafult_categories`
--

INSERT INTO `incomes_deafult_categories` (`id_categories`, `income_category`) VALUES
(1, 'wynagrodzenie'),
(2, 'odsetki bankowe'),
(3, 'sprzedaż na allegro'),
(4, 'inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `income_categories`
--

CREATE TABLE `income_categories` (
  `id_categories` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `income_category` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `income_categories`
--

INSERT INTO `income_categories` (`id_categories`, `id_users`, `income_category`) VALUES
(1, 1, 'wynagrodzenie'),
(2, 1, 'odsetki bankowe'),
(3, 1, 'sprzedaż na allegro'),
(4, 1, 'inne');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL,
  `user_name` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `user_email` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `user_password` varchar(255) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id_users`, `user_name`, `user_email`, `user_password`) VALUES
(1, 'Rachel', 'vitharig@gmail.com', '$2y$10$2R7ew3KAOwn.XuRGlC37I.NTtplO4cGpthO5ErWD/EIFzC1bIFBRu');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id_expenses`),
  ADD KEY `id_users` (`id_users`),
  ADD KEY `id_payment` (`id_payment`),
  ADD KEY `id_users_expenses_categories` (`id_users_expenses_categories`);

--
-- Indeksy dla tabeli `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id_categories`),
  ADD KEY `id_users` (`id_users`);

--
-- Indeksy dla tabeli `expense_deafult_categories`
--
ALTER TABLE `expense_deafult_categories`
  ADD PRIMARY KEY (`id_categories`);

--
-- Indeksy dla tabeli `expense_payment`
--
ALTER TABLE `expense_payment`
  ADD PRIMARY KEY (`id_payment`),
  ADD KEY `id_users` (`id_users`);

--
-- Indeksy dla tabeli `expense_payment_deafult`
--
ALTER TABLE `expense_payment_deafult`
  ADD PRIMARY KEY (`id_deafult_payment`);

--
-- Indeksy dla tabeli `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id_incomes`),
  ADD KEY `id_users` (`id_users`),
  ADD KEY `id_users_incomes_categories` (`id_users_incomes_categories`);

--
-- Indeksy dla tabeli `incomes_deafult_categories`
--
ALTER TABLE `incomes_deafult_categories`
  ADD PRIMARY KEY (`id_categories`);

--
-- Indeksy dla tabeli `income_categories`
--
ALTER TABLE `income_categories`
  ADD PRIMARY KEY (`id_categories`),
  ADD KEY `id_users` (`id_users`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id_expenses` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id_categories` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT dla tabeli `expense_deafult_categories`
--
ALTER TABLE `expense_deafult_categories`
  MODIFY `id_categories` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT dla tabeli `expense_payment`
--
ALTER TABLE `expense_payment`
  MODIFY `id_payment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `expense_payment_deafult`
--
ALTER TABLE `expense_payment_deafult`
  MODIFY `id_deafult_payment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id_incomes` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `incomes_deafult_categories`
--
ALTER TABLE `incomes_deafult_categories`
  MODIFY `id_categories` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `income_categories`
--
ALTER TABLE `income_categories`
  MODIFY `id_categories` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`),
  ADD CONSTRAINT `expenses_ibfk_2` FOREIGN KEY (`id_payment`) REFERENCES `expense_payment` (`id_payment`),
  ADD CONSTRAINT `expenses_ibfk_3` FOREIGN KEY (`id_users_expenses_categories`) REFERENCES `expense_categories` (`id_categories`);

--
-- Ograniczenia dla tabeli `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD CONSTRAINT `expense_categories_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`);

--
-- Ograniczenia dla tabeli `expense_payment`
--
ALTER TABLE `expense_payment`
  ADD CONSTRAINT `expense_payment_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`);

--
-- Ograniczenia dla tabeli `incomes`
--
ALTER TABLE `incomes`
  ADD CONSTRAINT `incomes_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`),
  ADD CONSTRAINT `incomes_ibfk_2` FOREIGN KEY (`id_users_incomes_categories`) REFERENCES `income_categories` (`id_categories`);

--
-- Ograniczenia dla tabeli `income_categories`
--
ALTER TABLE `income_categories`
  ADD CONSTRAINT `income_categories_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
