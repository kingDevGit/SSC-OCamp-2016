<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1468776639.
 * Generated on 2016-07-17 17:30:39 by Kenny
 */
class PropelMigration_1468776639
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postDown(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'chrono' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `chrono_timer`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `player_id` INTEGER NOT NULL,
    `pause` TINYINT(1) DEFAULT 0 NOT NULL,
    `pause_at` DATETIME,
    `end_at` DATETIME,
    `class_key` VARCHAR(20),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `chrono_timer_i_d6d3d5` (`player_id`),
    INDEX `chrono_timer_i_3a2c33` (`pause`),
    INDEX `chrono_timer_i_acd565` (`end_at`),
    CONSTRAINT `chrono_timer_fk_46d202`
        FOREIGN KEY (`player_id`)
        REFERENCES `chrono_player` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `chrono_transaction`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `player_a` INTEGER NOT NULL,
    `player_b` INTEGER NOT NULL,
    `second` INTEGER NOT NULL,
    `executed` TINYINT(1) DEFAULT 0 NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `fi_ono_transaction_player_a` (`player_a`),
    INDEX `fi_ono_transaction_player_b` (`player_b`),
    CONSTRAINT `chrono_transaction_player_a`
        FOREIGN KEY (`player_a`)
        REFERENCES `chrono_player` (`id`),
    CONSTRAINT `chrono_transaction_player_b`
        FOREIGN KEY (`player_b`)
        REFERENCES `chrono_player` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `chrono_notification`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `to_player` INTEGER NOT NULL,
    `message` VARCHAR(255) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `chrono_notification_i_32c529` (`to_player`),
    INDEX `chrono_notification_i_d404ac` (`created_at`),
    CONSTRAINT `chrono_notification_fk_cbd91c`
        FOREIGN KEY (`to_player`)
        REFERENCES `chrono_player` (`id`)
) ENGINE=InnoDB CHARACTER SET=\'utf8mb4\' COLLATE=\'utf8mb4_unicode_ci\';

CREATE TABLE `chrono_account`
(
    `player_id` INTEGER NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `hash` VARCHAR(255) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`player_id`),
    INDEX `chrono_account_i_ce4c89` (`email`),
    CONSTRAINT `chrono_account_fk_46d202`
        FOREIGN KEY (`player_id`)
        REFERENCES `chrono_player` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `chrono_session`
(
    `token` VARCHAR(100) NOT NULL,
    `player_id` INTEGER NOT NULL,
    `expired_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`token`),
    INDEX `chrono_session_fi_46d202` (`player_id`),
    CONSTRAINT `chrono_session_fk_46d202`
        FOREIGN KEY (`player_id`)
        REFERENCES `chrono_player` (`id`)
) ENGINE=InnoDB;

CREATE TABLE `chrono_player`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `nickname` VARCHAR(255) NOT NULL,
    `gender` TINYINT NOT NULL,
    `union_id` INTEGER,
    `tags` TEXT,
    `address` VARCHAR(10) NOT NULL,
    `die_count` INTEGER DEFAULT 0 NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `chrono_player_fi_fae516` (`union_id`),
    CONSTRAINT `chrono_player_fk_fae516`
        FOREIGN KEY (`union_id`)
        REFERENCES `chrono_union` (`id`)
) ENGINE=InnoDB CHARACTER SET=\'utf8mb4\' COLLATE=\'utf8mb4_unicode_ci\';

CREATE TABLE `chrono_union`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `color` VARCHAR(7) DEFAULT \'#888888\' NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET=\'utf8mb4\' COLLATE=\'utf8mb4_unicode_ci\';

CREATE TABLE `chrono_setting`
(
    `name` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    PRIMARY KEY (`name`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'chrono' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `chrono_timer`;

DROP TABLE IF EXISTS `chrono_transaction`;

DROP TABLE IF EXISTS `chrono_notification`;

DROP TABLE IF EXISTS `chrono_account`;

DROP TABLE IF EXISTS `chrono_session`;

DROP TABLE IF EXISTS `chrono_player`;

DROP TABLE IF EXISTS `chrono_union`;

DROP TABLE IF EXISTS `chrono_setting`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}