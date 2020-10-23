CREATE  PROCEDURE `sp_score_user`(

in user_id int,
in property_id int

)
BEGIN
	DECLARE done int DEFAULT false;
    /* 			contact function var 				*/
    DECLARE score int(11) DEFAULT 0;
    DECLARE Smail int(11) DEFAULT 0;
    DECLARE Sphone int(11) DEFAULT 0;
    DECLARE Umail bool;
    DECLARE Uphone bool;
    DECLARE Ucountry int(11) DEFAULT 0;
    DECLARE Udocument varchar(20);
    /* 			contact function var 					*/
    DECLARE Sdni_o int(11) DEFAULT 0;
    DECLARE Sdni_r int(11) DEFAULT 0;

	/*		 	nationality_score_user vars 			*/

	DECLARE Snational int(11) DEFAULT 0;
	DECLARE Srut int(11) DEFAULT 0;
    DECLARE Sprovitional int(11) DEFAULT 0;
    DECLARE Spassport int(11) DEFAULT 0;
	DECLARE country int(11) DEFAULT 0;
	DECLARE nationality_score_user int(11) DEFAULT 0;
	/* 			guarantor_score Vars 			*/
	DECLARE Ucollateral_id int(11) DEFAULT 0;
	DECLARE Sconfirmed int(11) DEFAULT 0;
	DECLARE Sunconfirmed int(11) DEFAULT 0;
	DECLARE Uconfirmed_collateral bool;
	DECLARE guarantor_score int(11) DEFAULT 0;
	DECLARE guarantor_confirmation_score int(11) DEFAULT 0;
	DECLARE guarantor_identification_score int(11) DEFAULT 0;
	DECLARE contact_guarantor_score int(11) DEFAULT 0;
	DECLARE identity_guarantor_score int(11) DEFAULT 0;
	DECLARE nationality_guarantor_score int(11) DEFAULT 0;
    /* 			Categories Vars 			*/
    DECLARE contact_user_score int(11) DEFAULT 0;
    DECLARE identity_user_score int(11) DEFAULT 0;
	DECLARE identification_score int(11) DEFAULT 0;
	/* 			 socioeconomic_stability_score vars				*/
	DECLARE Ujob int(11) DEFAULT 0;
	DECLARE Semploy int(11) DEFAULT 0;
	DECLARE SafpE int(11) DEFAULT 0;
	DECLARE SdicomE int(11) DEFAULT 0;
	DECLARE Sf_set int(11) DEFAULT 0;
	DECLARE Ss_set int(11) DEFAULT 0;
	DECLARE St_set int(11) DEFAULT 0;
	DECLARE Swork int(11) DEFAULT 0;
	DECLARE Sfree int(11) DEFAULT 0;
	DECLARE SafpF int(11) DEFAULT 0;
	DECLARE SdicomF int(11) DEFAULT 0;
	DECLARE SotherF int(11) DEFAULT 0;
	DECLARE SsavesF int(11) DEFAULT 0;
	DECLARE Sinsurance int(11) DEFAULT 0;
	DECLARE Slast int(11) DEFAULT 0;
	DECLARE Sunemploy int(11) DEFAULT 0;
	DECLARE SafpU int(11) DEFAULT 0;
	DECLARE SdicomU int(11) DEFAULT 0;
	DECLARE SotherU int(11) DEFAULT 0;
	DECLARE SsavesU int(11) DEFAULT 0;
	DECLARE Uinsurance boolean DEFAULT 0;
	DECLARE job_type_score int(11) DEFAULT 0;
	DECLARE job_docs_score int(11) DEFAULT 0;
	DECLARE socioeconomic_stability_score int(11) DEFAULT 0;
	/* preferences and conditions Vars*/
	DECLARE preferences_score int(11) DEFAULT 0;
	DECLARE conditions_score int(11) DEFAULT 0;
	DECLARE preferences_conditions_score int(11) DEFAULT 0;

	DECLARE Prent int(11) DEFAULT 0;
	DECLARE Ppet varchar(30) DEFAULT 0;
	DECLARE Psmock boolean DEFAULT 0;

	DECLARE Ppro_type int(11) DEFAULT 0;
	DECLARE Pdate date ;

	DECLARE Upet varchar(30) DEFAULT 0;
	DECLARE Usmock boolean DEFAULT 0;
	DECLARE Upro_type int(11) DEFAULT 0;
	DECLARE Udate date ;
	DECLARE Uwarranty int DEFAULT 0;
	DECLARE Uadvancement int DEFAULT 0;
	DECLARE Utime int DEFAULT 0;

	DECLARE Spro_type int DEFAULT 0;
	DECLARE Spro_for int DEFAULT 0;
	DECLARE Sdate int DEFAULT 0;
	DECLARE Spet int DEFAULT 0;
	DECLARE Ssmock int DEFAULT 0;
	DECLARE Swarranty1 int DEFAULT 0;
	DECLARE Swarranty2 int DEFAULT 0;
	DECLARE Swarranty3 int DEFAULT 0;
	DECLARE Sadvancement1 int DEFAULT 0;
	DECLARE Sadvancement2 int DEFAULT 0;
	DECLARE Sadvancement3 int DEFAULT 0;
	DECLARE Stime1 int DEFAULT 0;
	DECLARE Stime2 int DEFAULT 0;
	/*  	memberships Vars  		 */
	DECLARE Smembership1 int DEFAULT 0;
	DECLARE Smembership2 int DEFAULT 0;
	DECLARE Smembership3 int DEFAULT 0;
	DECLARE membership_score int DEFAULT 0;
	/*  	finantial Vars  		 */
	DECLARE finantial_score int DEFAULT 0;
	DECLARE Sfinantial int DEFAULT 0;
	DECLARE Uamount int DEFAULT 0;
	DECLARE Usave int DEFAULT 0;
	DECLARE Uother int DEFAULT 0;
	DECLARE Ulast int DEFAULT 0;
	/* feedback Vars*/
	DECLARE identification_feedback varchar(1000) DEFAULT '';
	DECLARE guarantor_feedback varchar(1000) DEFAULT '';
	DECLARE socioeconomic_stability_feedback varchar(1000) DEFAULT '';
	DECLARE conditions_and_preferences_feedback varchar(1000) DEFAULT '';
	DECLARE memberships_feedback varchar(1000) DEFAULT '';
	DECLARE finantial_feedback varchar(1000) DEFAULT '';

	DECLARE identification_details varchar(10000) DEFAULT '';
	DECLARE guarantor_details varchar(10000) DEFAULT '';
	DECLARE socioeconomic_stability_details varchar(10000) DEFAULT '';
	DECLARE conditions_and_preferences_details varchar(10000) DEFAULT '';
	DECLARE memberships_details varchar(10000) DEFAULT '';
	DECLARE finantial_details varchar(10000) DEFAULT '';

	DECLARE feedback1 varchar(1000) DEFAULT '';
	DECLARE feedback2 varchar(1000) DEFAULT '';
	DECLARE feedback3 varchar(1000) DEFAULT '';
	DECLARE feedback4 varchar(1000) DEFAULT '';
	DECLARE feedback5 varchar(1000) DEFAULT '';
	DECLARE feedback6 varchar(1000) DEFAULT '';
	DECLARE feedback7 varchar(1000) DEFAULT '';
	DECLARE feedback8 varchar(1000) DEFAULT '';
	DECLARE feedback9 varchar(1000) DEFAULT '';
	DECLARE feedback10 varchar(1000) DEFAULT '';
	DECLARE feedback11 varchar(1000) DEFAULT '';
	DECLARE feedback12 varchar(1000) DEFAULT '';
	DECLARE feedback13 varchar(1000) DEFAULT '';
	DECLARE feedback14 varchar(1000) DEFAULT '';
	DECLARE feedback15 varchar(1000) DEFAULT '';
	DECLARE feedback16 varchar(1000) DEFAULT '';
	DECLARE feedback17 varchar(1000) DEFAULT '';
	DECLARE feedback18 varchar(1000) DEFAULT '';



	/*			Cursor	  Arrendadores					*/
    DECLARE lessor CURSOR FOR
        SELECT  mail_verified,  phone_verified,
                country_id, document_type,
				collateral_id,  confirmed_collateral,
				employment_type, move_date,
				warranty_months_quantity,months_advance_quantity,
				tenanting_months_quantity,property_type,
				amount, save_amount,
				other_income_amount, last_invoice_amount,
				tenanting_insurance


        FROM users
        WHERE id = user_id;
	/*	  			Cursor propiedades					*/
	DECLARE property CURSOR FOR
        SELECT 	rent, pet_preference,
            	smoking_allowed, property_type_id,
                available_date
	    FROM properties
        WHERE property_id=id;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

	/* SETEO feedback de Catgorias							*/
	SET identification_feedback = (SELECT DISTINCT cat_feed FROM v_scoring
					WHERE 	cat_id = 1);
	SET guarantor_feedback=(	SELECT DISTINCT cat_feed FROM v_scoring
					WHERE 	cat_id = 2);
	SET socioeconomic_stability_feedback =(SELECT DISTINCT cat_feed FROM v_scoring
					WHERE 	cat_id = 3);
	SET conditions_and_preferences_feedback =(SELECT DISTINCT cat_feed	FROM v_scoring
					WHERE 	cat_id = 4);
	SET memberships_feedback =(SELECT DISTINCT cat_feed FROM v_scoring
					WHERE 	cat_id = 5);
	SET finantial_feedback =(SELECT DISTINCT cat_feed FROM v_scoring
					WHERE 	cat_id = 6);


	/* SETEO de Variables de Arrendador */
    OPEN lessor;
    get_lessor: LOOP
        FETCH lessor INTO   Umail,Uphone,
                            Ucountry, Udocument,
							Ucollateral_id, Uconfirmed_collateral,
							Ujob,Udate,
							Uwarranty,Uadvancement,
							Utime,Upro_type,
							Uamount,Usave,
							Uother, Ulast,
							Uinsurance;
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
	/*---------------------------------------------------------------
						CATEGORY = 1
     identification_score  = 	contact_user_score +
	 								nationality_score_user
	---------------------------------------------------------------	*/

	/*---------------------- contact_user_score---------------------*/


	SET Sphone = (SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 1);
	SET feedback1 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 1);
    SET Smail = (SELECT points FROM v_scoring WHERE cat_id = 1 AND scoring_id=1  AND det_id = 2);
	SET feedback2 = (SELECT det_feed FROM v_scoring WHERE cat_id = 1 AND scoring_id=1  AND det_id = 2);
	CALL sp_contact_score_user(Uphone,Umail,Sphone,Smail,feedback1,feedback2,contact_user_score,identification_details);
    /*-----------------    identity_user_score ---------------------- */
    SET Sdni_o = (SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 3);
	SET feedback1 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 3);
    SET Sdni_r = (SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 4);
	SET feedback2 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 4);
	CALL sp_identity_user_score(user_id,Sdni_o,Sdni_r,feedback1,feedback2,identity_user_score,identification_details);
	/*----------------    national_user_score ----------------------- */
	SET Snational = (SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 5);
	SET feedback1 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 5);
	SET Srut = (SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 6);
	SET feedback2 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 6);
	SET Sprovitional = (SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 7);
	SET feedback3 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 7);
    SET Spassport = (SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 8);
	SET feedback4 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 8);

    SET country = (SELECT id from countries where valid);
	CALL sp_nationality_score_user(	country, Ucountry,Udocument, Snational,Srut,Sprovitional, Spassport,
							feedback1,feedback2,feedback3,feedback4, identification_details,nationality_score_user);

	SET identification_score = contact_user_score + identity_user_score + nationality_score_user;
	SET score = score + identification_score;
	/*--------------------------------------------------------------------
					CATEGORY = 2
	guarantor_score	= 	guarantor_confirmation_score +
						guarantor_identification_score
	---------------------------------------------------------------------*/

	/*----------------    guarantor_confirmation_score ----------------------- */
	SET Sconfirmed = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 9);
	SET Sunconfirmed = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 10);
	SET feedback1 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 10);

	SET Sinsurance = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 11);
	SET feedback2 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 11);

	CALL sp_endorsement_score_user(Uconfirmed_collateral,Sconfirmed, Sunconfirmed,Uinsurance, Sinsurance, feedback1,
									feedback2,guarantor_details,guarantor_confirmation_score);

	IF Uconfirmed_collateral = true THEN
		/*----------------    contact_guarantor_score ----------------------- */
		SET Sphone = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 12);
		SET feedback1 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 12);
    	SET Smail = (SELECT points FROM v_scoring WHERE cat_id = 2 AND scoring_id=1  AND det_id = 13);
		SET feedback2 = (SELECT det_feed FROM v_scoring WHERE cat_id = 2 AND scoring_id=1  AND det_id = 13);

		SET Umail = (SELECT mail_verified FROM users WHERE id = Ucollateral_id);
		SET Uphone = (SELECT mail_verified FROM users WHERE id = Ucollateral_id);
		CALL sp_contact_score_user(Uphone,Umail,Sphone,Smail,feedback1,feedback2, contact_guarantor_score,guarantor_details);
		/*-----------------    identity_guarantor_score ---------------------- */
		SET Sdni_o = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 14);
		SET feedback1 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 14);
		SET Sdni_r = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 15);
		SET feedback2 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 15);
		CALL sp_identity_user_score(Ucollateral_id,Sdni_o,Sdni_r,feedback1,feedback2,identity_guarantor_score,guarantor_details);
		/*----------------    national_guarantor_score ----------------------- */
		SET Snational = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 16);
		SET feedback1 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 16);
		SET Srut = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 17);
		SET feedback2 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 17);
		SET Sprovitional = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 18);
		SET feedback3 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 18);

		SET Spassport = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 19);
		SET feedback4 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 19);
		SET country = (SELECT id from countries where valid);

		SET Ucountry = (SELECT country_id FROM users WHERE id = Ucollateral_id);
		SET Udocument = (SELECT document_type FROM users WHERE id = Ucollateral_id);

		CALL sp_nationality_score_user(	country, Ucountry,Udocument, Snational,Srut,Sprovitional, Spassport,
								feedback1,feedback2,feedback3,feedback4, guarantor_details,nationality_guarantor_score);
		SET guarantor_identification_score = contact_guarantor_score + identity_guarantor_score + nationality_guarantor_score;
	END IF;

	SET guarantor_score = guarantor_identification_score + guarantor_confirmation_score;
	SET score = score + guarantor_score;
	/*--------------------------------------------------------------------
					CATEGORY = 3
	socioeconomic_stability_score = job_type_score +
				job_docs_score
	---------------------------------------------------------------------*/
	SET Semploy = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 20);
	SET feedback1 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 20);
	SET SafpE = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 21);
	SET feedback2 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 21);
	SET SdicomE = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 22);
	SET feedback3 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 22);
	SET Sf_set = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 23);
	SET feedback4 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 23);
	SET Ss_set = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 24);
	SET feedback5 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 24);
	SET St_set = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 25);
	SET feedback6 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 25);
	SET Swork = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 26);
	SET feedback7 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 26);

	SET Sfree = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 27);
	SET feedback8 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 27);
	SET SafpF = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 28);
	SET feedback9 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 28);
	SET SdicomF = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id =29 );
	SET feedback10 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id =29 );
	SET SotherF = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 30);
	SET feedback11 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 30);
	SET SsavesF = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 31);
	SET feedback12 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 31);
	SET Slast = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 32);
	SET feedback13 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 32);

	SET Sunemploy = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 33);
	SET feedback14 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 33);
	SET SafpU = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 34);
	SET feedback15 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 34);
	SET SdicomU = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 35);
	SET feedback16 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 35);
	SET SotherU = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 36);
	SET feedback17 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 36);
	SET SsavesU = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 37);
	SET feedback18 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 37);
	/*----------------    job_type_score ----------------------- */
	CALL sp_job_user_score(  Ujob, Semploy, Sfree, Sunemploy,feedback1,feedback8, feedback14, socioeconomic_stability_details,
	 						job_type_score);
	/*----------------    job_docs_score ----------------------- */

	CALL sp_docs_user_score(user_id, Ujob,SafpE,SafpF, SafpU,SdicomE,SdicomF,SdicomU,
					SotherF,SotherU,SsavesF, SsavesU,Sf_set,Ss_set,St_set,Swork, Slast,
					feedback2, feedback3, feedback4, feedback5, feedback6,
					feedback7, feedback9, feedback10, feedback11, feedback12,
					feedback13, feedback15, feedback16, feedback17, feedback18,
					socioeconomic_stability_details, job_docs_score);

	SET socioeconomic_stability_score = job_type_score + job_docs_score;
	SET score = socioeconomic_stability_score + score;
	/*--------------------------------------------------------------------
					CATEGORY = 4
	preferences_conditions_score = preferences_score +
				conditions_score
	---------------------------------------------------------------------*/
	SET Spro_type = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 38);
	SET feedback1 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 38);
	SET Spro_for = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 39);
	SET feedback2 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 39);
	SET Sdate = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 40);
	SET feedback3 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 40);
	SET Spet = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 41);
	SET feedback4 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 41);
	SET Ssmock = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 42);
	SET feedback5 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 42);
	SET Swarranty1 = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 43);
	SET feedback6 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 43);
	SET Swarranty2 = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 44);
	SET feedback7 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 44);
	SET Swarranty3 = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 45);
	SET feedback8 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 45);
	SET Sadvancement1 = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 46);
	SET feedback9 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 46);
	SET Sadvancement2 = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 47);
	SET feedback10 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 47);
	SET Sadvancement3 = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 48);
	SET feedback11 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 48);
	SET Stime1 = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 49);
	SET feedback12 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 49);
	SET Stime2 = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 50);
	SET feedback13 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 50);
	/*----------------    preferences_score ----------------------- */
	CALL sp_preferences_score( Spro_type,Spro_for,
					Sdate,Spet,Ssmock,Upet,Ppet,Usmock,
					Psmock, Upro_type,	Ppro_type, Udate,
					Pdate,	user_id, property_id,
					feedback1, feedback2, feedback3,
					feedback4,feedback5,conditions_and_preferences_details,
					preferences_score
					);

	/*----------------    conditions_score ----------------------- */
	CALL sp_conditions_score(
	 		Uwarranty, Uadvancement, Utime,
     		Swarranty1,	Swarranty2,	Swarranty3,
	 		Sadvancement1, Sadvancement2,	Sadvancement3,
	 		Stime1,	Stime2,
		 	feedback6, feedback7, feedback8,
			feedback9, feedback10, feedback11,
			feedback12, feedback13, conditions_and_preferences_details, conditions_score);

	 SET preferences_conditions_score = preferences_score +	conditions_score;
	 SET score = preferences_conditions_score + score;

	 /*--------------------------------------------------------------------
	 				CATEGORY = 5
		membership_score
	 ---------------------------------------------------------------------*/

	 SET Smembership1 = (SELECT points  FROM v_scoring  WHERE cat_id = 5 AND scoring_id=1  AND det_id = 51);
	 SET feedback1 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 5 AND scoring_id=1  AND det_id = 51);
	 SET Smembership2 = (SELECT points  FROM v_scoring  WHERE cat_id = 5 AND scoring_id=1  AND det_id = 52);
	 SET feedback2 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 5 AND scoring_id=1  AND det_id = 52);
	 SET Smembership3 = (SELECT points  FROM v_scoring  WHERE cat_id = 5 AND scoring_id=1  AND det_id = 53);
	 SET feedback3 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 5 AND scoring_id=1  AND det_id = 53);

	 CALL sp_membership_user_score(user_id, Smembership1, Smembership2, Smembership3,feedback1, feedback2,
	 							feedback3, memberships_details, membership_score);
	 SET score = membership_score +score;
	 /*--------------------------------------------------------------------
	 			   CATEGORY = 6
	    finantial_score
	 ---------------------------------------------------------------------*/
	 SET Sfinantial = (SELECT points  FROM v_scoring  WHERE cat_id = 6 AND scoring_id=1  AND det_id = 54);
	 SET feedback1 = (SELECT det_feed  FROM v_scoring  WHERE cat_id = 6 AND scoring_id=1  AND det_id = 54);
	 CALL sp_finantial_user_score(Ujob, Prent, Uamount, Usave, Uother, Ulast, Sfinantial,
	 						feedback1, finantial_details, finantial_score);
	 SET score = finantial_score + score;
	/*-------------------------------------------------------------*/

