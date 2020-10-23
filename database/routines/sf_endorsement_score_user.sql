
CREATE FUNCTION `sf_endorsement_score_user`(
    confirmed bool,
    confirmed_points int,
    unconfirmed_points int,
    Uinsurance bool,
    Sinsurance int
    ) RETURNS int(11)
BEGIN
    DECLARE points int(11) DEFAULT 0;
    IF confirmed THEN
        SET  points = confirmed_points;
    ELSE
        SET  points = unconfirmed_points;
    END IF;

    IF Uinsurance THEN
        SET  points = points + Sinsurance;
    END IF;
    RETURN points;

END
