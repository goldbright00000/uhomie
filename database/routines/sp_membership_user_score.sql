
CREATE PROCEDURE `sp_membership_user_score`(
            IN user_id varchar(20),
            IN basic_points int,
            IN select_points int,
            IN prime_points int,
            IN f1 varchar(1000),
            IN f2 varchar(1000),
            IN f3 varchar(1000),
            INOUT f varchar(10000),
            OUT points int
        )
BEGIN

    DECLARE member varchar(20) DEFAULT '';
    SET points = 0;
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
            SET points = 0;
            SET f = CONCAT(f,'/',f1);
        end if;
        IF(member = 'Basic') then
            SET points = basic_points;
            SET f = CONCAT(f,'/',f1);
        end if;
        IF(member = 'Select') then
            SET points = select_points;
            SET f = CONCAT(f,'/',f2);
        end if;
        IF(member = 'Premium') then
            SET points = prime_points;

        end if;
END
