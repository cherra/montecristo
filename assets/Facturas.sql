ALTER TABLE `montecristo`.`Facturas` 
ADD COLUMN `pdf` LONGBLOB NOT NULL AFTER `estado`,
ADD COLUMN `xml` LONGBLOB NOT NULL AFTER `pdf`;

