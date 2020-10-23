CREATE  PROCEDURE `sp_score_user`(
in user_id int,
in property_id int

)
BEGIN
    DECLARE done int DEFAULT false;
    DECLARE score int DEFAULT 0;
    DECLARE finantial int DEFAULT 0;

    DECLARE contact int DEFAULT 0;
    DECLARE endorsement int DEFAULT 0;
    DECLARE advance int DEFAULT 0;

    DECLARE membership int DEFAULT 0;
    DECLARE time_f int DEFAULT 0;
    DECLARE warranty int DEFAULT 0;

    DECLARE feedback varchar(1000) DEFAULT 'Sugerencias ';
    DECLARE member varchar(20);
    DECLARE misce int default 0;

    DECLARE nation int default 0;
    DECLARE country int default 0;
    DECLARE job int default 0;

    DECLARE docs int default 0;



    /* score_configs - T A B L E */
        /* finantial function var */
    DECLARE Smax_pts int;
    DECLARE Sone int;
    DECLARE Stwo int;

    DECLARE Smore int;

        /* contact function var */
    DECLARE Smail int(11) DEFAULT 0;
    DECLARE Sphone int(11) DEFAULT 0;

        /* endorsement function vars*/
    DECLARE Sp_conf int;
    DECLARE Sp_unco int;
        /* membership function vars */
    DECLARE Sbasic int(11) DEFAULT 0;
    DECLARE Sselect int(11) DEFAULT 0;
    DECLARE Sprime int(11) DEFAULT 0;

        /* time function vars*/
    DECLARE Sless_year int(11) DEFAULT 0;
    DECLARE Sgreater_year int(11) DEFAULT 0;

        /* warranty function vars*/
    DECLARE Smore_one int(11) DEFAULT 0;
    DECLARE Sone_warranty int(11) DEFAULT 0;

    /* misce function vars*/
    DECLARE Smisce_points int(11) DEFAULT 0;

    /* nacionality function vars*/
    DECLARE Snational int(11) DEFAULT 0;
    DECLARE Sprovitional int(11) DEFAULT 0;
    DECLARE Spassport int(11) DEFAULT 0;

    /* job function var */
    DECLARE Sfree int(11) DEFAULT 0;
    DECLARE Semploye int(11) DEFAULT 0;
    DECLARE Sunemploye int(11) DEFAULT 0;

    DECLARE freelance int(11) DEFAULT 0;

    /* documents function var */
    DECLARE Sdni_o int(11) DEFAULT 0;
    DECLARE Sdni_r int(11) DEFAULT 0;
    DECLARE Sf_set int(11) DEFAULT 0;

    DECLARE Ss_set int(11) DEFAULT 0;
    DECLARE St_set int(11) DEFAULT 0;
    DECLARE Safp int(11) DEFAULT 0;

    DECLARE Swork int(11) DEFAULT 0;
    DECLARE Sdicom int(11) DEFAULT 0;
    DECLARE Slast int(11) DEFAULT 0;

    DECLARE Sfree_other int(11) DEFAULT 0;
    DECLARE Sunem_other int(11) DEFAULT 0;
    DECLARE Sfree_saves int(11) DEFAULT 0;

    DECLARE Sunem_saves int(11) DEFAULT 0;



    /* users  - T A B L E */
        /* finantial and job function var */
    DECLARE Uamount int;
    DECLARE Ulast int;
    DECLARE Uemployment int;

    DECLARE Usave int;
    DECLARE Uother int;

        /* contacts function vars */
    DECLARE Umail bool;
    DECLARE Uphone bool;

        /* endorsement function vars*/
    DECLARE Ucoll int;
    DECLARE Uconf_coll bool;

        /* advancement function */
    DECLARE Umonths int;

    /* time function vars*/
    DECLARE Utime int(11) DEFAULT 0;

    /* warranty function vars*/
    DECLARE Uquantity int(11) DEFAULT 0;

    /* misce function vars*/
    DECLARE Upet varchar(20) DEFAULT 0;
    DECLARE Usmock int(11) DEFAULT 0;
    DECLARE Upro_type int(11) DEFAULT 0;

    DECLARE Udate date;

    /* nacionality function vars*/
    DECLARE Ucountry int(11) DEFAULT 0;
    DECLARE Udocument varchar(20);





    /* properties - T A B L E */
    DECLARE Prent int;

        /* misce function vars*/
    DECLARE Ppet varchar(20);
    DECLARE Psmock int(11) DEFAULT 0;
    DECLARE Ppro_type int(11) DEFAULT 0;

    DECLARE Pdate date;

    DECLARE configs CURSOR FOR
            SELECT  max_points_rent, mail,
                    phone,registered_endorsement,
                    unsecured_endorsement, one_advance,
                    two_advance, more_advance,
                    basic_member, select_member,
                    prime_member, greater_than_year,
                    less_than_year, warranty_more,
                    warranty_one, misce_points,
                    national, provitional,
                    passport, job_employe,
                    job_freelance, job_unemploye,
                    dni_obverce_points,dni_review_points,
                    first_settlement_points,second_settlement_points,
                    third_settlement_points,afp_points,
                    work_constancy_points,dicom_points,
                    last_invoice_points,free_other_income_points,
                    unemploy_other_income_points, free_saves_points,
                    unemploy_saves_points





            FROM score_configs limit 1;

    DECLARE lessor CURSOR FOR
        SELECT  mail_verified,  phone_verified,
                amount, save_amount,
                other_income_amount, last_invoice_amount,
                employment_type, collateral_id,
                confirmed_collateral, months_advance_quantity,
                tenanting_months_quantity, warranty_months_quantity,
                pet_preference, smoking_allowed,
                property_type,move_date,
                country_id, document_type


            FROM users
            WHERE id = user_id;

    DECLARE property CURSOR FOR
            SELECT rent, pet_preference,
                    smoking_allowed, property_type_id,
                    available_date

            FROM properties
            WHERE property_id=id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;


    /* SETEO de Variables de Configuracion*/

    OPEN configs;
    get_configs: LOOP
    FETCH configs INTO  Smax_pts, Smail,
                        Sphone, Sp_conf,
                        Sp_unco, Sone,
                        Stwo, Smore,
                        Sbasic, Sselect,
                        Sprime, Sgreater_year,
                        Sless_year, Smore_one,
                        Sone_warranty, Smisce_points,
                        Snational, Sprovitional,
                        Spassport, Semploye,
                        Sfree, Sunemploye,
                        Sdni_o, Sdni_r,
                        Sf_set,Ss_set,
                        St_set,Safp,
                        Swork,Sdicom,
                        Slast,Sfree_other,
                        Sunem_other,Sfree_saves,
                        Sunem_saves;

        IF done  THEN
            LEAVE get_configs;
        END IF;

    END LOOP get_configs;
    CLOSE configs;

    /* SETEO de Variables de Arrendador */

    OPEN lessor;
    get_lessor: LOOP
    FETCH lessor INTO   Umail,Uphone,
                        Uamount, Usave,
                        Uother, Ulast,
                        Uemployment, Ucoll,
                        Uconf_coll, Umonths,
                        Utime, Uquantity,
                        Upet, Usmock,
                        Upro_type, Udate,
                        Ucountry, Udocument;

        IF done  THEN
            LEAVE get_lessor;
        END IF;

    END LOOP get_lessor;
    CLOSE lessor;

