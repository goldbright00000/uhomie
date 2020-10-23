CREATE
VIEW `v_properties_descriptor_days` AS
    (SELECT
        DAYOFWEEK(`properties`.`created_at`) AS `day`,
        COUNT(`properties`.`id`) AS `quantity`,
        `properties`.`created_at` AS `date`
    FROM
        `properties`
    GROUP BY `properties`.`created_at`
    ORDER BY `day`)
