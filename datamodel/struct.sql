SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `osc_db` DEFAULT CHARACTER SET utf8 ;
USE `osc_db` ;

-- -----------------------------------------------------
-- Table `osc_db`.`t_admin`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_admin` (
  `pk_i_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `s_name` VARCHAR(100) NOT NULL ,
  `s_username` VARCHAR(40) NOT NULL ,
  `s_password` VARCHAR(40) NOT NULL ,
  `s_email` VARCHAR(100) NULL DEFAULT NULL ,
  `s_secret` VARCHAR(40) NULL DEFAULT NULL ,
  PRIMARY KEY (`pk_i_id`) ,
  UNIQUE INDEX `s_username` (`s_username` ASC) ,
  UNIQUE INDEX `s_email` (`s_email` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_alerts`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_alerts` (
  `s_email` VARCHAR(100) NULL DEFAULT NULL ,
  `fk_i_user_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `s_search` LONGTEXT NULL DEFAULT NULL ,
  `s_secret` VARCHAR(40) NULL DEFAULT NULL ,
  `b_active` TINYINT(1) NOT NULL DEFAULT '0' ,
  `e_type` ENUM('INSTANT','HOURLY','DAILY','WEEKLY','CUSTOM') NOT NULL )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_category`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_category` (
  `pk_i_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `fk_i_parent_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `i_expiration_days` INT(3) UNSIGNED NOT NULL DEFAULT '0' ,
  `i_position` INT(2) UNSIGNED NOT NULL DEFAULT '0' ,
  `b_enabled` TINYINT(1) NOT NULL DEFAULT '1' ,
  `s_icon` VARCHAR(250) NULL DEFAULT NULL ,
  PRIMARY KEY (`pk_i_id`) ,
  INDEX `fk_i_parent_id` (`fk_i_parent_id` ASC) ,
  INDEX `i_position` (`i_position` ASC) ,
  CONSTRAINT `t_category_ibfk_1`
    FOREIGN KEY (`fk_i_parent_id` )
    REFERENCES `osc_db`.`t_category` (`pk_i_id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_locale`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_locale` (
  `pk_c_code` CHAR(5) NOT NULL ,
  `s_name` VARCHAR(100) NOT NULL ,
  `s_short_name` VARCHAR(40) NOT NULL ,
  `s_description` VARCHAR(100) NOT NULL ,
  `s_version` VARCHAR(20) NOT NULL ,
  `s_author_name` VARCHAR(100) NOT NULL ,
  `s_author_url` VARCHAR(100) NOT NULL ,
  `s_currency_format` VARCHAR(50) NOT NULL ,
  `s_dec_point` VARCHAR(2) NULL DEFAULT '.' ,
  `s_thousands_sep` VARCHAR(2) NULL DEFAULT '' ,
  `i_num_dec` TINYINT(4) NULL DEFAULT '2' ,
  `s_date_format` VARCHAR(20) NOT NULL ,
  `s_stop_words` TEXT NULL DEFAULT NULL ,
  `b_enabled` TINYINT(1) NOT NULL DEFAULT '1' ,
  `b_enabled_bo` TINYINT(1) NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`pk_c_code`) ,
  UNIQUE INDEX `s_short_name` (`s_short_name` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_category_description`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_category_description` (
  `fk_i_category_id` INT(10) UNSIGNED NOT NULL ,
  `fk_c_locale_code` CHAR(5) NOT NULL ,
  `s_name` VARCHAR(100) NOT NULL ,
  `s_description` TEXT NULL DEFAULT NULL ,
  `s_slug` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`fk_i_category_id`, `fk_c_locale_code`) ,
  INDEX `fk_c_locale_code` (`fk_c_locale_code` ASC) ,
  CONSTRAINT `t_category_description_ibfk_1`
    FOREIGN KEY (`fk_i_category_id` )
    REFERENCES `osc_db`.`t_category` (`pk_i_id` ),
  CONSTRAINT `t_category_description_ibfk_2`
    FOREIGN KEY (`fk_c_locale_code` )
    REFERENCES `osc_db`.`t_locale` (`pk_c_code` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_category_stats`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_category_stats` (
  `fk_i_category_id` INT(10) UNSIGNED NOT NULL ,
  `i_num_items` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`fk_i_category_id`) ,
  CONSTRAINT `t_category_stats_ibfk_1`
    FOREIGN KEY (`fk_i_category_id` )
    REFERENCES `osc_db`.`t_category` (`pk_i_id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_country`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_country` (
  `pk_c_code` CHAR(2) NOT NULL ,
  `fk_c_locale_code` CHAR(5) NOT NULL ,
  `s_name` VARCHAR(80) NOT NULL ,
  PRIMARY KEY (`pk_c_code`, `fk_c_locale_code`) ,
  INDEX `fk_c_locale_code` (`fk_c_locale_code` ASC) ,
  CONSTRAINT `t_country_ibfk_1`
    FOREIGN KEY (`fk_c_locale_code` )
    REFERENCES `osc_db`.`t_locale` (`pk_c_code` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_region`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_region` (
  `pk_i_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `fk_c_country_code` CHAR(2) NOT NULL ,
  `s_name` VARCHAR(60) NOT NULL ,
  `b_active` TINYINT(1) NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`pk_i_id`) ,
  INDEX `fk_c_country_code` (`fk_c_country_code` ASC) ,
  CONSTRAINT `t_region_ibfk_1`
    FOREIGN KEY (`fk_c_country_code` )
    REFERENCES `osc_db`.`t_country` (`pk_c_code` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_city`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_city` (
  `pk_i_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `fk_i_region_id` INT(10) UNSIGNED NOT NULL ,
  `s_name` VARCHAR(60) NOT NULL ,
  `fk_c_country_code` CHAR(2) NULL DEFAULT NULL ,
  `b_active` TINYINT(1) NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`pk_i_id`) ,
  INDEX `fk_i_region_id` (`fk_i_region_id` ASC) ,
  INDEX `fk_c_country_code` (`fk_c_country_code` ASC) ,
  CONSTRAINT `t_city_ibfk_1`
    FOREIGN KEY (`fk_i_region_id` )
    REFERENCES `osc_db`.`t_region` (`pk_i_id` ),
  CONSTRAINT `t_city_ibfk_2`
    FOREIGN KEY (`fk_c_country_code` )
    REFERENCES `osc_db`.`t_country` (`pk_c_code` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_city_area`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_city_area` (
  `pk_i_id` INT(10) UNSIGNED NOT NULL ,
  `fk_i_city_id` INT(10) UNSIGNED NOT NULL ,
  `s_name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`pk_i_id`) ,
  INDEX `fk_i_city_id` (`fk_i_city_id` ASC) ,
  CONSTRAINT `t_city_area_ibfk_1`
    FOREIGN KEY (`fk_i_city_id` )
    REFERENCES `osc_db`.`t_city` (`pk_i_id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_currency`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_currency` (
  `pk_c_code` CHAR(3) NOT NULL ,
  `s_name` VARCHAR(40) NOT NULL ,
  `s_description` VARCHAR(80) NULL DEFAULT NULL ,
  `b_enabled` TINYINT(1) NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`pk_c_code`) ,
  UNIQUE INDEX `s_name` (`s_name` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`user` (
  `pk_i_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `reg_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `mod_date` TIMESTAMP NULL ,
  `s_name` VARCHAR(100) NOT NULL ,
  `s_password` VARCHAR(40) NOT NULL ,
  `s_secret` VARCHAR(40) NULL DEFAULT NULL ,
  `s_email` VARCHAR(100) NULL DEFAULT NULL ,
  `s_website` VARCHAR(100) NULL DEFAULT NULL ,
  `s_phone_land` VARCHAR(45) NULL DEFAULT NULL ,
  `s_phone_mobile` VARCHAR(45) NULL DEFAULT NULL ,
  `b_enabled` TINYINT(1)  NOT NULL DEFAULT TRUE ,
  `b_active` TINYINT(1) NOT NULL DEFAULT '0' ,
  `s_pass_code` VARCHAR(100) NULL DEFAULT NULL ,
  `s_pass_date` DATETIME NULL DEFAULT NULL ,
  `s_pass_question` VARCHAR(100) NULL DEFAULT NULL ,
  `s_pass_answer` VARCHAR(100) NULL DEFAULT NULL ,
  `s_pass_ip` VARCHAR(15) NULL DEFAULT NULL ,
  `fk_c_country_code` CHAR(2) NULL DEFAULT NULL ,
  `s_country` VARCHAR(40) NULL DEFAULT NULL ,
  `s_address` VARCHAR(100) NULL DEFAULT NULL ,
  `s_zip` VARCHAR(15) NULL DEFAULT NULL ,
  `fk_i_region_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `s_region` VARCHAR(100) NULL DEFAULT NULL ,
  `fk_i_city_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `s_city` VARCHAR(100) NULL DEFAULT NULL ,
  `fk_i_city_area_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `s_city_area` VARCHAR(200) NULL DEFAULT NULL ,
  `d_coord_lat` DECIMAL(10,6) NULL DEFAULT NULL ,
  `d_coord_long` DECIMAL(10,6) NULL DEFAULT NULL ,
  `i_permissions` VARCHAR(2) NULL DEFAULT '0' ,
  `b_company` TINYINT(1) NOT NULL DEFAULT '0' ,
  `i_items` INT(10) UNSIGNED NULL DEFAULT '0' ,
  `i_comments` INT(10) UNSIGNED NULL DEFAULT '0' ,
  PRIMARY KEY (`pk_i_id`) ,
  UNIQUE INDEX `s_email` (`s_email` ASC) ,
  INDEX `fk_c_country_code` (`fk_c_country_code` ASC) ,
  INDEX `fk_i_region_id` (`fk_i_region_id` ASC) ,
  INDEX `fk_i_city_id` (`fk_i_city_id` ASC) ,
  INDEX `fk_i_city_area_id` (`fk_i_city_area_id` ASC) ,
  CONSTRAINT `t_user_ibfk_1`
    FOREIGN KEY (`fk_c_country_code` )
    REFERENCES `osc_db`.`t_country` (`pk_c_code` ),
  CONSTRAINT `t_user_ibfk_2`
    FOREIGN KEY (`fk_i_region_id` )
    REFERENCES `osc_db`.`t_region` (`pk_i_id` ),
  CONSTRAINT `t_user_ibfk_3`
    FOREIGN KEY (`fk_i_city_id` )
    REFERENCES `osc_db`.`t_city` (`pk_i_id` ),
  CONSTRAINT `t_user_ibfk_4`
    FOREIGN KEY (`fk_i_city_area_id` )
    REFERENCES `osc_db`.`t_city_area` (`pk_i_id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`item`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`item` (
  `pk_i_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `fk_i_user_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `fk_i_category_id` INT(10) UNSIGNED NOT NULL ,
  `pub_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `mod_date` TIMESTAMP NULL ,
  `f_price` FLOAT NULL DEFAULT NULL ,
  `i_price` BIGINT(20) NULL DEFAULT NULL ,
  `fk_c_currency_code` CHAR(3) NULL DEFAULT NULL ,
  `s_contact_name` VARCHAR(100) NULL DEFAULT NULL ,
  `s_contact_email` VARCHAR(140) NULL DEFAULT NULL ,
  `b_premium` TINYINT(1) NOT NULL DEFAULT '0' ,
  `b_enabled` TINYINT(1) NOT NULL DEFAULT '1' ,
  `b_active` TINYINT(1) NOT NULL DEFAULT '0' ,
  `b_spam` TINYINT(1) NOT NULL DEFAULT '0' ,
  `s_secret` VARCHAR(40) NULL DEFAULT NULL ,
  `b_show_email` TINYINT(1) NULL DEFAULT NULL ,
  `status` ENUM('ACTIVE','INACTIVE','MODERATION') NULL ,
  `status_detail` ENUM('ON_MODERATION','SPAM','STRONG_BAD_WORD','MEDIUM_BAD_WORD','OLD') NULL ,
  PRIMARY KEY (`pk_i_id`) ,
  INDEX `fk_i_user_id` (`fk_i_user_id` ASC) ,
  INDEX `fk_i_category_id` (`fk_i_category_id` ASC) ,
  INDEX `fk_c_currency_code` (`fk_c_currency_code` ASC) ,
  CONSTRAINT `t_item_ibfk_1`
    FOREIGN KEY (`fk_i_user_id` )
    REFERENCES `osc_db`.`user` (`pk_i_id` ),
  CONSTRAINT `t_item_ibfk_2`
    FOREIGN KEY (`fk_i_category_id` )
    REFERENCES `osc_db`.`t_category` (`pk_i_id` ),
  CONSTRAINT `t_item_ibfk_3`
    FOREIGN KEY (`fk_c_currency_code` )
    REFERENCES `osc_db`.`t_currency` (`pk_c_code` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_item_comment`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_item_comment` (
  `pk_i_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `fk_i_item_id` INT(10) UNSIGNED NOT NULL ,
  `dt_pub_date` DATETIME NOT NULL ,
  `s_title` VARCHAR(200) NOT NULL ,
  `s_author_name` VARCHAR(100) NOT NULL ,
  `s_author_email` VARCHAR(100) NOT NULL ,
  `s_body` TEXT NOT NULL ,
  `b_enabled` TINYINT(1) NOT NULL DEFAULT '1' ,
  `b_active` TINYINT(1) NOT NULL DEFAULT '0' ,
  `b_spam` TINYINT(1) NOT NULL DEFAULT '0' ,
  `fk_i_user_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`pk_i_id`) ,
  INDEX `fk_i_item_id` (`fk_i_item_id` ASC) ,
  INDEX `fk_i_user_id` (`fk_i_user_id` ASC) ,
  CONSTRAINT `t_item_comment_ibfk_1`
    FOREIGN KEY (`fk_i_item_id` )
    REFERENCES `osc_db`.`item` (`pk_i_id` ),
  CONSTRAINT `t_item_comment_ibfk_2`
    FOREIGN KEY (`fk_i_user_id` )
    REFERENCES `osc_db`.`user` (`pk_i_id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_item_description`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_item_description` (
  `fk_i_item_id` INT(10) UNSIGNED NOT NULL ,
  `fk_c_locale_code` CHAR(5) NOT NULL ,
  `s_title` VARCHAR(100) NOT NULL ,
  `s_description` MEDIUMTEXT NOT NULL ,
  `s_what` LONGTEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`fk_i_item_id`, `fk_c_locale_code`) ,
  INDEX `fk_i_item_id` (`fk_i_item_id` ASC) ,
  FULLTEXT INDEX `s_description` (`s_description` ASC, `s_title` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_item_location`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_item_location` (
  `fk_i_item_id` INT(10) UNSIGNED NOT NULL ,
  `fk_c_country_code` CHAR(2) NULL DEFAULT NULL ,
  `s_country` VARCHAR(40) NULL DEFAULT NULL ,
  `s_address` VARCHAR(100) NULL DEFAULT NULL ,
  `s_zip` VARCHAR(15) NULL DEFAULT NULL ,
  `fk_i_region_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `s_region` VARCHAR(100) NULL DEFAULT NULL ,
  `fk_i_city_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `s_city` VARCHAR(100) NULL DEFAULT NULL ,
  `fk_i_city_area_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `s_city_area` VARCHAR(200) NULL DEFAULT NULL ,
  `d_coord_lat` DECIMAL(10,6) NULL DEFAULT NULL ,
  `d_coord_long` DECIMAL(10,6) NULL DEFAULT NULL ,
  PRIMARY KEY (`fk_i_item_id`) ,
  INDEX `fk_c_country_code` (`fk_c_country_code` ASC) ,
  INDEX `fk_i_region_id` (`fk_i_region_id` ASC) ,
  INDEX `fk_i_city_id` (`fk_i_city_id` ASC) ,
  INDEX `fk_i_city_area_id` (`fk_i_city_area_id` ASC) ,
  CONSTRAINT `t_item_location_ibfk_1`
    FOREIGN KEY (`fk_i_item_id` )
    REFERENCES `osc_db`.`item` (`pk_i_id` ),
  CONSTRAINT `t_item_location_ibfk_2`
    FOREIGN KEY (`fk_c_country_code` )
    REFERENCES `osc_db`.`t_country` (`pk_c_code` ),
  CONSTRAINT `t_item_location_ibfk_3`
    FOREIGN KEY (`fk_i_region_id` )
    REFERENCES `osc_db`.`t_region` (`pk_i_id` ),
  CONSTRAINT `t_item_location_ibfk_4`
    FOREIGN KEY (`fk_i_city_id` )
    REFERENCES `osc_db`.`t_city` (`pk_i_id` ),
  CONSTRAINT `t_item_location_ibfk_5`
    FOREIGN KEY (`fk_i_city_area_id` )
    REFERENCES `osc_db`.`t_city_area` (`pk_i_id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_meta_fields`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_meta_fields` (
  `pk_i_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `s_name` VARCHAR(255) NOT NULL ,
  `s_slug` VARCHAR(255) NOT NULL ,
  `e_type` ENUM('TEXT','TEXTAREA','DROPDOWN','RADIO') NOT NULL DEFAULT 'TEXT' ,
  `s_options` VARCHAR(255) NULL DEFAULT NULL ,
  `b_required` TINYINT(1) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`pk_i_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_item_meta`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_item_meta` (
  `fk_i_item_id` INT(10) UNSIGNED NOT NULL ,
  `fk_i_field_id` INT(10) UNSIGNED NOT NULL ,
  `s_value` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`fk_i_item_id`, `fk_i_field_id`) ,
  INDEX `fk_i_field_id` (`fk_i_field_id` ASC) ,
  CONSTRAINT `t_item_meta_ibfk_1`
    FOREIGN KEY (`fk_i_item_id` )
    REFERENCES `osc_db`.`item` (`pk_i_id` ),
  CONSTRAINT `t_item_meta_ibfk_2`
    FOREIGN KEY (`fk_i_field_id` )
    REFERENCES `osc_db`.`t_meta_fields` (`pk_i_id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_item_resource`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_item_resource` (
  `pk_i_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `fk_i_item_id` INT(10) UNSIGNED NOT NULL ,
  `s_name` VARCHAR(60) NULL DEFAULT NULL ,
  `s_extension` VARCHAR(10) NULL DEFAULT NULL ,
  `s_content_type` VARCHAR(40) NULL DEFAULT NULL ,
  `s_path` VARCHAR(250) NULL DEFAULT NULL ,
  PRIMARY KEY (`pk_i_id`) ,
  INDEX `fk_i_item_id` (`fk_i_item_id` ASC) ,
  CONSTRAINT `t_item_resource_ibfk_1`
    FOREIGN KEY (`fk_i_item_id` )
    REFERENCES `osc_db`.`item` (`pk_i_id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_item_stats`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_item_stats` (
  `fk_i_item_id` INT(10) UNSIGNED NOT NULL ,
  `i_num_views` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `i_num_spam` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `i_num_repeated` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `i_num_bad_classified` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `i_num_offensive` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `i_num_expired` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `i_num_premium_views` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `dt_date` DATE NOT NULL ,
  PRIMARY KEY (`fk_i_item_id`, `dt_date`) ,
  INDEX `dt_date` (`dt_date` ASC, `fk_i_item_id` ASC) ,
  CONSTRAINT `t_item_stats_ibfk_1`
    FOREIGN KEY (`fk_i_item_id` )
    REFERENCES `osc_db`.`item` (`pk_i_id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_keywords`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_keywords` (
  `s_md5` VARCHAR(32) NOT NULL ,
  `fk_c_locale_code` CHAR(5) NOT NULL ,
  `s_original_text` VARCHAR(255) NOT NULL ,
  `s_anchor_text` VARCHAR(255) NOT NULL ,
  `s_normalized_text` VARCHAR(255) NOT NULL ,
  `fk_i_category_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `fk_i_city_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`s_md5`, `fk_c_locale_code`) ,
  INDEX `fk_i_category_id` (`fk_i_category_id` ASC) ,
  INDEX `fk_i_city_id` (`fk_i_city_id` ASC) ,
  INDEX `fk_c_locale_code` (`fk_c_locale_code` ASC) ,
  CONSTRAINT `t_keywords_ibfk_1`
    FOREIGN KEY (`fk_i_category_id` )
    REFERENCES `osc_db`.`t_category` (`pk_i_id` ),
  CONSTRAINT `t_keywords_ibfk_2`
    FOREIGN KEY (`fk_i_city_id` )
    REFERENCES `osc_db`.`t_city` (`pk_i_id` ),
  CONSTRAINT `t_keywords_ibfk_3`
    FOREIGN KEY (`fk_c_locale_code` )
    REFERENCES `osc_db`.`t_locale` (`pk_c_code` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`latest_searches`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`latest_searches` (
  `query` VARCHAR(255) NOT NULL ,
  `search_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_log`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_log` (
  `dt_date` DATETIME NOT NULL ,
  `s_section` VARCHAR(50) NOT NULL ,
  `s_action` VARCHAR(50) NOT NULL ,
  `fk_i_id` INT(10) UNSIGNED NOT NULL ,
  `s_data` VARCHAR(250) NOT NULL ,
  `s_ip` VARCHAR(50) NOT NULL ,
  `s_who` VARCHAR(50) NOT NULL ,
  `fk_i_who_id` INT(10) UNSIGNED NOT NULL )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_meta_categories`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_meta_categories` (
  `fk_i_category_id` INT(10) UNSIGNED NOT NULL ,
  `fk_i_field_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`fk_i_category_id`, `fk_i_field_id`) ,
  INDEX `fk_i_field_id` (`fk_i_field_id` ASC) ,
  CONSTRAINT `t_meta_categories_ibfk_1`
    FOREIGN KEY (`fk_i_category_id` )
    REFERENCES `osc_db`.`t_category` (`pk_i_id` ),
  CONSTRAINT `t_meta_categories_ibfk_2`
    FOREIGN KEY (`fk_i_field_id` )
    REFERENCES `osc_db`.`t_meta_fields` (`pk_i_id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_pages`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_pages` (
  `pk_i_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `s_internal_name` VARCHAR(50) NULL DEFAULT NULL ,
  `b_indelible` TINYINT(1) NOT NULL DEFAULT '0' ,
  `dt_pub_date` DATETIME NOT NULL ,
  `dt_mod_date` DATETIME NULL DEFAULT NULL ,
  `i_order` INT(3) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`pk_i_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_pages_description`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_pages_description` (
  `fk_i_pages_id` INT(10) UNSIGNED NOT NULL ,
  `fk_c_locale_code` CHAR(5) NOT NULL ,
  `s_title` VARCHAR(255) NOT NULL ,
  `s_text` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`fk_i_pages_id`, `fk_c_locale_code`) ,
  INDEX `fk_c_locale_code` (`fk_c_locale_code` ASC) ,
  CONSTRAINT `t_pages_description_ibfk_1`
    FOREIGN KEY (`fk_i_pages_id` )
    REFERENCES `osc_db`.`t_pages` (`pk_i_id` ),
  CONSTRAINT `t_pages_description_ibfk_2`
    FOREIGN KEY (`fk_c_locale_code` )
    REFERENCES `osc_db`.`t_locale` (`pk_c_code` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_plugin_category`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_plugin_category` (
  `s_plugin_name` VARCHAR(40) NOT NULL ,
  `fk_i_category_id` INT(10) UNSIGNED NOT NULL ,
  INDEX `fk_i_category_id` (`fk_i_category_id` ASC) ,
  CONSTRAINT `t_plugin_category_ibfk_1`
    FOREIGN KEY (`fk_i_category_id` )
    REFERENCES `osc_db`.`t_category` (`pk_i_id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_preference`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_preference` (
  `s_section` VARCHAR(40) NOT NULL ,
  `s_name` VARCHAR(40) NOT NULL ,
  `s_value` LONGTEXT NOT NULL ,
  `e_type` ENUM('STRING','INTEGER','BOOLEAN') NOT NULL ,
  UNIQUE INDEX `s_section` (`s_section` ASC, `s_name` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_user_description`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_user_description` (
  `fk_i_user_id` INT(10) UNSIGNED NOT NULL ,
  `fk_c_locale_code` CHAR(5) NOT NULL ,
  `s_info` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`fk_i_user_id`, `fk_c_locale_code`) ,
  INDEX `fk_c_locale_code` (`fk_c_locale_code` ASC) ,
  CONSTRAINT `t_user_description_ibfk_1`
    FOREIGN KEY (`fk_i_user_id` )
    REFERENCES `osc_db`.`user` (`pk_i_id` ),
  CONSTRAINT `t_user_description_ibfk_2`
    FOREIGN KEY (`fk_c_locale_code` )
    REFERENCES `osc_db`.`t_locale` (`pk_c_code` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_user_email_tmp`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_user_email_tmp` (
  `fk_i_user_id` INT(10) UNSIGNED NOT NULL ,
  `s_new_email` VARCHAR(100) NOT NULL ,
  `dt_date` DATETIME NOT NULL ,
  PRIMARY KEY (`fk_i_user_id`) ,
  CONSTRAINT `t_user_email_tmp_ibfk_1`
    FOREIGN KEY (`fk_i_user_id` )
    REFERENCES `osc_db`.`user` (`pk_i_id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `osc_db`.`t_widget`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `osc_db`.`t_widget` (
  `pk_i_id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `s_description` VARCHAR(40) NOT NULL ,
  `s_location` VARCHAR(40) NOT NULL ,
  `e_kind` ENUM('TEXT','HTML') NOT NULL ,
  `s_content` MEDIUMTEXT NOT NULL ,
  PRIMARY KEY (`pk_i_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
