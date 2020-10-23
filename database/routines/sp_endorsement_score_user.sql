
CREATE PROCEDURE `sp_endorsement_score_user`(
            IN confirmed bool,
            IN confirmed_points int,
            IN unconfirmed_points int,
            IN Uinsurance boolean,
            IN Sinsurance int,
            IN f1 varchar(1000),
            IN f2 varchar(1000),
            INOUT f varchar(10000),
            OUT points int
        )
BEGIN
    IF confirmed THEN
        SET points = confirmed_points;
    ELSE
        SET points = unconfirmed_points;
        SET f = CONCAT(f,'/',f1);
    END IF;
    IF Uinsurance THEN
        SET points = points + Sinsurance;
    ELSE
        SET f = CONCAT(f,'/',f2);
    END IF;


END
