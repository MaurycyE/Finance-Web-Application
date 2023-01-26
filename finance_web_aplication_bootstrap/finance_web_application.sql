-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 26 Sty 2023, 14:57
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

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expense_deafult_categories`
--

CREATE TABLE `expense_deafult_categories` (
  `id_categories` int(11) NOT NULL,
  `expense_category` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expense_payment`
--

CREATE TABLE `expense_payment` (
  `id_payment` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `expense_payment_method` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `expense_payment_deafult`
--

CREATE TABLE `expense_payment_deafult` (
  `id_deafult_payment` int(11) NOT NULL,
  `expense_deafult_payment_method` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

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

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `income_categories`
--

CREATE TABLE `income_categories` (
  `id_categories` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `income_category` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

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
  ADD KEY `users.id_users` (`id_users`);

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
  MODIFY `id_categories` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `expense_deafult_categories`
--
ALTER TABLE `expense_deafult_categories`
  MODIFY `id_categories` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `expense_payment`
--
ALTER TABLE `expense_payment`
  MODIFY `id_payment` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `expense_payment_deafult`
--
ALTER TABLE `expense_payment_deafult`
  MODIFY `id_deafult_payment` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id_incomes` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `incomes_deafult_categories`
--
ALTER TABLE `incomes_deafult_categories`
  MODIFY `id_categories` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `income_categories`
--
ALTER TABLE `income_categories`
  MODIFY `id_categories` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT;

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
