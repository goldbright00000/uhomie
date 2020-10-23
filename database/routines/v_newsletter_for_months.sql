CREATE
VIEW `v_newsletter_for_months` AS
    (SELECT
        MONTH(`newsletters`.`created_at`) AS `month_nl`,
        COUNT(`newsletters`.`id`) AS `quantity_nl`,
        `newsletters`.`created_at` AS `date_nl`
    FROM
        `newsletters`
    GROUP BY `date_nl`
    ORDER BY `month_nl`)
