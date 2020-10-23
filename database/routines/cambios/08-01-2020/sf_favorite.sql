CREATE  FUNCTION `sf_favorite`(
user int,
property int

) RETURNS bool
BEGIN
	DECLARE result bool DEFAULT false;
	SET result = (select count(id)
						from users_has_properties
						where 	user_id = user and
								property_id = property and
								type = 3);
	IF (result > 0) then
		RETURN true;
	else
		RETURN false;
	end if;


RETURN false;
END
