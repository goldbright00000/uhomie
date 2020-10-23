CREATE FUNCTION `sf_applied`(
user int,
property int

) RETURNS bool
BEGIN
	DECLARE result int DEFAULT 0;
	SET result = (select count(postulates.id)
						from postulates
						INNER JOIN users_has_properties
							ON users_has_properties.id = postulates.id
						WHERE users_has_properties.user_id = user
                            AND users_has_properties.type = 2
                            AND postulates.espera = 0
                            AND users_has_properties.property_id = property);
	IF (result > 0) then
		RETURN true;
	else
		RETURN false;
	end if;


RETURN false;
END