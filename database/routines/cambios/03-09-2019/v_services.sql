CREATE

VIEW `v_services` AS SELECT DISTINCT
        `cs`.`id` AS `id`,
        `c`.`id` AS `company_id`,
        `c`.`user_id` AS `user_id`,
        `c`.`description` AS `description`,
        `c`.`name` AS `name`,
        `c`.`email` AS `email`,
        `c`.`phone` AS `phone`,
        `c`.`cell_phone` AS `cell_phone`,
        `u`.`city_id` AS `city_id`,
        `u`.`address` AS `address`,
        `m`.`name` AS `membership_name`,
        `m`.`id` AS `membership_id`,
        `p`.`path` AS `path`,
        `s`.`name` AS `service`,
        `s`.`id` AS `service_id`,
        `t`.`id` AS `service_type_id`,
        `t`.`name` AS `service_type_name`,
        `cs`.`description` AS `service_description`,
        `u`.`firstname` AS `user_firstname`,
        `u`.`lastname` AS `user_lastname`
        
    FROM
        (((((((((`companies` `c`
        JOIN `users` `u`)
        JOIN `roles` `r`)
        JOIN `memberships` `m`)
        JOIN `users_has_memberships` `h`)
        JOIN `photos` `p`)
        JOIN `companies_has_services_list` `cs`)
        JOIN `services_list` `s`)
        JOIN `services_type` `t`))

    WHERE
        ((`u`.`id` = `c`.`user_id`)
            AND (`u`.`id` = `h`.`user_id`)
            AND (`h`.`membership_id` = `m`.`id`)
            AND (`m`.`role_id` = `r`.`id`)
            AND (`r`.`id` = 4)
            AND (`c`.`id` = `p`.`company_id`)
            AND (`p`.`logo` = 1)
            AND (`cs`.`company_id` = `c`.`id`)
        	AND (`s`.`id` = `cs`.`service_list_id`)
        	AND (`s`.`service_type_id` = `t`.`id`))
    ORDER BY `s`.`name` ASC
