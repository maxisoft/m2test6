-- MySQL Script generated by MySQL Workbench
-- 01/04/16 20:44:44
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema m2test6
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `m2test6` ;

-- -----------------------------------------------------
-- Schema m2test6
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `m2test6` DEFAULT CHARACTER SET utf8 ;
USE `m2test6` ;

-- -----------------------------------------------------
-- Table `m2test6`.`USER`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `m2test6`.`USER` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'teacher', 'student') NOT NULL,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `date_of_birth` DATE NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `login_UNIQUE` (`login` ASC),
  INDEX `first_name_index` (`first_name` ASC),
  INDEX `last_name_index` (`last_name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `m2test6`.`MODULE`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `m2test6`.`MODULE` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `owner_user_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `coefficient` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `name_index` (`name` ASC),
  CONSTRAINT `fk_MODULE_USER`
    FOREIGN KEY (`owner_user_id`)
    REFERENCES `m2test6`.`USER` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `m2test6`.`STUDENT_MODULE_SUBSCRIPTION`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `m2test6`.`STUDENT_MODULE_SUBSCRIPTION` (
  `module_id` INT NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `mark` DECIMAL(6,4) UNSIGNED NULL,
  PRIMARY KEY (`module_id`, `user_id`),
  INDEX `fk_STUDENT_MODULE_SUBCRITPION_USER1_idx` (`user_id` ASC),
  CONSTRAINT `fk_STUDENT_MODULE_SUBCRITPION_MODULE1`
    FOREIGN KEY (`module_id`)
    REFERENCES `m2test6`.`MODULE` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_STUDENT_MODULE_SUBCRITPION_USER1`
    FOREIGN KEY (`user_id`)
    REFERENCES `m2test6`.`USER` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `m2test6`.`TEACHER_MODULE_SUBSCRIPTION`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `m2test6`.`TEACHER_MODULE_SUBSCRIPTION` (
  `user_id` INT UNSIGNED NOT NULL,
  `module_id` INT NOT NULL,
  PRIMARY KEY (`user_id`, `module_id`),
  INDEX `fk_TEACHER_MODULE_SUBSCRIPTION_MODULE1_idx` (`module_id` ASC),
  CONSTRAINT `fk_TEACHER_MODULE_SUBSCRIPTION_USER1`
    FOREIGN KEY (`user_id`)
    REFERENCES `m2test6`.`USER` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TEACHER_MODULE_SUBSCRIPTION_MODULE1`
    FOREIGN KEY (`module_id`)
    REFERENCES `m2test6`.`MODULE` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `m2test6`.`NOTIFICATION`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `m2test6`.`NOTIFICATION` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `message` VARCHAR(255) NULL,
  `read` TINYINT(1) NULL,
  `creation_date` DATETIME NULL,
  `target_user_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_NOTIFICATION_USER1_idx` (`target_user_id` ASC),
  CONSTRAINT `fk_NOTIFICATION_USER1`
    FOREIGN KEY (`target_user_id`)
    REFERENCES `m2test6`.`USER` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `m2test6` ;

-- -----------------------------------------------------
-- Placeholder table for view `m2test6`.`MODULE_STATS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `m2test6`.`MODULE_STATS` (`id` INT, `average` INT, `standard_deviation` INT);

-- -----------------------------------------------------
-- Placeholder table for view `m2test6`.`STUDENT_STATS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `m2test6`.`STUDENT_STATS` (`id` INT, `average` INT, `standard_deviation` INT);

-- -----------------------------------------------------
-- Placeholder table for view `m2test6`.`GLOBAL_STATS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `m2test6`.`GLOBAL_STATS` (`1` INT);

-- -----------------------------------------------------
-- View `m2test6`.`MODULE_STATS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `m2test6`.`MODULE_STATS`;
USE `m2test6`;
CREATE  OR REPLACE VIEW `MODULE_STATS` AS
    SELECT
        module.id,
        AVG(mark) AS average,
        (SELECT 1000) AS standard_deviation
    FROM
        MODULE module
            LEFT OUTER JOIN
        STUDENT_MODULE_SUBSCRIPTION ON id = module_id;

-- -----------------------------------------------------
-- View `m2test6`.`STUDENT_STATS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `m2test6`.`STUDENT_STATS`;
USE `m2test6`;
CREATE  OR REPLACE VIEW `STUDENT_STATS` AS
    SELECT
        user.id,
        AVG(mark* 0) AS average,
        (SELECT 1000) AS standard_deviation
    FROM
        USER user
            LEFT OUTER JOIN
        STUDENT_MODULE_SUBSCRIPTION ON user.id = user_id
            NATURAL JOIN
        MODULE;

-- -----------------------------------------------------
-- View `m2test6`.`GLOBAL_STATS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `m2test6`.`GLOBAL_STATS`;
USE `m2test6`;
CREATE  OR REPLACE VIEW `GLOBAL_STATS` AS
SELECT 1;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
