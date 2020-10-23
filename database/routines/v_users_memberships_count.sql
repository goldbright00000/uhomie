CREATE
VIEW `v_users_membership_count` AS
    (SELECT
        `memberships`.`id` AS `id`,
        `memberships`.`name` AS `name`,
        COUNT(`users_has_memberships`.`membership_id`) AS `u_count`,
        `memberships`.`role_id` AS `role_id`
    FROM
        (`memberships`
        LEFT JOIN `users_has_memberships` ON ((`users_has_memberships`.`membership_id` = `memberships`.`id`)))
    GROUP BY `memberships`.`id`)
