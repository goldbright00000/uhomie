
CREATE  PROCEDURE `sp_job_user_score`(
    IN employment_type int,
    IN employe_point int,
    IN free_points int,
    IN unemploye_point int,
    IN fi varchar(1000),
    IN f2 varchar(1000),
    IN f3  varchar(1000),
    INOUT f varchar(10000),
    OUT points int
    )
BEGIN

    IF(employment_type IS NULL) then
        SET points =0;
        SET f = CONCAT(f,'/',f3);
    else
        CASE employment_type
            WHEN 1 THEN
                SET points = employe_point;
            WHEN 2 THEN
                SET points = free_points;
            WHEN 3 THEN
                SET points = unemploye_point;
            ELSE
                SET points = 0;
                SET f = CONCAT(f,'/',f3);
        END CASE;
    end if;
END
