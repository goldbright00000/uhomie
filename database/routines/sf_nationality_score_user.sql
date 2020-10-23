
CREATE  FUNCTION `sf_nationality_score_user`(

    country int,
    origin int,
    document varchar(20),

    national_u INT,
    rut INT,
    provisional INT,
    pass INT

) RETURNS int(11)
BEGIN

    if origin = country  then
        RETURN national_u;
    else
        IF (document IS NULL) then
            RETURN 0;
        else
            if document = 'RUT_PROVISIONAL' then
                RETURN provisional;
            else
                if document = 'PASAPORTE' then
                    RETURN pass;
                else
                    if document = 'RUT' then
                        RETURN rut;
                    else
                        RETURN 0;
                    end if;

                end if;
            end if;
        end if;
    end if;
END
