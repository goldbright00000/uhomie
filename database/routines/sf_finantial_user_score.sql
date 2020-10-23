
CREATE  FUNCTION `sf_finantial_user_score`(
                type_empl int, rent int,
                amount int, save int,
                other int, last_invo int,
                points int
        ) RETURNS int(11)
BEGIN
    DECLARE score INT;
    DECLARE summary float default 0;
    CASE type_empl
        WHEN 1 THEN
            SET summary = amount + save/12 + other;
        WHEN 2 THEN
            SET summary = last_invo + save/12 + other;
        WHEN 3 THEN
            SET summary =  save/12 + other;
        ELSE RETURN 0;
    END CASE;
    SET summary = summary *.35;
    if summary >= rent then
        RETURN points;
    else
        RETURN points*(summary/rent);
    end if;
END
