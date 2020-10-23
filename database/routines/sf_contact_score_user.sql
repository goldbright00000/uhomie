
CREATE  FUNCTION `sf_contact_user_score`(

            has_phone bool,
            has_mail bool,
            score_phone int,
            score_mail int

            ) RETURNS int(11)
BEGIN
            DECLARE total int ;
            SET total = 0;

            IF has_phone THEN
               SET total = total + score_phone;
            END IF;

            IF has_mail THEN
               SET total = total + score_mail;
            END IF;

            RETURN total;
END
