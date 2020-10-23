CREATE
VIEW `v_scoring_category_points` AS
    (SELECT
        `c`.`id` AS `id`,
        `c`.`name` AS `name`,
        `c`.`feed_back` AS `feed_back`,
        `c`.`description` AS `description`,
        `c`.`scoring_id` AS `scoring_id`,
        SUM(`d`.`points`) AS `points_scoring`
    FROM
        (`cat_scoring` `c`
        LEFT JOIN `det_scoring` `d` ON ((`d`.`cat_scoring_id` = `c`.`id`)))
    GROUP BY `c`.`id`)
