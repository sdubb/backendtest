ALTER TABLE `event_ticket_booking` ADD `user_first_name` VARCHAR(256) NULL DEFAULT NULL AFTER `user_id`, ADD `user_last_name` VARCHAR(256) NULL DEFAULT NULL AFTER `user_first_name`;
ALTER TABLE `setting` ADD `sightengine_is_content_moderation` INT NOT NULL DEFAULT '0' AFTER `music_url`, ADD `sightengine_api_user` VARCHAR(256) NULL DEFAULT NULL AFTER `sightengine_is_content_moderation`, ADD `sightengine_api_secret` VARCHAR(256) NULL DEFAULT NULL AFTER `sightengine_is_content_moderation`;
ALTER TABLE `event_ticket_booking` ADD `user_email` VARCHAR(100) NULL DEFAULT NULL AFTER `user_last_name`, ADD `user_phone` VARCHAR(100) NULL DEFAULT NULL AFTER `user_email`, ADD `booking_user_type` INT NOT NULL DEFAULT '1' COMMENT '1=registered user,2=non-registered' AFTER `user_phone`;
ALTER TABLE `event_ticket_booking` ADD `user_country_code` VARCHAR(100) NULL DEFAULT NULL AFTER `user_email`;
ALTER TABLE `module_auth` ADD `level` INT NOT NULL DEFAULT '1' AFTER `alias`, ADD `parent_id` INT NULL DEFAULT NULL AFTER `level`;
ALTER TABLE `module_auth` ADD `action_list` TEXT NULL DEFAULT NULL AFTER `parent_id`;
INSERT INTO `module_auth` (`id`, `name`, `alias`, `level`, `parent_id`, `action_list`) VALUES
(264, 'View', 'view', 2, 14, 'event/index,event/view,event-ticket/index,event-ticket-booking/index,event-ticket-booking/view'),
(265, 'Update', 'update', 2, 14, 'event/update,ticket/update,event-ticket/update,event-ticket-booking/update'),
(266, 'Delete', 'delete', 2, 14, 'event/delete,event-coupon/delete,event-ticket/delete'),
(267, 'Create', 'create', 2, 14, 'event/create,ticket/create,event-coupon/create');
ALTER TABLE `setting` ADD `sms_gateway` INT NULL DEFAULT NULL COMMENT '1= twilio,2 = sms91, 3 firebase' AFTER `sightengine_api_secret`, ADD `twilio_sid` VARCHAR(256) NULL DEFAULT NULL AFTER `sms_gateway`, ADD `twilio_token` VARCHAR(256) NULL DEFAULT NULL AFTER `twilio_sid`, ADD `twilio_number` VARCHAR(256) NULL DEFAULT NULL AFTER `twilio_token`, ADD `msg91_authKey` VARCHAR(256) NULL DEFAULT NULL AFTER `twilio_number`;
ALTER TABLE `setting` ADD `msg91_sender_id` VARCHAR(100) NULL DEFAULT NULL AFTER `msg91_authKey`;
ALTER TABLE `event` ADD `unique_id` VARCHAR(200) NULL DEFAULT NULL AFTER `id`;
ALTER TABLE `setting` ADD `storage_system` INT NOT NULL DEFAULT '1' COMMENT 'server Storage =1, AWS S3=2, Azure storage=3' AFTER `msg91_sender_id`, ADD `aws_access_key_id` VARCHAR(200) NULL DEFAULT NULL AFTER `storage_system`, ADD `aws_secret_key` VARCHAR(200) NULL DEFAULT NULL AFTER `aws_access_key_id`, ADD `aws_region` VARCHAR(100) NULL DEFAULT NULL AFTER `aws_secret_key`, ADD `aws_bucket` VARCHAR(200) NULL DEFAULT NULL AFTER `aws_region`, ADD `aws_access_url` VARCHAR(256) NULL DEFAULT NULL AFTER `aws_bucket`, ADD `azure_account_name` VARCHAR(200) NULL DEFAULT NULL AFTER `aws_access_url`, ADD `azure_account_key` VARCHAR(200) NULL DEFAULT NULL AFTER `azure_account_name`, ADD `azure_container` VARCHAR(200) NULL DEFAULT NULL AFTER `azure_account_key`;
ALTER TABLE `setting` CHANGE `sightengine_is_content_moderation` `content_moderation_gateway` INT NOT NULL DEFAULT '0' COMMENT 'sightengine.com=1, AMAZON REKOGNITION=2';
ALTER TABLE `post` ADD `display_whose` INT NOT NULL DEFAULT '1' COMMENT 'all=1, 2 only subscribe user' AFTER `poll_id`;
ALTER TABLE `setting` ADD `subscribe_active_condition_follower` INT NOT NULL DEFAULT '0' AFTER `azure_container`, ADD `subscribe_active_condition_post` INT NOT NULL DEFAULT '0' AFTER `subscribe_active_condition_follower`;
DROP TABLE IF EXISTS `subscription_plan`;
CREATE TABLE IF NOT EXISTS `subscription_plan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `expiry_day` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `subscription_plan` (`id`, `name`, `expiry_day`) VALUES
(2, 'Monthly', 30);

DROP TABLE IF EXISTS `subscription_plan_subscriber`;
CREATE TABLE IF NOT EXISTS `subscription_plan_subscriber` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` int NOT NULL DEFAULT '1' COMMENT '1= subscription plan, 2=post subscription',
  `post_id` int DEFAULT NULL,
  `subscription_plan_user_id` int DEFAULT NULL,
  `subscribe_to_user_id` int NOT NULL,
  `subcriber_id` int NOT NULL,
  `subscription_plan_value` float NOT NULL,
  `expiry_time` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `subscription_plan_user`;
CREATE TABLE IF NOT EXISTS `subscription_plan_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subscription_plan_id` int NOT NULL,
  `user_id` int NOT NULL,
  `type` int NOT NULL DEFAULT '1' COMMENT '1=coin, amount=2',
  `value` float NOT NULL,
  `created_at` int NOT NULL,
  `updated_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `setting` CHANGE `sms_gateway` `sms_gateway` INT NULL DEFAULT '3' COMMENT '1= twilio,2 = sms91, 3 firebase';










