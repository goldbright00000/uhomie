CREATE FUNCTION `sf_applied`(
user int,
property int

) RETURNS bool
BEGIN
	DECLARE result bool DEFAULT false;
	SET result = (select postulates.id
						from postulates
						INNER JOIN users_has_properties
							ON users_has_properties.id = postulates.id
							AND users_has_properties.user_id = user and
							postulates.espera = 0 and 
								users_has_properties.property_id = property);
	IF not (result IS NULL) then
		RETURN true;
	else
		RETURN false;
	end if;


RETURN false;
END
