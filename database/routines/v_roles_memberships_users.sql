CREATE 
VIEW `v_roles_memberships_users` AS
    (SELECT
        `memberships`.`id` AS `id`,
        `memberships`.`role_id` AS `role_id`,
        `memberships`.`name` AS `name`,
        COUNT(`users_has_memberships`.`membership_id`) AS `u_count`,
        `memberships`.`enabled` AS `enabled`,
        `roles`.`name` AS `role_name`
    FROM
        ((`memberships`
        LEFT JOIN `users_has_memberships` ON ((`memberships`.`id` = `users_has_memberships`.`membership_id`)))
        JOIN `roles` ON ((`roles`.`id` = `memberships`.`role_id`)))
    GROUP BY `memberships`.`id`)
