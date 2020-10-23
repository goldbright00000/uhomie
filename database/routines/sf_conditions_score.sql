CREATE  FUNCTION `sf_conditions_score`(
     Uwarranty INT,
     Uadvancement INT,
     Utime INT,
     Swarranty1 int,
	 Swarranty2 int,
	 Swarranty3 INT,
	 Sadvancement1 int,
	 Sadvancement2 int,
	 Sadvancement3 int,
	 Stime1 int,
	 Stime2 int
) RETURNS int(11)
BEGIN
    DECLARE points int DEFAULT 0;

    if (Uwarranty = 1) then
        SET points = points + Swarranty1;
    end if;
    if (Uwarranty = 2 ) then
        SET points = points + Swarranty2;
    end if;
    if (Uwarranty > 2) then
        SET points = points + Swarranty3;
    end if;

    if (Uadvancement = 1) then
        SET points = points + Sadvancement1;
    end if;
    if (Uadvancement = 2 ) then
        SET points = points + Sadvancement2;
    end if;
    if (Uadvancement > 2) then
        SET points = points + Sadvancement3;
    end if;

    if (Utime < 12) then
        SET points = points + Stime1;
    end if;
    if (Utime >= 2 ) then
        SET points = points + Stime2;
    end if;
    RETURN points;
END
