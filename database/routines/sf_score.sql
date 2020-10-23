CREATE FUNCTION `sf_score` (

user_id int,
property_id int

)
RETURNS INTEGER
BEGIN
	DECLARE done int DEFAULT false;
    /* 			contact function var 				*/
    DECLARE score int(11) DEFAULT 0;
    DECLARE Smail int(11) DEFAULT 0;
    DECLARE Sphone int(11) DEFAULT 0;
	DECLARE Sinsurance int(11) DEFAULT 0;
	DECLARE Uinsurance bool DEFAULT 0;
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
	DECLARE identification_user_score int(11) DEFAULT 0;
	/* 			 job_score vars				*/
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
	DECLARE Slast int(11) DEFAULT 0;
	DECLARE Sunemploy int(11) DEFAULT 0;
	DECLARE SafpU int(11) DEFAULT 0;
	DECLARE SdicomU int(11) DEFAULT 0;
	DECLARE SotherU int(11) DEFAULT 0;
	DECLARE SsavesU int(11) DEFAULT 0;
	DECLARE job_type_score int(11) DEFAULT 0;
	DECLARE job_docs_score int(11) DEFAULT 0;
	DECLARE job_score int(11) DEFAULT 0;
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
     identification_user_score  = 	contact_user_score +
	 								nationality_score_user
	---------------------------------------------------------------	*/

	/*---------------------- contact_user_score---------------------*/
    SET Sphone = (SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 1);
    SET Smail = (SELECT points FROM v_scoring WHERE cat_id = 1 AND scoring_id=1  AND det_id = 2);

    SET contact_user_score = sf_contact_user_score(Uphone,Umail,Sphone,Smail);
    /*-----------------    identity_user_score ---------------------- */
    SET Sdni_o = (SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 3);
    SET Sdni_r = (SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 4);
    SET identity_user_score = sf_identity_user_score(user_id,Sdni_o,Sdni_r);
	/*----------------    national_user_score ----------------------- */
	SET Snational = (SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 5);
	SET Srut = (SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 6);
	SET Sprovitional = (SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 7);
    SET Spassport = (SELECT points  FROM v_scoring  WHERE cat_id = 1 AND scoring_id=1  AND det_id = 8);

    SET country = (SELECT id from countries where valid);
    SET nationality_score_user = sf_nationality_score_user(	country, Ucountry,Udocument, Snational,Srut,Sprovitional, Spassport);
	SET identification_user_score = contact_user_score + identity_user_score + nationality_score_user;
	SET score = score + identification_user_score;
	/*--------------------------------------------------------------------
					CATEGORY = 2
	guarantor_score	= 	guarantor_confirmation_score +
						guarantor_identification_score
	---------------------------------------------------------------------*/

	/*----------------    guarantor_confirmation_score ----------------------- */
	SET Sconfirmed = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 9);
	SET Sunconfirmed = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 10);
	SET Sinsurance = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 11);
	SET guarantor_confirmation_score = sf_endorsement_score_user(Uconfirmed_collateral, Sconfirmed, Sunconfirmed, Uinsurance, Sinsurance);

	IF Uconfirmed_collateral = true THEN
		/*----------------    contact_guarantor_score ----------------------- */
		SET Sphone = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 12);
    	SET Smail = (SELECT points FROM v_scoring WHERE cat_id = 2 AND scoring_id=1  AND det_id = 13);

		SET Umail = (SELECT mail_verified FROM users WHERE id = Ucollateral_id);
		SET Uphone = (SELECT mail_verified FROM users WHERE id = Ucollateral_id);
		SET contact_guarantor_score = sf_contact_user_score(Uphone,Umail,Sphone,Smail);
		/*-----------------    identity_guarantor_score ---------------------- */
		SET Sdni_o = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 14);
		SET Sdni_r = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 15);
		SET identity_guarantor_score = sf_identity_user_score(Ucollateral_id,Sdni_o,Sdni_r);
		/*----------------    national_guarantor_score ----------------------- */
		SET Snational = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 16);
		SET Srut = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 17);
		SET Sprovitional = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 18);

		SET Spassport = (SELECT points  FROM v_scoring  WHERE cat_id = 2 AND scoring_id=1  AND det_id = 19);
		SET country = (SELECT id from countries where valid);

		SET Ucountry = (SELECT country_id FROM users WHERE id = Ucollateral_id);
		SET Udocument = (SELECT document_type FROM users WHERE id = Ucollateral_id);
		SET nationality_guarantor_score = sf_nationality_score_user(country, Ucountry,Udocument, Snational,Srut,Sprovitional, Spassport);
		SET guarantor_identification_score = contact_guarantor_score + identity_guarantor_score + nationality_guarantor_score;
	END IF;

	SET guarantor_score = guarantor_identification_score + guarantor_confirmation_score;
	SET score = score + guarantor_score;
	/*--------------------------------------------------------------------
					CATEGORY = 3
	job_score = job_type_score +
				job_docs_score
	---------------------------------------------------------------------*/
	SET Semploy = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 20);
	SET SafpE = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 21);
	SET SdicomE = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 22);
	SET Sf_set = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 23);
	SET Ss_set = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 24);
	SET St_set = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 25);
	SET Swork = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 26);

	SET Sfree = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 27);
	SET SafpF = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 28);
	SET SdicomF = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id =29 );
	SET SotherF = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 30);
	SET SsavesF = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 31);
	SET Slast = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 32);

	SET Sunemploy = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 33);
	SET SafpU = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 34);
	SET SdicomU = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 35);
	SET SotherU = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 36);
	SET SsavesU = (SELECT points  FROM v_scoring  WHERE cat_id = 3 AND scoring_id=1  AND det_id = 37);
	/*----------------    job_type_score ----------------------- */
	SET job_type_score = sf_job_user_score(  Ujob, Semploy, Sfree, Sunemploy );
	/*----------------    job_docs_score ----------------------- */
	SET job_docs_score = sf_docs_user_score(user_id, Ujob,SafpE,SafpF, SafpU,SdicomE,SdicomF,SdicomU,
					SotherF,SotherU,SsavesF, SsavesU,Sf_set,Ss_set,St_set,Swork, Slast);

	SET job_score = job_type_score + job_docs_score;
	SET score = job_score + score;
	/*--------------------------------------------------------------------
					CATEGORY = 4
	preferences_conditions_score = preferences_score +
				conditions_score
	---------------------------------------------------------------------*/
	SET Spro_type = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 38);
	SET Spro_for = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 39);
	SET Sdate = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 40);
	SET Spet = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 41);
	SET Ssmock = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 42);
	SET Swarranty1 = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 43);
	SET Swarranty2 = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 44);
	SET Swarranty3 = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 45);
	SET Sadvancement1 = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 46);
	SET Sadvancement2 = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 47);
	SET Sadvancement3 = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 48);
	SET Stime1 = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 49);
	SET Stime2 = (SELECT points  FROM v_scoring  WHERE cat_id = 4 AND scoring_id=1  AND det_id = 50);

	/*----------------    preferences_score ----------------------- */
	SET preferences_score = sf_preferences_score( Spro_type,Spro_for,
								Sdate,Spet,Ssmock,Upet,Ppet,Usmock,
								Psmock, Upro_type,	Ppro_type, Udate,
								 Pdate,	user_id, property_id);
	/*----------------    conditions_score ----------------------- */
	SET conditions_score = sf_conditions_score(
	 Uwarranty,
     Uadvancement,
     Utime,
     Swarranty1,
	 Swarranty2,
	 Swarranty3,
	 Sadvancement1,
	 Sadvancement2,
	 Sadvancement3,
	 Stime1,
	 Stime2 );
	 SET preferences_conditions_score = preferences_score +	conditions_score;
	 SET score = preferences_conditions_score + score;

	 /*--------------------------------------------------------------------
	 				CATEGORY = 5
		membership_score
	 ---------------------------------------------------------------------*/

	 SET Smembership1 = (SELECT points  FROM v_scoring  WHERE cat_id = 5 AND scoring_id=1  AND det_id = 51);
	 SET Smembership2 = (SELECT points  FROM v_scoring  WHERE cat_id = 5 AND scoring_id=1  AND det_id = 52);
	 SET Smembership3 = (SELECT points  FROM v_scoring  WHERE cat_id = 5 AND scoring_id=1  AND det_id = 53);

	 SET membership_score = sf_membership_user_score(user_id, Smembership1, Smembership2, Smembership3);

	 SET score = membership_score +score;
	 /*--------------------------------------------------------------------
	 			   CATEGORY = 6
	    finantial_score
	 ---------------------------------------------------------------------*/
	 SET Sfinantial = (SELECT points  FROM v_scoring  WHERE cat_id = 6 AND scoring_id=1  AND det_id = 54);
	 SET finantial_score = sf_finantial_user_score(Ujob, Prent, Uamount, Usave, Uother, Ulast, Sfinantial);
	 SET score = finantial_score+ score;
	/*-------------------------------------------------------------*/

RETURN score;
END
