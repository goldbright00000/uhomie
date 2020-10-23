CREATE  FUNCTION sf_identity_user_score(
Uuser_id int, Sdni_o int,
Sdni_r int) RETURNS int(11)
BEGIN
    DECLARE points int DEFAULT 0;
    DECLARE done bool DEFAULT false;
    DECLARE Dname varchar(60) DEFAULT '';

    DECLARE docs CURSOR FOR
            SELECT name
            FROM files
            WHERE user_id = Uuser_id and verified;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    OPEN docs;

    get_docs: LOOP
        FETCH docs INTO Dname;
    IF done  THEN
        LEAVE get_docs;
    else
        if Dname = 'id_front' then
            set points =  points + Sdni_o;
        end if;
        if Dname = 'id_back' then
            set points =  points + Sdni_r;
        end if;
    END IF;
END LOOP get_docs;

CLOSE docs;
RETURN points;
END
