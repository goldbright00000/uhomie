CREATE  PROCEDURE sp_identity_user_score(
IN Uuser_id int,
IN Sdni_o int,
IN Sdni_r int,
IN f1 varchar(1000),
IN f2 varchar(1000),
OUT points INT,
INOUT f varchar(10000)
)
BEGIN
    DECLARE Dfront varchar(60) DEFAULT '';
    DECLARE Dback varchar(60) DEFAULT '';

    SET Dfront = (SELECT name  FROM files   WHERE   user_id = Uuser_id and  verified and name = 'id_front');
    SET Dback = (SELECT name   FROM files   WHERE   user_id = Uuser_id and  verified and name = 'id_back');
    SET points = 0;

    IF Dfront IS NULL  THEN
        SET f = CONCAT(f,'/',f1);
    else
        set points =  points + Sdni_o;
    END IF;

    if Dback IS NULL then
        SET f = CONCAT(f,'/',f2);
    else
        set points =  points + Sdni_r;
    end if;
END
