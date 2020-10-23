CREATE
VIEW `v_users_count_role` AS
    (SELECT
        `r`.`id` AS `role_id`, COUNT(`uhm`.`id`) AS `u_count`
    FROM
        ((`memberships` `m`
        LEFT JOIN `users_has_memberships` `uhm` ON ((`m`.`id` = `uhm`.`membership_id`)))
        JOIN `roles` `r` ON ((`r`.`id` = `m`.`role_id`)))
    GROUP BY `r`.`id`)
