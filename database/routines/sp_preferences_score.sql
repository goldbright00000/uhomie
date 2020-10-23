
CREATE  PROCEDURE `sp_preferences_score`(
            IN Spro_type int,
            IN Spro_for int,
            IN Sdate int,
            IN Spet int,
            IN Ssmock int,

            IN Upet varchar(20),
            IN Ppet varchar(20),

            IN Usmock bool,
            IN Psmock bool,
            IN Utype int,

            IN Ptype int,
            IN Udate date,
            IN Pdate date,

            IN user_id int,
            IN property_id int,
            IN f1 varchar(1000),
            IN f2 varchar(1000),
            IN f3 varchar(1000),
            IN f4 varchar(1000),
            IN f5 varchar(1000),
            INOUT f varchar(10000),
            OUT sco int

        )
BEGIN

    DECLARE value_id int;
    SET sco= 0;

    if Upet = Ppet then
        set sco = sco + Spet;
    ELSE
        SET f = CONCAT(f,'/',f4);
    end if;

    if Usmock = Psmock then
        set sco = sco + Ssmock;
    ELSE
        SET f = CONCAT(f,'/',f5);
    end if;

    if Utype = Ptype then
        set sco = sco + Spro_type;
    ELSE
        SET f = CONCAT(f,'/',f1);
    end if;

    if Udate >= Pdate then
        set sco = sco + Sdate;
    ELSE
        SET f = CONCAT(f,'/',f3);
    end if;
    set value_id =(select properties_has_properties_for.id
        from    properties_has_properties_for,
                users, properties
        where
            users.id = user_id and
            properties.id = property_id and
            properties.id =properties_has_properties_for.property_id and
            users.property_for = properties_has_properties_for.property_for_id);

    IF not (value_id IS NULL) then
            set sco = sco + Spro_for;
    ELSE
        SET f = CONCAT(f,'/',f2);
    end if;


END
