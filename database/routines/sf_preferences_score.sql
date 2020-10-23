
CREATE  FUNCTION `sf_preferences_score`(
            Spro_type int,
            Spro_for int,
            Sdate int,
            Spet int,
            Ssmock int,

            Upet varchar(20),
            Ppet varchar(20),

            Usmock bool,
            Psmock bool,
            Utype int,

            Ptype int,
            Udate date,
            Pdate date,

            user_id int,
            property_id int
        ) RETURNS int(11)
BEGIN
    DECLARE sco int DEFAULT 0;
    DECLARE value_id int;

    if Upet = Ppet then
        set sco = sco + Spet;
    end if;

    if Usmock = Psmock then
        set sco = sco + Ssmock;
    end if;

    if Utype = Ptype then
        set sco = sco + Spro_type;
    end if;

    if Udate >= Pdate then
        set sco = sco + Sdate;
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
    end if;

    RETURN sco;
END
