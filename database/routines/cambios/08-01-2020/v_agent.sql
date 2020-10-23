CREATE OR REPLACE

VIEW `v_agent` AS
    SELECT DISTINCT
        `c`.`id` AS `company_id`,
        `c`.`name` AS `name`,
        `c`.`description` AS `description`,
        `c`.`website` AS `website`,
        `c`.`city_id` AS `city_id`,
        `c`.`user_id` AS `agent_id`,
        `c`.`latitude` AS `latitude`,
        `c`.`longitude` AS `longitude`,
        `u`.`phone` AS `phone`,
        `u`.`email` AS `email`,
        `m`.`name` AS `membership_name`,
        `m`.`id` AS `membership_id`
    FROM
        `companies` `c`
        INNER JOIN `users` `u` ON `c`.`user_id` = `u`.`id`
        INNER JOIN `users_has_memberships` `h` ON `u`.`id` = `h`.`user_id`
        INNER JOIN `memberships` `m` ON `m`.`id` = `h`.`membership_id`
        INNER JOIN `roles` `r` ON `r`.`id` = `m`.`role_id`
    WHERE
        `c`.`type` = 0
        AND `r`.`id` = 3;