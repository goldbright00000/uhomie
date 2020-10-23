CREATE  PROCEDURE `sp_nationality_score_user`(
    IN country int,
    IN origin int,
    IN document varchar(20),
    IN national_u INT,
    IN rut INT,
    IN provisional INT,
    IN pass INT,
    IN f1 VARCHAR(1000),
    IN f2 VARCHAR(1000),
    IN f3 VARCHAR(1000),
    IN f4 VARCHAR(1000),
    INOUT f VARCHAR(10000),
    OUT points int

)
BEGIN
    SET points = 0;
    if origin = country  then
        SET points = national_u;
    else
        IF (document IS NULL) then
            SET f = CONCAT(f,'/',f3);
        else
            if document = 'RUT_PROVISIONAL' then
                SET points = provisional;
            else
                if document = 'PASAPORTE' then
                    SET points = pass;
                else
                    if document = 'RUT' then
                        SET points = rut;
                    else
                        SET f = CONCAT(f,'/',f3);
                    end if;
                end if;
            end if;
        end if;
    end if;
END
