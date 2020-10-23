CREATE
VIEW `v_scoring_points` AS
    (SELECT
        `s`.`id` AS `id`,
        `s`.`name` AS `name`,
        `s`.`description` AS `description`,
        SUM(`d`.`points`) AS `points_scoring`
    FROM
        ((`scoring` `s`
        LEFT JOIN `cat_scoring` `c` ON ((`c`.`scoring_id` = `s`.`id`)))
        LEFT JOIN `det_scoring` `d` ON ((`d`.`cat_scoring_id` = `c`.`id`)))
    GROUP BY `s`.`id` , `s`.`name`)
