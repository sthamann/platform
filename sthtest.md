Dev Workshop Project Product Ratings

Todos
--

* Product Version ID < Bewertungen hängen rein logisch nicht an einer bestimmten Produkt Version
* API Calls geben keine Bewertungen zurück cache/clear
* Migration anlegen
* Bewertungen am Produkt (Automatisch?, Durchschnitt berechnen) 
    * Computed Fields
    * Feld an Entität die nicht aus der Datenbank kommen
    * FloatField
    * Flag Computed
    * Subscriper product_rating.loaded
    * MediaFolderLoadedSubscriber
* Admin Module
    * Listing 
    * Action Bar (Öffnen, Freigabe, Löschen)
    * Search ?
    * Filter (Nur offene, nur schlechte)
    * Erstellen von Bewertungen zum Testen (CREATE)
    * Auswahl Produkt, Sales Channel, Language (Search)
* Konfiguration
    * eMail Templates
    * Action-URL Frontend ?
* Storefront
    * Ausgabe Bewertungen am Produkt
    * Ausgabe Durchschnittsbewertung / Sternesystem im Listing
    * Eingabe am Produkt 
        * Context (Sales Channel + Language)
        * Eingeloggter User
        * Falls eingeloggt Spalten eMail + Name ausblenden
    * Double-Opt-In Mail
    
     


? Database Schema

Primary Key
Product Key
Context Key
UserId Key
shopId Key

UniqueIndex - Title + Content + Stars
--
Rating Date
Rating externalUser - Konfigurierbar
Rating externalEmail
Rating Labels / Origin / Source - Klassifikation
Rating Language - "de"
Rating title
Rating content
Rating positive - Increment
Rating negative - Increment
Rating points = 0.0 - 5.0
Rating Status = Approved 1, Declined 0
Rating Comment
Rating CommentDate
--

product_rating

id
product_id
customer_id NULL
external_user
external_email
origin
sales_channel_id
language_id
title
content
positive
negative
points
status
comment
comment_created_at
created_at
updated_at


CREATE TABLE `product_rating` (
    `id` BINARY(16) NOT NULL,
    `version_id` BINARY(16) NOT NULL,
    `product_id` BINARY(16) NULL,
    `customer_id` BINARY(16) NULL,
    `sales_channel_id` BINARY(16) NULL,
    `language_id` BINARY(16) NULL,
    `external_user` VARCHAR(255) NULL,
    `external_email` VARCHAR(255) NULL,
    `title` VARCHAR(255) NULL,
    `content` LONGTEXT NULL,
    `positive` INT(11) NULL,
    `negative` INT(11) NULL,
    `points` DOUBLE NULL,
    `status` TINYINT(1) NULL DEFAULT '0',
    `comment` VARCHAR(255) NULL,
    `comment_created_at` DATETIME(3) NULL,
    `updated_at` DATETIME(3) NOT NULL,
    `created_at` DATETIME(3) NOT NULL,
    `product_version_id` BINARY(16) NOT NULL,
    PRIMARY KEY (`id`,`version_id`),
    KEY `fk.product_rating.product_id` (`product_id`,`product_version_id`),
    KEY `fk.product_rating.customer_id` (`customer_id`),
    KEY `fk.product_rating.sales_channel_id` (`sales_channel_id`),
    KEY `fk.product_rating.language_id` (`language_id`),
    CONSTRAINT `fk.product_rating.product_id` FOREIGN KEY (`product_id`,`product_version_id`) REFERENCES `product` (`id`,`version_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk.product_rating.customer_id` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk.product_rating.sales_channel_id` FOREIGN KEY (`sales_channel_id`) REFERENCES `sales_channel` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk.product_rating.language_id` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


SET NAMES utf8mb4;

INSERT INTO `product_rating` (`id`, `version_id`, `product_id`, `customer_id`, `sales_channel_id`, `language_id`, `external_user`, `external_email`, `title`, `content`, `positive`, `negative`, `points`, `status`, `comment`, `comment_created_at`, `updated_at`, `created_at`, `product_version_id`) VALUES
(UNHEX('00000000000000000000000000000000'),	UNHEX('00000000000000000000000000000000'),	UNHEX('001D52A4AC7F45A39E360DBF3EF43013'),	UNHEX('0A511F525D8B4F3BA5FE69D73BA9A4AF'),	UNHEX('288DDD0AA2324633A279FFFE2922F0B8'),	UNHEX('2FBB5FE2E29A4D70AA5854CE7CE3E20B'),	NULL,	NULL,	'Awesome product',	'That is pretty awesome',	0,	0,	4,	0,	'Hello World',	'2019-04-11 08:06:50.000',	'2019-04-11 08:06:50.000',	'2019-04-11 08:06:50.000',	UNHEX('0FA91CE3E96A4BC2BE4BD9CE752C3425'));

