-- MySQL Script generated by MySQL Workbench
-- 01/09/16 10:43:51
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
DROP TABLE IF EXISTS `m2test6`.`USER` ;

CREATE TABLE IF NOT EXISTS `m2test6`.`USER` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'teacher', 'student') NOT NULL,
  `first_name` VARCHAR(255) NOT NULL,
  `last_name` VARCHAR(255) NOT NULL,
  `date_of_birth` DATE NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(45) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `valid` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `login_UNIQUE` (`login` ASC),
  INDEX `first_name_index` (`first_name` ASC),
  INDEX `last_name_index` (`last_name` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  INDEX `role_index` (`role` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `m2test6`.`MODULE`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `m2test6`.`MODULE` ;

CREATE TABLE IF NOT EXISTS `m2test6`.`MODULE` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `code` VARCHAR(6) NOT NULL,
  `coefficient` INT UNSIGNED NOT NULL,
  `description` VARCHAR(1024) NULL,
  `valid` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `name_index` (`name` ASC),
  UNIQUE INDEX `code_UNIQUE` (`code` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `m2test6`.`STUDENT_MODULE_SUBSCRIPTION`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `m2test6`.`STUDENT_MODULE_SUBSCRIPTION` ;

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
DROP TABLE IF EXISTS `m2test6`.`TEACHER_MODULE_SUBSCRIPTION` ;

CREATE TABLE IF NOT EXISTS `m2test6`.`TEACHER_MODULE_SUBSCRIPTION` (
  `user_id` INT UNSIGNED NOT NULL,
  `module_id` INT NOT NULL,
  `main` TINYINT(1) NOT NULL,
  PRIMARY KEY (`user_id`, `module_id`),
  INDEX `fk_TEACHER_MODULE_SUBSCRIPTION_MODULE1_idx` (`module_id` ASC),
  INDEX `module_main_teacher_index` (`module_id` ASC, `main` ASC),
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
DROP TABLE IF EXISTS `m2test6`.`NOTIFICATION` ;

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
CREATE TABLE IF NOT EXISTS `m2test6`.`MODULE_STATS` (`module_id` INT, `average` INT, `min_mark` INT, `max_mark` INT, `standard_deviation` INT, `student_count` INT);

-- -----------------------------------------------------
-- Placeholder table for view `m2test6`.`STUDENT_STATS`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `m2test6`.`STUDENT_STATS` (`id` INT, `average` INT);

-- -----------------------------------------------------
-- View `m2test6`.`MODULE_STATS`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `m2test6`.`MODULE_STATS` ;
DROP TABLE IF EXISTS `m2test6`.`MODULE_STATS`;
USE `m2test6`;
CREATE  OR REPLACE VIEW `MODULE_STATS` AS
	SELECT 
		module.id AS module_id,
		AVG(mark) AS average,
		MIN(mark) AS min_mark,
		MAX(mark) AS max_mark,
		STD(mark) AS standard_deviation,
        count(mark) AS student_count
	FROM
		MODULE module
			LEFT OUTER JOIN
		STUDENT_MODULE_SUBSCRIPTION ON id = module_id
	GROUP BY module.id;

-- -----------------------------------------------------
-- View `m2test6`.`STUDENT_STATS`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `m2test6`.`STUDENT_STATS` ;
DROP TABLE IF EXISTS `m2test6`.`STUDENT_STATS`;
USE `m2test6`;
CREATE  OR REPLACE VIEW `STUDENT_STATS` AS
    SELECT 
        user.id,
        SUM(module.coefficient * mark) / SUM(module.coefficient) AS average
    FROM
        USER user
            JOIN
        STUDENT_MODULE_SUBSCRIPTION ON user.id = user_id
            JOIN
        MODULE module ON module.id = module_id
	WHERE
		mark is not null
	GROUP BY user.id;
USE `m2test6`;

DELIMITER $$

USE `m2test6`$$
DROP TRIGGER IF EXISTS `m2test6`.`USER_BEFORE_INSERT_CHECK` $$
USE `m2test6`$$
CREATE DEFINER = CURRENT_USER TRIGGER `m2test6`.`USER_BEFORE_INSERT_CHECK` BEFORE INSERT ON `USER` FOR EACH ROW
BEGIN
	IF (NEW.email NOT REGEXP '^[^@]+@[^@]+\.[^@]{2,}$') THEN
		SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = 'bad email address', MYSQL_ERRNO = 1001;
    END IF;
END$$


USE `m2test6`$$
DROP TRIGGER IF EXISTS `m2test6`.`USER_BEFORE_UPDATE_CHECK` $$
USE `m2test6`$$
CREATE DEFINER = CURRENT_USER TRIGGER `m2test6`.`USER_BEFORE_UPDATE_CHECK` BEFORE UPDATE ON `USER` FOR EACH ROW
BEGIN
	IF (NEW.email NOT REGEXP '^[^@]+@[^@]+\.[^@]{2,}$') THEN
		SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = 'bad email address', MYSQL_ERRNO = 1001;
    END IF;
END$$


USE `m2test6`$$
DROP TRIGGER IF EXISTS `m2test6`.`STUDENT_MODULE_SUBSCRIPTION_BEFORE_UPDATE_CHECK` $$
USE `m2test6`$$
CREATE DEFINER = CURRENT_USER TRIGGER `m2test6`.`STUDENT_MODULE_SUBSCRIPTION_BEFORE_UPDATE_CHECK` BEFORE UPDATE ON `STUDENT_MODULE_SUBSCRIPTION` FOR EACH ROW
BEGIN
	IF (NEW.mark is not null and NEW.mark NOT BETWEEN 0 AND 20) THEN
		SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = 'mark must be between 0 and 20',
			MYSQL_ERRNO = 1001;
    END IF;
    IF (SELECT count(u.id) FROM USER u WHERE u.id = NEW.user_id AND u.role <> 'student') > 0 THEN
		SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = 'user must be a student',
            MYSQL_ERRNO = 1001;
    END IF;
END$$


USE `m2test6`$$
DROP TRIGGER IF EXISTS `m2test6`.`STUDENT_MODULE_SUBSCRIPTION_BEFORE_UPDATE_CHECK` $$
USE `m2test6`$$
CREATE DEFINER = CURRENT_USER TRIGGER `m2test6`.`STUDENT_MODULE_SUBSCRIPTION_BEFORE_UPDATE_CHECK` BEFORE UPDATE ON `STUDENT_MODULE_SUBSCRIPTION` FOR EACH ROW
BEGIN
	IF (NEW.mark is not null and NEW.mark NOT BETWEEN 0 AND 20) THEN
		SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = 'mark must be between 0 and 20',
			MYSQL_ERRNO = 1001;
    END IF;
    IF (SELECT count(u.id) FROM USER u WHERE u.id = NEW.user_id AND u.role <> 'student') > 0 THEN
		SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = 'user must be a student',
            MYSQL_ERRNO = 1001;
    END IF;
END$$


USE `m2test6`$$
DROP TRIGGER IF EXISTS `m2test6`.`TEACHER_MODULE_SUBSCRIPTION_BEFORE_INSERT_CHECK` $$
USE `m2test6`$$
CREATE DEFINER = CURRENT_USER TRIGGER `m2test6`.`TEACHER_MODULE_SUBSCRIPTION_BEFORE_INSERT_CHECK` BEFORE INSERT ON `TEACHER_MODULE_SUBSCRIPTION` FOR EACH ROW
BEGIN
	IF (SELECT 1 FROM TEACHER_MODULE_SUBSCRIPTION AS t WHERE t.module_id = NEW.module_id AND t.main AND t.user_id <> NEW.user_id LIMIT 1) THEN
        SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = 'There\'s already a main teacher for this module',
            MYSQL_ERRNO = 1001;
    END IF;
    IF (SELECT 1 FROM USER as u WHERE u.id = NEW.user_id AND u.role <> 'teacher' LIMIT 1) THEN
		SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = 'user must be a teacher',
            MYSQL_ERRNO = 1001;
    END IF;
END$$


USE `m2test6`$$
DROP TRIGGER IF EXISTS `m2test6`.`TEACHER_MODULE_SUBSCRIPTION_BEFORE_UPDATE_CHECK` $$
USE `m2test6`$$
CREATE DEFINER = CURRENT_USER TRIGGER `m2test6`.`TEACHER_MODULE_SUBSCRIPTION_BEFORE_UPDATE_CHECK` BEFORE UPDATE ON `TEACHER_MODULE_SUBSCRIPTION` FOR EACH ROW
BEGIN
	IF (SELECT 1 FROM TEACHER_MODULE_SUBSCRIPTION AS t WHERE t.module_id = NEW.module_id AND t.main AND t.user_id <> NEW.user_id LIMIT 1) THEN
        SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = 'There\'s already a main teacher for this module',
            MYSQL_ERRNO = 1001;
    END IF;
    IF (SELECT 1 FROM USER as u WHERE u.id = NEW.user_id AND u.role <> 'teacher' LIMIT 1) THEN
		SIGNAL SQLSTATE '45000'
			SET MESSAGE_TEXT = 'user must be a teacher',
            MYSQL_ERRNO = 1001;
    END IF;
END$$


DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
