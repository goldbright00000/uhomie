
CREATE  FUNCTION `sf_demand`(
            property int
) RETURNS int
BEGIN

	DECLARE post int DEFAULT 0;
	DECLARE Slow int DEFAULT 0;

	DECLARE Smedium int DEFAULT 0;
	DECLARE done bool DEFAULT false;

	DECLARE Shigh int DEFAULT 0;
	DECLARE level int DEFAULT 0;

    SET Shigh = (SELECT points  FROM v_scoring  WHERE cat_id = 7 AND scoring_id=2  AND det_id = 54);
    SET Smedium = (SELECT points  FROM v_scoring  WHERE cat_id = 7 AND scoring_id=2  AND det_id = 55);
    SET Slow = (SELECT points  FROM v_scoring  WHERE cat_id = 7 AND scoring_id=2  AND det_id = 56);

	SET post = (SELECT count(id)
		FROM users_has_properties
        WHERE property_id =property AND
				type = 2);

    IF post < Slow THEN
     	RETURN 0;
   	ELSE
   		IF post >= Slow and post <= Smedium THEN
   			RETURN 1;
   		ELSE
   			RETURN 2;
   		END IF;

    END IF;

    RETURN level;

END
