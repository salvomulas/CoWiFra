SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `webre` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `webre` ;

-- -----------------------------------------------------
-- Table `webre`.`temp_users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `webre`.`temp_users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(255) NOT NULL ,
  `password` VARCHAR(255) NOT NULL ,
  `firstname` VARCHAR(255) NOT NULL ,
  `lastname` VARCHAR(255) NOT NULL ,
  `email` VARCHAR(255) NOT NULL ,
  `key` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) ,
  UNIQUE INDEX `key_UNIQUE` (`key` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `webre`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `webre`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `create_timestamp` DATETIME NOT NULL ,
  `username` VARCHAR(255) NOT NULL ,
  `password` VARCHAR(255) NOT NULL ,
  `firstname` VARCHAR(255) NOT NULL ,
  `lastname` VARCHAR(255) NOT NULL ,
  `email` VARCHAR(255) NOT NULL ,
  `job_description` VARCHAR(1000) NULL ,
  `company` VARCHAR(255) NULL ,
  `deleted` BIT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) ,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `webre`.`projects`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `webre`.`projects` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `create_timestamp` DATETIME NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `description` VARCHAR(1000) NULL ,
  `general_requirements` VARCHAR(4000) NULL ,
  `owner` INT NOT NULL ,
  `artifacts_directory` VARCHAR(255) NOT NULL ,
  `deleted` BIT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_projects_users_idx` (`owner` ASC) ,
  UNIQUE INDEX `artifacts_directory_UNIQUE` (`artifacts_directory` ASC) ,
  CONSTRAINT `fk_projects_users`
    FOREIGN KEY (`owner` )
    REFERENCES `webre`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `webre`.`projects_has_users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `webre`.`projects_has_users` (
  `projects_id` INT NOT NULL ,
  `users_id` INT NOT NULL ,
  `is_admin` BIT NOT NULL ,
  PRIMARY KEY (`projects_id`, `users_id`) ,
  INDEX `fk_projects_has_users_users1_idx` (`users_id` ASC) ,
  INDEX `fk_projects_has_users_projects1_idx` (`projects_id` ASC) ,
  CONSTRAINT `fk_projects_has_users_projects1`
    FOREIGN KEY (`projects_id` )
    REFERENCES `webre`.`projects` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_projects_has_users_users1`
    FOREIGN KEY (`users_id` )
    REFERENCES `webre`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `webre`.`artifacts`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `webre`.`artifacts` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `create_timestamp` DATETIME NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `path_on_fs` VARCHAR(255) NOT NULL ,
  `description` VARCHAR(1000) NULL ,
  `owner` INT NOT NULL ,
  `projects_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_artifact_users1_idx` (`owner` ASC) ,
  INDEX `fk_artifact_projects1_idx` (`projects_id` ASC) ,
  UNIQUE INDEX `path_on_fs_projects_id_UNIQUE` (`path_on_fs` ASC, `projects_id` ASC) ,
  CONSTRAINT `fk_artifact_users1`
    FOREIGN KEY (`owner` )
    REFERENCES `webre`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_artifact_projects1`
    FOREIGN KEY (`projects_id` )
    REFERENCES `webre`.`projects` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `webre`.`history`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `webre`.`history` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `action_timestamp` DATETIME NOT NULL ,
  `php_function` VARCHAR(255) NOT NULL ,
  `action_description` VARCHAR(255) NOT NULL ,
  `projects_id` INT NOT NULL ,
  `users_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_history_projects1_idx` (`projects_id` ASC) ,
  INDEX `fk_history_users1_idx` (`users_id` ASC) ,
  CONSTRAINT `fk_history_projects1`
    FOREIGN KEY (`projects_id` )
    REFERENCES `webre`.`projects` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_history_users1`
    FOREIGN KEY (`users_id` )
    REFERENCES `webre`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `webre`.`sketches`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `webre`.`sketches` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `create_timestamp` DATETIME NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `description` VARCHAR(1000) NOT NULL ,
  `owner` INT NOT NULL ,
  `projects_id` INT NOT NULL ,
  `deleted` BIT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_sketch_users1_idx` (`owner` ASC) ,
  INDEX `fk_sketch_projects1_idx` (`projects_id` ASC) ,
  UNIQUE INDEX `name_projects_id_UNIQUE` (`projects_id` ASC, `name` ASC) ,
  CONSTRAINT `fk_sketch_users1`
    FOREIGN KEY (`owner` )
    REFERENCES `webre`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sketch_projects1`
    FOREIGN KEY (`projects_id` )
    REFERENCES `webre`.`projects` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `webre`.`sketch_archives`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `webre`.`sketch_archives` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sketches_id` INT NOT NULL ,
  `create_timestamp` DATETIME NOT NULL ,
  `data` MEDIUMTEXT NOT NULL ,
  `description` VARCHAR(1000) NOT NULL ,
  `creator` INT NOT NULL ,
  INDEX `fk_sketch_archives_users1_idx` (`creator` ASC) ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_sketch_archives_sketches1`
    FOREIGN KEY (`sketches_id` )
    REFERENCES `webre`.`sketches` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_sketch_archives_users1`
    FOREIGN KEY (`creator` )
    REFERENCES `webre`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `webre` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
