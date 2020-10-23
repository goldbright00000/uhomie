CREATE
VIEW `v_leased_properties` AS
    (SELECT
        DAYOFWEEK(`properties`.`created_at`) AS `day`,
        COUNT(`properties`.`id`) AS `quantity`,
        `properties`.`created_at` AS `date`
    FROM
        `properties`
    GROUP BY `properties`.`created_at`
    ORDER BY `day`)
