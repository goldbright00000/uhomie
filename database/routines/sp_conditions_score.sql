CREATE  PROCEDURE `sp_conditions_score`(
     IN Uwarranty INT,
     IN Uadvancement INT,
     IN Utime INT,
     IN Swarranty1 int,
	 IN Swarranty2 int,
	 IN Swarranty3 INT,
	 IN Sadvancement1 int,
	 IN Sadvancement2 int,
	 IN Sadvancement3 int,
	 IN Stime1 int,
	 IN Stime2 int,
     IN f1 varchar(1000),
     IN f2 varchar(1000),
     IN f3 varchar(1000),
     IN f4 varchar(1000),
     IN f5 varchar(1000),
     IN f6 varchar(1000),
     IN f7 varchar(1000),
     IN f8 varchar(1000),
     INOUT f varchar(10000),
     OUT points int
)
BEGIN
    SET points = 0;

    if (Uwarranty = 1) then
        SET points = points + Swarranty1;
        SET f = CONCAT(f,'/',f1);
    end if;
    if (Uwarranty = 2 ) then
        SET points = points + Swarranty2;
        SET f = CONCAT(f,'/',f2);
    end if;
    if (Uwarranty > 2) then
        SET points = points + Swarranty3;
    end if;

    if (Uadvancement = 1) then
        SET points = points + Sadvancement1;
        SET f = CONCAT(f,'/',f4);
    end if;
    if (Uadvancement = 2 ) then
        SET points = points + Sadvancement2;
        SET f = CONCAT(f,'/',f5);
    end if;
    if (Uadvancement > 2) then
        SET points = points + Sadvancement3;
    end if;

    if (Utime < 12) then
        SET points = points + Stime1;
        SET f = CONCAT(f,'/',f7);
    end if;
    if (Utime >= 2 ) then
        SET points = points + Stime2;
    end if;

END
