CREATE

VIEW `v_properties_wu` AS
    SELECT
        `p`.`property_id` AS `property_id`,
        `p`.`name` AS `name`,
        `p`.`description` AS `description`,
        `p`.`meters` AS `meters`,
        `p`.`bedrooms` AS `bedrooms`,
        `p`.`bathrooms` AS `bathrooms`,
        `p`.`rent` AS `rent`,
        `p`.`verified` AS `verified`,
        `p`.`available_date` AS `available_date`,
        `p`.`pet_preference` AS `pet_preference`,
        `p`.`private_parking` AS `private_parking`,
        `p`.`public_parking` AS `public_parking`,
        `p`.`furnished` AS `furnished`,
        `p`.`city_id` AS `city_id`,
        `p`.`commune_id` AS `commune_id`,
        `p`.`property_type_id` AS `property_type_id`,
        `p`.`path` AS `path`,
        `p`.`property_type_name` AS `property_type_name`,
        `p`.`owner_id` AS `owner_id`,
        `p`.`membership_name` AS `membership_name`,
        `p`.`membership_id` AS `membership_id`,
        `p`.`demand` AS `demand`,
        `p`.`property_for_id` AS `property_for_id`,
        `u`.`id` AS `user_id`,
        SF_SCORE(`u`.`id`, `p`.`property_id`) AS `score`,
        SF_FAVORITE(`u`.`id`, `p`.`property_id`) AS `favorite`,
        SF_APPLIED(`u`.`id`, `p`.`property_id`) AS `applied`
    FROM
        (`users` `u`
        JOIN `v_properties` `p`)
