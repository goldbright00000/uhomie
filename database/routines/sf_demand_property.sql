
CREATE  FUNCTION `sf_demand_property`(
            property int
) RETURNS int
BEGIN

	DECLARE post int DEFAULT 0;
	DECLARE Slow int DEFAULT 0;

	DECLARE done bool DEFAULT false;

	DECLARE Shigh int DEFAULT 0;
	DECLARE level int DEFAULT 0;

    SET Shigh = 11;
    SET Slow = 4;

	SET post = (SELECT count(id)
		FROM users_has_properties
        WHERE property_id =property AND
				type = 2);

    IF post < Slow THEN
     	RETURN 0;
   	ELSE
   		IF post >= Slow and post <= Shigh THEN
   			RETURN 1;
   		ELSE
   			RETURN 2;
   		END IF;

    END IF;

    RETURN level;

END
