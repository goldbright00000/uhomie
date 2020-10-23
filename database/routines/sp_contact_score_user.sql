CREATE  PROCEDURE `sp_contact_score_user`(

            IN has_phone bool,
            IN has_mail bool,
            IN score_phone int,
            IN score_mail int,
            IN f1 varchar(1000),
            IN f2 varchar(1000),
            OUT total int,
            INOUT f varchar(10000)
            )
BEGIN

    SET total = 0;
    IF has_phone THEN
       SET total = total + score_phone;
    ELSE
        SET f = CONCAT(f,'/',f1);
    END IF;

    IF has_mail THEN
       SET total = total + score_mail;
    ELSE
        SET f = CONCAT(f,'/',f2);
    END IF;

END
