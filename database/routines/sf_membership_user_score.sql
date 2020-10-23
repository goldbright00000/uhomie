
CREATE FUNCTION `sf_membership_user_score`(
            user_id varchar(20),
            basic_points int,
            select_points int,
            prime_points int
        ) RETURNS int(11)
BEGIN
    DECLARE member varchar(20) DEFAULT '';
    SET member = (select  c.name  from
                users as a,
                users_has_memberships as b,
                memberships as c,
                roles as d
        where
                a.id = user_id and
                a.id = b.user_id and
                b.membership_id = c.id and
                d.id =c.role_id and
                d.name = 'Arrendatario');
        IF(member IS NULL) then
            RETURN 0;
        end if;
        IF(member = 'Basic') then
            RETURN basic_points;
        end if;
        IF(member = 'Select') then
            RETURN select_points;
        end if;
        IF(member = 'Premium') then
            RETURN prime_points;
        end if;
END
