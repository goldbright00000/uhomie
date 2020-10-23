
CREATE  FUNCTION `sf_job_user_score`(

            employment_type int,
            employe_point int,

            free_points int,
            unemploye_point int

        ) RETURNS int(11)
BEGIN

    IF(employment_type IS NULL) then
        RETURN unemploye_point;
    else
        CASE employment_type
            WHEN 1 THEN
                RETURN employe_point;
            WHEN 2 THEN
                    RETURN free_points;
            WHEN 3 THEN
                    RETURN unemploye_point;
            ELSE RETURN 0;
        END CASE;
    end if;
END