/*
contact_user_score,
		identification_score,
		nationality_score_user,
		guarantor_score,
		guarantor_confirmation_score,
		guarantor_identification_score,
		job_type_score,
		job_docs_score,
		socioeconomic_stability_score,
		conditions_score,
		preferences_score,
		membership_score,
		Ujob,Prent, Uamount, Usave, Uother, Ulast, Sfinantial,finantial_score,
		identification_score,
		identity_user_score,
	 	contact_user_score,
		nationality_score_user,
		identification_details,
		identification_feedback,
		guarantor_score,
		guarantor_identification_score,
		guarantor_confirmation_score,
		guarantor_details,
		guarantor_feedback,score,
		preferences_conditions_score, preferences_score,conditions_score,conditions_and_preferences_details,
		socioeconomic_stability_score, job_type_score, job_docs_score, socioeconomic_stability_details, membership_score;
		memberships_details, membership_score,
		finantial_details, finantial_score;

*/
	select
		identification_score, identification_details, identification_feedback,
		guarantor_score, guarantor_details, guarantor_feedback,
		socioeconomic_stability_score, socioeconomic_stability_details, socioeconomic_stability_feedback,
		preferences_conditions_score,conditions_and_preferences_details,conditions_and_preferences_feedback,
		membership_score, memberships_details, memberships_feedback,
		finantial_score, finantial_details,finantial_feedback,
		score;
END
