
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



CREATE TABLE `bill` (
  `Bill_ID` int(11) NOT NULL,
  `Bill_type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Struktura tabeli dla tabeli `booking`
--

CREATE TABLE `booking` (
  `Booking_ID` int(11) NOT NULL,
  `Person_ID` int(10) UNSIGNED NOT NULL,
  `Number_of_persons` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `Schedule_arrival` date NOT NULL DEFAULT current_timestamp(),
  `Schedule_departure` date NOT NULL DEFAULT current_timestamp(),
  `Special_service` text DEFAULT 'Brak',
  `Payment_name` varchar(20) NOT NULL,
  `Price` float NOT NULL,
  `Bill_type` varchar(15) NOT NULL,
  `Color_of_the_cabin` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


--
-- Struktura tabeli dla tabeli `color_of_the_cabin`
--

CREATE TABLE `color_of_the_cabin` (
  `Cabin_ID` int(11) NOT NULL,
  `Color` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


--
-- Struktura tabeli dla tabeli `extra_service`
--

CREATE TABLE `extra_service` (
  `Booking_ID` int(11) NOT NULL,
  `Price_ID` int(11) NOT NULL,
  `Special_Service` text CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci DEFAULT NULL,
  `Extra_Service_fee` float UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


--
-- Struktura tabeli dla tabeli `payment_type`
--

CREATE TABLE `payment_type` (
  `Payment_ID` int(11) NOT NULL,
  `Payment_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


--
-- Struktura tabeli dla tabeli `person`
--

CREATE TABLE `person` (
  `Person_ID` int(11) NOT NULL,
  `Booking_ID` int(11) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Surname` varchar(25) NOT NULL,
  `ID_card` varchar(25) NOT NULL,
  `PESEL` bigint(11) DEFAULT NULL,
  `Phone_number` char(15) DEFAULT NULL,
  `email` varchar(40) NOT NULL,
  `login` varchar(18) DEFAULT NULL,
  `pass` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


--
-- Struktura tabeli dla tabeli `price`
--

CREATE TABLE `price` (
  `Price_ID` int(11) NOT NULL,
  `Price` float UNSIGNED DEFAULT NULL,
  `Promo_rate` float UNSIGNED DEFAULT NULL,
  `Extra_service_fee` float UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


--
-- Struktura tabeli dla tabeli `price_schedule`
--

CREATE TABLE `price_schedule` (
  `Price_ID` int(11) NOT NULL,
  `Start_date` date NOT NULL,
  `End_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Struktura tabeli dla tabeli `promo_rate`
--

CREATE TABLE `promo_rate` (
  `Promo_ID` int(11) NOT NULL,
  `Promo_rate` float UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;


--
-- Indeksy dla tabeli `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`Bill_ID`);

--
-- Indeksy dla tabeli `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`Booking_ID`),
  ADD KEY `Person_ID` (`Person_ID`),
  ADD KEY `Payment_name` (`Payment_name`),
  ADD KEY `Price` (`Price`),
  ADD KEY `Bill_type` (`Bill_type`),
  ADD KEY `Color_of_the_cabin` (`Color_of_the_cabin`);

--
-- Indeksy dla tabeli `color_of_the_cabin`
--
ALTER TABLE `color_of_the_cabin`
  ADD PRIMARY KEY (`Cabin_ID`);

--
-- Indeksy dla tabeli `extra_service`
--
ALTER TABLE `extra_service`
  ADD KEY `Price_ID` (`Price_ID`),
  ADD KEY `Booking_ID` (`Booking_ID`);

--
-- Indeksy dla tabeli `payment_type`
--
ALTER TABLE `payment_type`
  ADD PRIMARY KEY (`Payment_ID`);

--
-- Indeksy dla tabeli `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`Person_ID`),
  ADD KEY `Booking_ID` (`Booking_ID`);

--
-- Indeksy dla tabeli `price`
--
ALTER TABLE `price`
  ADD PRIMARY KEY (`Price_ID`),
  ADD KEY `Promo_rate` (`Promo_rate`);

--
-- Indeksy dla tabeli `promo_rate`
--
ALTER TABLE `promo_rate`
  ADD PRIMARY KEY (`Promo_ID`);

