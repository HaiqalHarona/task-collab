-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema notion_budget
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema notion_budget
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `notion_budget` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `notion_budget` ;

-- -----------------------------------------------------
-- Table `notion_budget`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `notion_budget`.`user` (
  `email` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NULL DEFAULT NULL,
  `password` VARCHAR(255) NULL DEFAULT NULL,
  `google_id` VARCHAR(255) NULL DEFAULT NULL,
  `github_id` VARCHAR(255) NULL DEFAULT NULL,
  `avatar` TEXT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`email`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `notion_budget`.`workspaces`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `notion_budget`.`workspaces` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `owner_email` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `owner_email` (`owner_email` ASC) VISIBLE,
  CONSTRAINT `workspaces_ibfk_1`
    FOREIGN KEY (`owner_email`)
    REFERENCES `notion_budget`.`user` (`email`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `notion_budget`.`projects`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `notion_budget`.`projects` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `workspace_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `icon` VARCHAR(50) NULL DEFAULT NULL,
  `color` VARCHAR(7) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `workspace_id` (`workspace_id` ASC) VISIBLE,
  CONSTRAINT `projects_ibfk_1`
    FOREIGN KEY (`workspace_id`)
    REFERENCES `notion_budget`.`workspaces` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `notion_budget`.`tasks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `notion_budget`.`tasks` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `project_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `status` VARCHAR(50) NULL DEFAULT 'todo',
  `priority` ENUM('low', 'medium', 'high') NULL DEFAULT 'medium',
  `due_date` DATETIME NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `project_id` (`project_id` ASC) VISIBLE,
  CONSTRAINT `tasks_ibfk_1`
    FOREIGN KEY (`project_id`)
    REFERENCES `notion_budget`.`projects` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `notion_budget`.`comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `notion_budget`.`comments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` BIGINT UNSIGNED NOT NULL,
  `user_email` VARCHAR(255) NOT NULL,
  `body` TEXT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `task_id` (`task_id` ASC) VISIBLE,
  INDEX `user_email` (`user_email` ASC) VISIBLE,
  CONSTRAINT `comments_ibfk_1`
    FOREIGN KEY (`task_id`)
    REFERENCES `notion_budget`.`tasks` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `comments_ibfk_2`
    FOREIGN KEY (`user_email`)
    REFERENCES `notion_budget`.`user` (`email`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `notion_budget`.`tags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `notion_budget`.`tags` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `workspace_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `color` VARCHAR(7) NULL DEFAULT '#808080',
  PRIMARY KEY (`id`),
  INDEX `workspace_id` (`workspace_id` ASC) VISIBLE,
  CONSTRAINT `tags_ibfk_1`
    FOREIGN KEY (`workspace_id`)
    REFERENCES `notion_budget`.`workspaces` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `notion_budget`.`task_assignees`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `notion_budget`.`task_assignees` (
  `task_id` BIGINT UNSIGNED NOT NULL,
  `user_email` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`task_id`, `user_email`),
  INDEX `user_email` (`user_email` ASC) VISIBLE,
  CONSTRAINT `task_assignees_ibfk_1`
    FOREIGN KEY (`task_id`)
    REFERENCES `notion_budget`.`tasks` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `task_assignees_ibfk_2`
    FOREIGN KEY (`user_email`)
    REFERENCES `notion_budget`.`user` (`email`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `notion_budget`.`task_tags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `notion_budget`.`task_tags` (
  `task_id` BIGINT UNSIGNED NOT NULL,
  `tag_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`task_id`, `tag_id`),
  INDEX `tag_id` (`tag_id` ASC) VISIBLE,
  CONSTRAINT `task_tags_ibfk_1`
    FOREIGN KEY (`task_id`)
    REFERENCES `notion_budget`.`tasks` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `task_tags_ibfk_2`
    FOREIGN KEY (`tag_id`)
    REFERENCES `notion_budget`.`tags` (`id`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


-- -----------------------------------------------------
-- Table `notion_budget`.`workspace_members`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `notion_budget`.`workspace_members` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `workspace_id` BIGINT UNSIGNED NOT NULL,
  `user_email` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'member', 'viewer') NULL DEFAULT 'member',
  `joined_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `workspace_id` (`workspace_id` ASC) VISIBLE,
  INDEX `user_email` (`user_email` ASC) VISIBLE,
  CONSTRAINT `workspace_members_ibfk_1`
    FOREIGN KEY (`workspace_id`)
    REFERENCES `notion_budget`.`workspaces` (`id`)
    ON DELETE CASCADE,
  CONSTRAINT `workspace_members_ibfk_2`
    FOREIGN KEY (`user_email`)
    REFERENCES `notion_budget`.`user` (`email`)
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
