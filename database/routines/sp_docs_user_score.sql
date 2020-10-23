CREATE  PROCEDURE `sp_docs_user_score`(
IN Uuser_id int, IN Ujob int,
IN SafpE int, IN SafpF int, IN SafpU int,
IN SdicomE  int, IN SdicomF  int, IN SdicomU  int,
IN SotherF int, IN SotherU int,
IN SsavesF int,IN SsavesU int,
IN Sf_set int,IN Ss_set int,IN St_set int,
IN Swork int, IN Slast int,
IN f2 varchar(1000), IN f3 varchar(1000), IN f4 varchar(1000), IN f5 varchar(1000), IN f6 varchar(1000),
IN f7 varchar(1000), IN f9 varchar(1000), IN f10 varchar(1000), IN f11 varchar(1000), IN f12 varchar(1000),
IN f13 varchar(1000), IN f15 varchar(1000), IN f16 varchar(1000), IN f17 varchar(1000), IN f18 varchar(1000),
INOUT f varchar(10000), OUT points int
)
BEGIN

    DECLARE done bool DEFAULT false;
    DECLARE Dval bool DEFAULT false;
    DECLARE Dname varchar(60) DEFAULT '';
    DECLARE Dfactor integer DEFAULT 0;
    DECLARE Dval_date boolean DEFAULT 0;

    DECLARE docs CURSOR FOR
            SELECT name, factor, val_date,verified
            FROM files
            WHERE user_id = Uuser_id and verified;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    /* recorrido de los documentos*/
    SET points = 0;
    OPEN docs;
    get_docs: LOOP
    FETCH docs INTO Dname, Dfactor, Dval_date, Dval;

    IF done  THEN
        LEAVE get_docs;
    else
        CASE Ujob
            WHEN 1 THEN
                if Dname = 'afp' then
                    if Dval_date = true and Dval = true then
                        set points =  points + SafpE;
                    else
                        SET f = CONCAT(f,'/',f2);
                    end if;
                end if;
                if Dname = 'dicom' and Dval = true then
                    set points =  points + ((Dfactor*99)/999);
                else
                    SET f = CONCAT(f,'/',f3);
                end if;
                if Dname = 'first_settlement' and Dval = true then
                    set points =  points + Sf_set;
                else
                    SET f = CONCAT(f,'/',f4);
                end if;
                if Dname = 'second_settlement' and Dval = true then
                    set points =  points + Ss_set;
                else
                    SET f = CONCAT(f,'/',f5);
                end if;
                if Dname = 'third_settlement' and Dval = true then
                    set points =  points + St_set;
                else
                    SET f = CONCAT(f,'/',f6);
                end if;
                if Dname = 'work_constancy' and Dval = true then
                    set points =  points + Swork;
                else
                    SET f = CONCAT(f,'/',f7);
                end if;
            WHEN 2 THEN
                if Dname = 'afp'   then
                    if Dval_date = true and Dval = true then
                        set points =  points + SafpF;
                    else
                        SET f = CONCAT(f,'/',f9);
                    end if;
                end if;
                if Dname = 'dicom' and Dval = true then
                    set points =  points + ((Dfactor*99)/999);
                else
                    SET f = CONCAT(f,'/',f10);
                end if;

                if Dname = 'other_income' and Dval = true then
                    set points =  points + SotherF;
                else
                    SET f = CONCAT(f,'/',f11);
                end if;
                if Dname = 'last_invoice' and Dval = true then
                    set points =  points + Slast;
                else
                    SET f = CONCAT(f,'/',f12);
                end if;
                if Dname = 'saves' and Dval = true then
                    set points =  points + SsavesF;
                else
                    SET f = CONCAT(f,'/',f13);
                end if;
            WHEN 3 THEN
                if Dname = 'afp' then
                    if Dval_date = true and Dval = true then
                        set points =  points + SafpU;
                    else
                        SET f = CONCAT(f,'/',f15);
                    end if;
                end if;
                if Dname = 'dicom' and Dval = true then
                    set points =  points + ((Dfactor*99)/999);
                else
                    SET f = CONCAT(f,'/',f16);
                end if;
                if Dname = 'other_income' and Dval = true then
                    set points =  points + SotherU;
                else
                    SET f = CONCAT(f,'/',f17);
                end if;
                if Dname = 'saves' and Dval = true then
                    set points =  points + SsavesU;
                else
                        SET f = CONCAT(f,'/',f18);
                end if;
        END CASE;
    END IF;
END LOOP get_docs;
CLOSE docs;
END
