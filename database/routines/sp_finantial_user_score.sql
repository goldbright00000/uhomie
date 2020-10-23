
CREATE  PROCEDURE `sp_finantial_user_score`(
                IN type_empl int, IN rent int,
                IN amount int, IN save int,
                IN other int, IN last_invo int,
                IN points int,
                IN f1 varchar(1000),
                INOUT f varchar(10000),
                OUT total int
        )
BEGIN

    DECLARE summary float default 0;
    SET total = 0;
    CASE type_empl
        WHEN 1 THEN
            SET summary = amount + save/12 + other;
        WHEN 2 THEN
            SET summary = last_invo + save/12 + other;
        WHEN 3 THEN
            SET summary =  save/12 + other;
        ELSE SET summary = 0;
    END CASE;
    SET summary = summary *.35;
    if summary >= rent then
        SET total = points;
    else
        SET total = points*(summary/rent);
        SET f = CONCAT(f,'/',f1);
    end if;
END
