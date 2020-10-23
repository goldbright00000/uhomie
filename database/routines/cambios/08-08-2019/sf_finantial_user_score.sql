CREATE  FUNCTION `sf_finantial_user_score`(
                Uuser_id int,
                type_empl int, rent int,
                amount int, save int,
                other int, last_invo int,
                points int
        ) RETURNS int(11)
BEGIN
    DECLARE score INT;
    DECLARE amount_verified int default 0;
    DECLARE other_income_verified int default 0;
    DECLARE last_invoice_verified int default 0;
    DECLARE saves_verified int default 0;
    DECLARE Dname varchar(60) DEFAULT '';
    DECLARE Dfactor integer DEFAULT 0;
    DECLARE Dval_date boolean DEFAULT 0;
    DECLARE summary float default 0;
    DECLARE done bool DEFAULT false;

    DECLARE docs CURSOR FOR
            SELECT name, factor, val_date
            FROM files
            WHERE user_id = Uuser_id and verified;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN docs;
    get_docs: LOOP
    FETCH docs INTO Dname, Dfactor, Dval_date;

    IF done  THEN
        LEAVE get_docs;
    else
        if Dname = 'first_settlement' then
            set amount_verified = amount_verified + Dfactor;
        end if;
        if Dname = 'second_settlement' then
            set amount_verified = amount_verified + Dfactor;
        end if;
        if Dname = 'third_settlement' then
            set amount_verified = amount_verified + Dfactor;
        end if;

        if Dname = 'other_income' then
            set other_income_verified = other_income_verified + Dfactor;
        end if;

        if Dname = 'last_invoice' then
            set last_invoice_verified = last_invoice_verified + Dfactor;
        end if;
        
        if Dname = 'saves' then
            set saves_verified = saves_verified + Dfactor;
        end if;
    END IF;
    END LOOP get_docs;
    CLOSE docs;

    CASE type_empl
        WHEN 1 THEN
            SET summary = (amount_verified/3) + saves_verified/12 +  other_income_verified;
        WHEN 2 THEN
            SET summary = last_invoice_verified + saves_verified/12 + other_income_verified;
        WHEN 3 THEN
            SET summary =  saves_verified/12 + other_income_verified;
        ELSE RETURN 0;
    END CASE;
    SET summary = summary *.35;
    if summary >= rent then
        RETURN points;
    else
        RETURN points*(summary/rent);
    end if;

END
