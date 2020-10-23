CREATE

VIEW `v_properties` AS
    SELECT DISTINCT
        `a`.`id` AS `property_id`,
        `a`.`name` AS `name`,
        `a`.`description` AS `description`,
        `a`.`meters` AS `meters`,
        `a`.`bedrooms` AS `bedrooms`,
        `a`.`bathrooms` AS `bathrooms`,
        `a`.`rent` AS `rent`,
        `a`.`verified` AS `verified`,
        `a`.`available` AS `available`,
        `a`.`available_date` AS `available_date`,
        `a`.`pet_preference` AS `pet_preference`,
        `a`.`private_parking` AS `private_parking`,
        `a`.`public_parking` AS `public_parking`,
        `a`.`furnished` AS `furnished`,
        `a`.`city_id` AS `city_id`,
        `a`.`commune_id` AS `commune_id`,
        `a`.`property_type_id` AS `property_type_id`,
        `d`.`path` AS `path`,
        `b`.`name` AS `property_type_name`,
        `c`.`user_id` AS `owner_id`,
        `f`.`name` AS `membership_name`,
        `f`.`id` AS `membership_id`,
        `o`.`property_for_id` AS `property_for_id`,
        SF_DEMAND(`a`.`id`) AS `demand`
    FROM
        (((((((`properties` `a`
        JOIN `properties_types` `b`)
        JOIN `users_has_properties` `c`)
        JOIN `photos` `d`)
        JOIN `users_has_memberships` `e`)
        JOIN `memberships` `f`)
        JOIN `roles` `g`)
        JOIN `properties_has_properties_for` `o`)
    WHERE
        (`a`.`active`
            AND (NOT (`a`.`is_project`))
            AND (`b`.`id` = `a`.`property_type_id`)
            AND (`a`.`id` = `c`.`property_id`)
            AND (`c`.`type` = 1)
            AND (`a`.`id` = `d`.`property_id`)
            AND (`d`.`cover` = 1)
            AND (`e`.`user_id` = `c`.`user_id`)
            AND (`e`.`membership_id` = `f`.`id`)
            AND (`f`.`role_id` = 2)
            AND `f`.`enabled`
            AND (`o`.`property_id` = `a`.`id`)
            AND (`a`.`deleted_at` IS NULL)
            AND (`a`.`expire_at` <= CURDATE() OR `a`.`expire_at` IS NULL))
