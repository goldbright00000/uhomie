CREATE  FUNCTION `sf_docs_user_score`(
Uuser_id int, Ujob int,
SafpE int, SafpF int, SafpU int,
SdicomE  int, SdicomF  int, SdicomU  int,
SotherF int, SotherU int,
SsavesF int,SsavesU int,
Sf_set int,Ss_set int,St_set int,
Swork int, Slast int
) RETURNS int(11)
BEGIN
    DECLARE points int DEFAULT 0;
    DECLARE done bool DEFAULT false;
    DECLARE Dname varchar(60) DEFAULT '';
    DECLARE Dfactor integer DEFAULT 0;
    DECLARE Dval_date boolean DEFAULT 0;

    DECLARE docs CURSOR FOR
            SELECT name, factor, val_date
            FROM files
            WHERE user_id = Uuser_id and verified;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    /* recorrido de los documentos*/

    OPEN docs;
    get_docs: LOOP
    FETCH docs INTO Dname, Dfactor, Dval_date;

    IF done  THEN
        LEAVE get_docs;
    else
        CASE Ujob
            WHEN 1 THEN
                if Dname = 'afp' then
                    if Dval_date = true then
                        set points =  points + SafpE;
                    end if;
                end if;
                if Dname = 'dicom' then
                    set points =  points + ((Dfactor*SdicomE)/999);
                end if;
                if Dname = 'first_settlement' then
                    set points =  points + Sf_set;
                end if;
                if Dname = 'second_settlement' then
                    set points =  points + Ss_set;
                end if;
                if Dname = 'third_settlement' then
                    set points =  points + St_set;
                end if;
                if Dname = 'work_constancy' then
                    set points =  points + Swork;
                end if;
            WHEN 2 THEN
                if Dname = 'afp' then
                    if Dval_date = true then
                        set points =  points + SafpF;
                    end if;
                end if;
                if Dname = 'dicom' then
                    set points =  points + ((Dfactor*SdicomF)/999);
                end if;

                if Dname = 'other_income' then
                    set points =  points + SotherF;
                end if;
                if Dname = 'last_invoice' then
                    set points =  points + Slast;
                end if;
                if Dname = 'saves' then
                    set points =  points + SsavesF;
                end if;
            WHEN 3 THEN
                if Dname = 'afp' then
                    if Dval_date = true then
                        set points =  points + SafpU;
                    end if;
                end if;
                if Dname = 'dicom' then
                    set points =  points + ((Dfactor*SdicomU)/999);
                end if;
                if Dname = 'other_income' then
                    set points =  points + SotherU;
                end if;
                if Dname = 'saves' then
                    set points =  points + SsavesU;
                end if;
            ELSE set points = 0;
        END CASE;
    END IF;
END LOOP get_docs;
CLOSE docs;

RETURN points;
END

