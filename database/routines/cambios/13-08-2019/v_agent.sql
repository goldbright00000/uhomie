CREATE
VIEW `v_agent` AS
    SELECT DISTINCT
        `p`.`id` AS `project_id`,
        `p`.`name` AS `name`,
        `p`.`description` AS `description`,
        `p`.`rent` AS `rent`,
        `p`.`condition` AS `condition`,
        `p`.`verified` AS `verified`,
        `p`.`city_id` AS `city_id`,
        `p`.`property_type_id` AS `property_type_id`,
        `c`.`user_id` AS `agent_id`,
        `u`.`phone` AS `phone`,
        `u`.`email` AS `email`,
        `m`.`name` AS `membership_name`,
        `m`.`id` AS `membership_id`,
        `f`.`path` AS `path`
    FROM
        ((((((`properties` `p`
        JOIN `companies` `c`)
        JOIN `users` `u`)
        JOIN `users_has_memberships` `h`)
        JOIN `memberships` `m`)
        JOIN `roles` `r`)
        JOIN `photos` `f`)
    WHERE
        (`p`.`is_project` AND `p`.`active`
            AND `p`.`available`
            AND (`c`.`id` = `p`.`company_id`)
            AND (`u`.`id` = `c`.`user_id`)
            AND (`u`.`id` = `h`.`user_id`)
            AND (`m`.`id` = `h`.`membership_id`)
            AND (`r`.`id` = `m`.`role_id`)
            AND (`r`.`id` = 3)
            AND (`f`.`property_id` = `p`.`id`)
            AND (`f`.`cover` = 1)
            AND (`p`.`deleted_at` IS NULL)
            )