/* SETEO de Variables de Propiedades */

    OPEN property;
    get_property: LOOP
    FETCH property INTO Prent, Ppet,
                        Psmock, Ppro_type,
                        Pdate;

        IF done  THEN
            LEAVE get_property;
        END IF;

    END LOOP get_property;
    CLOSE property;

    /* contacts score   FUNCTION*/

    SET contact = sf_contact_score_user(Uphone,Umail,Sphone,Smail);

    if contact <= ((Sphone+Smail)/2) then
        SET feedback = CONCAT(feedback,'aqui * verificación de contactos. ');
    end if;
    SET score = score + contact;


    /*finantial score FUNCTION*/

    SET finantial = sf_finantial_score_user(Uemployment,Prent,
                                Uamount,Usave,
                                Uother,Ulast,
                                Smax_pts);
    if finantial <= Smax_pts then
        SET feedback = CONCAT(feedback,'aqui *financiera. ');
    end if;

    SET score = score + finantial;

    /*   endorsement score FUNCTION*/

    SET endorsement = sf_endorsement_score_user(Uconf_coll, Sp_conf, Sp_unco);
    if endorsement < Sp_conf then
        SET feedback = CONCAT(feedback,'aqui * confirmación de aval. ');
    end if;

    SET score = score + endorsement;

    /*advance score FUNCTION*/
    SET advance = sf_advancement_score_user(Umonths,Sone,
                                Stwo,Smore);
    if advance <= Stwo then
        SET feedback = CONCAT(feedback,'aqui * adelanto en meses. ');
    end if;

    SET score = score + advance;

    /*membership score FUNCTION*/

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


    SET membership = sf_membership_score_user(member,Sprime,Sselect, Sbasic);
    if  membership <= Sselect then
        SET feedback = CONCAT(feedback,'aqui * Membresia. ');
    end if;
    SET score = score + membership;

    /*time score FUNCTION*/

    SET time_f = sf_time_score_user(Utime,Sgreater_year,Sless_year);
    if  time_f  < Sgreater_year then
        SET feedback = CONCAT(feedback,'aqui * Tiempo de arriendo. ');
    end if;
    SET score = score + time_f;

     /*warranty score FUNCTION */

    SET warranty = sf_months_warranty_user(Uquantity, Smore_one,Sone_warranty);
    if  warranty  < Smore_one then
        SET feedback = CONCAT(feedback,'aqui * mese en garantia. ');
    end if;
    SET score = score + warranty;

     /*misce score FUNCTION */

    SET misce = sf_misce_user(
                            Smisce_points, Upet,Ppet,
                            Usmock, Psmock, Upro_type,
                            Ppro_type, Udate, Pdate,
                            user_id, property_id);

    if  misce  < 10 then
        SET feedback = CONCAT(feedback,'aqui * miselanias. ');
    end if;
    SET score = score + misce;

         /*nationality  score FUNCTION */

    SET country = (SELECT id from countries where valid);
    SET nation = sf_nationality_score_user(
                            country, Ucountry,
                            Udocument, Snational,
                            Sprovitional, Spassport
                            );

    if  misce  <= Sprovitional then
        SET feedback = CONCAT(feedback,'aqui * nacionalidad. ');
    end if;
    SET score = score + nation;

           /*job  score FUNCTION */
    SET job = sf_job_score_user(
                            Uemployment, Semploye,
                            Sfree, Sunemploye
                            );

    if  job  <= Sfree then
        SET feedback = CONCAT(feedback,'aqui * tipo de trabajo. ');
    end if;
    SET score = score + job;

           /*documents  score FUNCTION */


    SET docs = sf_documents_score_user(user_id, Uemployment, Sdni_o,
                    Sdni_r,Sf_set,Ss_set,St_set,Safp, Swork,
                    Sdicom, Slast, Sfree_other,Sunem_other,
                    Sfree_saves, Sunem_saves);

    if  docs  <= docs/2 then
        SET feedback = CONCAT(feedback,'aqui * docs. ');
    end if;
    SET score = score + docs;


     /*select contact,finantial,endorsement,advance,membership,time_f,warranty,score,feedback;
     */
   select  score,finantial,
            contact,endorsement,
            advance,time_f,
            warranty, membership,
            misce,nation,
            job,docs;


END
