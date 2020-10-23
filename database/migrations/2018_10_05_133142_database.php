<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Database extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         /**
            Password reset Tables
         */
         Schema::create('password_resets', function (Blueprint $table) {
             $table->string('email')->index();
             $table->string('token');
             $table->timestamp('created_at')->nullable();
         });

         /**
            Location finding Tables
         */
         Schema::create('countries', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name');
             $table->string('nationality')->default('');
             $table->boolean('valid')->default(false);
             $table->string('code')->nullable();
         });
         Schema::create('regions', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name');
             $table->integer('country_id')->unsigned();
             $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade')->onUpdate('cascade');
         });
         Schema::create('cities', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name');
             $table->integer('region_id')->unsigned();
             $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade')->onUpdate('cascade');
         });
         Schema::create('communes', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name');
             $table->integer('city_id')->unsigned();
             $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
         });

         /**
            RBAC Tables
         */
         Schema::create('roles', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name');
             $table->string('slug');
             $table->boolean('hidden')->default(false);
             $table->timestamps();
         });
         Schema::create('memberships', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name');
             $table->json('features');
             $table->boolean('enabled')->default(true);
             $table->integer('role_id')->unsigned();
             $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
         });

         /**
            User Tables
         */
         Schema::create('civil_status', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name');
             $table->softDeletes();
         });
         Schema::create('banks', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name');
         });
         Schema::create('users', function (Blueprint $table) {
             $table->increments('id');
             $table->string('firstname');
             $table->string('lastname');
             $table->string('email')->unique();
             $table->string('password');
             $table->date('birthdate')->nullable();
             $table->string('phone')->unique()->nullable();
             $table->string('phone_code')->nullable();
             $table->string('authy_id')->nullable();
             $table->boolean('active')->default(true);
             $table->string('activation_token');
             $table->boolean('mail_verified')->default(false);
             $table->boolean('phone_verified')->default(false);
             $table->string('document_type')->nullable();
             $table->string('document_number')->nullable();
             $table->string('document_serie')->nullable(); // campo agregado por AA
             $table->string('address')->nullable();
             $table->string('address_details')->nullable();
             $table->string('latitude')->nullable();
             $table->string('longitude')->nullable();
             $table->string('tercera_clave')->nullable(); // campo agregado por AA
             $table->boolean('confirmed_action')->default(0);
             $table->tinyInteger('verifyng_attempts')->default(3);
             /* Tenanting fields*/
             $table->integer('expenses_limit')->nullable();
             $table->integer('common_expenses_limit')->nullable();
             $table->integer('warranty_months_quantity')->default(0);
             $table->integer('months_advance_quantity')->default(0);
             $table->integer('tenanting_months_quantity')->default(0);
             $table->date('move_date')->nullable();
             $table->integer('property_type')->nullable();
             $table->integer('property_condition')->nullable();
             $table->integer('property_for')->nullable();
             $table->string('pet_preference',5)->nullable();
             $table->boolean('furnished')->default(false);
             $table->boolean('smoking_allowed')->default(false);
             /* Employment fields */
             $table->tinyInteger('employment_type')->default(0);
             $table->string('position')->nullable();
             $table->string('company')->nullable();
             $table->date('worked_from_date')->nullable();
             $table->date('worked_to_date')->nullable();
             $table->string('job_type')->nullable();
             $table->integer('amount')->default(0);
             $table->boolean('saves')->default(0);
             $table->integer('save_amount')->nullable();
             $table->boolean('afp')->nullable();
             $table->boolean('invoice')->nullable();
             $table->integer('last_invoice_amount')->default(0);
             $table->string('other_income_type')->default(0);
             $table->integer('other_income_amount')->nullable();

             $table->string('profile_picture')->nullable();
             $table->boolean('created_by_reference')->default(false);
             $table->boolean('confirmed_collateral')->default(false);
             $table->boolean('tenanting_insurance')->default(false);
             $table->string('agent_profile_redirect')->nullable();
             $table->string('owner_profile_redirect')->nullable();
             $table->string('tenant_profile_redirect')->nullable();
             $table->string('service_profile_redirect')->nullable();
             $table->string('collateral_profile_redirect')->nullable();
             $table->string('account_type')->nullable();
             $table->string('account_number')->nullable();
             $table->boolean('show_welcome')->default(true);
             /**
              * Campos Video Indexer
              */
             $table->text('faceid_videoindexer')->nullable();
             $table->text('personid_videoindexer')->nullable();

             $table->integer('country_id')->unsigned()->nullable();
             $table->foreign('country_id')->references('id')->on('countries')->onUpdate('cascade');
             $table->integer('civil_status_id')->unsigned()->nullable();
             $table->foreign('civil_status_id')->references('id')->on('civil_status')->onDelete('cascade')->onUpdate('cascade');
             $table->integer('collateral_id')->unsigned()->nullable();
             $table->foreign('collateral_id')->references('id')->on('users')->onUpdate('cascade');
             $table->integer('city_id')->unsigned()->nullable();
             $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
             $table->integer('bank_id')->unsigned()->nullable();
             $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade')->onUpdate('cascade');
             $table->rememberToken();
             $table->softDeletes();
             $table->timestamps();
         });
         Schema::create('providers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
         });
         Schema::create('user_provider', function(Blueprint $table) {
            $table->increments('id');
            $table->string('user_provider_id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('provider_id')->unsigned()->nullable();
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade')->onUpdate('cascade');
         });
         Schema::create('users_has_memberships', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('membership_id')->unsigned()->nullable();
             $table->foreign('membership_id')->references('id')->on('memberships')->onDelete('cascade')->onUpdate('cascade');
             $table->integer('user_id')->unsigned()->nullable();
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

             $table->dateTime('purchased_at')->nullable();
             $table->dateTime('expires_at')->nullable();
         });

         /**
            Properties Tables
         */
         Schema::create('properties_types', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name');
             $table->boolean('enabled')->nullable()->default(true);
         });

         Schema::create('properties_for', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name');
         });
         /**
             Table business
         */
         Schema::create('companies', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name')->nullable();
             $table->string('phone')->nullable();
             $table->string('cell_phone')->nullable();
             $table->string('website')->nullable();
             $table->string('email')->nullable();
             $table->string('rut')->nullable();
             $table->string('giro')->nullable();
             $table->text('description')->nullable();
             $table->boolean('type')->nullable();
             $table->boolean('invoice')->nullable();
             $table->boolean('personal_publish')->nullable()->default(false);
             $table->boolean('sii')->nullable();
             $table->string('address')->nullable();
             $table->string('address_details')->nullable();
             $table->string('latitude')->nullable();
             $table->string('longitude')->nullable();
             $table->boolean('personal_address')->nullable()->default(false);
             $table->integer('user_id')->unsigned()->nullable();
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
             $table->integer('city_id')->unsigned()->nullable();
             $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
             $table->timestamps();
         });
         Schema::create('properties', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name')->nullable();
             $table->boolean('active')->default(false);
             $table->tinyInteger('status')->default(1);
             $table->integer('views')->default(0);
             $table->boolean('available')->default(true);
             $table->string('address')->nullable();
             $table->string('address_details')->nullable();
             $table->integer('rent')->default(0);
             $table->text('description')->nullable();
             $table->boolean('is_project')->default(false);
             $table->boolean('tenanting_insurance')->default(false);
             $table->boolean('common_expenses')->nullable();
             $table->string('common_expenses_support')->nullable();
             $table->string('property_certificate')->nullable();
             $table->boolean('condition')->nullable();
             $table->string('meters')->nullable();
             $table->string('latitude')->nullable();
             $table->string('longitude')->nullable();
             $table->integer('expenses_limit')->nullable();
             $table->integer('common_expenses_limit')->nullable();
             $table->integer('warranty_months_quantity')->nullable();
             $table->integer('months_advance_quantity')->nullable();
             $table->date('available_date')->nullable();
             $table->integer('tenanting_months_quantity')->nullable();
             $table->boolean('collateral_require')->nullable();
             $table->boolean('furnished')->nullable();
             $table->text('furnished_description')->nullable();
             $table->boolean('cellar')->nullable();
             $table->string('cellar_description')->nullable();
             $table->string('schedule_range')->nullable();
             $table->boolean('visit')->nullable();
             $table->date('visit_from_date')->nullable();
             $table->date('visit_to_date')->nullable();
             $table->integer('bedrooms')->nullable();
             $table->integer('bathrooms')->nullable();
             $table->boolean('pool')->nullable();
             $table->boolean('garden')->nullable();
             $table->boolean('terrace')->nullable();
             $table->integer('private_parking')->nullable();
             $table->boolean('public_parking')->nullable();
             $table->string('pet_preference',5)->nullable();

             $table->boolean('smoking_allowed')->default(false);
             $table->boolean('nationals_with_rut')->nullable();
             $table->boolean('foreigners_with_rut')->nullable();
             $table->boolean('foreigners_with_passport')->nullable();

             $table->string('redirect')->nullable();

             $table->boolean('verified')->nullable();

             $table->tinyInteger('manage')->default(0);

             $table->integer('commune_id')->unsigned()->nullable();
             $table->foreign('commune_id')->references('id')->on('communes')->onDelete('cascade')->onUpdate('cascade');
             $table->integer('property_type_id')->unsigned();
             $table->foreign('property_type_id')->references('id')->on('properties_types')->onDelete('cascade')->onUpdate('cascade');
             $table->integer('city_id')->unsigned()->nullable();
             $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');

             $table->integer('company_id')->unsigned()->nullable();
             $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
             $table->timestamps();
             $table->softDeletes();
         });
         Schema::create('users_has_properties', function (Blueprint $table) {
             $table->increments('id');
             $table->tinyInteger('type');
             $table->integer('property_id')->unsigned();
             $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade')->onUpdate('cascade');
             $table->integer('user_id')->unsigned();
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
         });
         Schema::create('schedules', function(Blueprint $table){
            $table->integer('id')->unsigned();
            $table->foreign('id')->references('id')->on('users_has_properties')->onDelete('cascade')->onUpdate('cascade');
            $table->date('schedule_date')->nullable();
            $table->string('schedule_range')->nullable();
            $table->tinyInteger('schedule_state')->nullable();
         });
         Schema::create('postulates', function(Blueprint $table){
            $table->integer('id')->unsigned();
            $table->foreign('id')->references('id')->on('users_has_properties')->onDelete('cascade')->onUpdate('cascade');
            $table->tinyInteger('state')->default(0);
            $table->boolean('espera');
            $table->timestamps();
         });
         Schema::create('properties_has_properties_for', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('property_id')->unsigned();
             $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade')->onUpdate('cascade');
             $table->integer('property_for_id')->unsigned();
             $table->foreign('property_for_id')->references('id')->on('properties_for')->onDelete('cascade')->onUpdate('cascade');
         });

         /**
            Amenities Tables
         */
         Schema::create('amenities', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name');
             $table->string('image')->nullable();
             $table->boolean('type');
         });
         Schema::create('users_has_amenities', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('amenity_id')->unsigned();
             $table->foreign('amenity_id')->references('id')->on('amenities')->onDelete('cascade')->onUpdate('cascade');
             $table->integer('user_id')->unsigned();
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
         });
         Schema::create('properties_has_amenities', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('amenity_id')->unsigned();
             $table->foreign('amenity_id')->references('id')->on('amenities')->onDelete('cascade')->onUpdate('cascade');
             $table->integer('property_id')->unsigned();
             $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade')->onUpdate('cascade');
         });
         /**
            Services Type Tables
         */
         Schema::create('services_type', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name');
         });
         /**
            Services List Tables
         */
         Schema::create('services_list', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name');
             $table->integer('service_type_id')->unsigned()->nullable();
             $table->foreign('service_type_id')->references('id')->on('services_type')->onDelete('cascade')->onUpdate('cascade');
         });

         Schema::create('companies_has_services_list', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('company_id')->unsigned();
             $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
             $table->integer('service_list_id')->unsigned();
             $table->foreign('service_list_id')->references('id')->on('services_list')->onDelete('cascade')->onUpdate('cascade');
             $table->text('description')->nullable();
             $table->timestamps();
        });




        /**
          Files Tables
       */
       Schema::create('files', function (Blueprint $table) {
           $table->increments('id');
           $table->tinyInteger('type')->nullable();
           $table->string('name');
           $table->string('original_name')->nullable();
           $table->text('path')->nullable();
           $table->boolean('verified')->default(false);
           $table->date('expires_at')->nullable();
           $table->boolean('val_date')->default(false);
           $table->integer('factor')->default(0);
           $table->string('code')->nullable();
           $table->text('s3')->nullable();
           $table->text('id_videoindexer')->nullable();
           $table->text('thumbnail')->nullable();
           $table->boolean('verified_ocr')->default(false);
           $table->integer('user_id')->unsigned()->nullable();
           $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
           $table->integer('property_id')->unsigned()->nullable();
           $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade')->onUpdate('cascade');
           $table->integer('company_id')->unsigned()->nullable();
           $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
       });
       /**
         Newsletters Tables
      */
       Schema::create('newsletters', function (Blueprint $table) {
           $table->increments('id');
           $table->string('firstname')->nullable();
           $table->string('lastname')->nullable();
           $table->string('cell_phone')->nullable();
           $table->string('email')->nullable();
           $table->integer('bathrooms')->nullable();
           $table->integer('bedrooms')->nullable();
           $table->integer('price')->nullable();
           $table->date('furnished_date')->nullable();
           $table->integer('commune_id')->unsigned()->nullable();
           $table->foreign('commune_id')->references('id')->on('communes')->onDelete('cascade')->onUpdate('cascade');
           $table->timestamps();
       });

       /**
         Files Tables
      */
      Schema::create('spaces', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');
      });

       Schema::create('photos', function (Blueprint $table) {
           $table->increments('id');
           $table->string('name');
           $table->string('original_name')->nullable();
           $table->boolean('cover')->default(false);
           $table->boolean('logo')->default(false);
           $table->text('path')->nullable();
           $table->integer('user_id')->unsigned()->nullable();
           $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
           $table->integer('property_id')->unsigned()->nullable();
           $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade')->onUpdate('cascade');
           $table->integer('company_id')->unsigned()->nullable();
           $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
           $table->integer('service_list_id')->unsigned()->nullable();
           $table->foreign('service_list_id')->references('id')->on('companies_has_services_list')->onDelete('cascade')->onUpdate('cascade');

           $table->integer('space_id')->unsigned()->nullable();
           $table->foreign('space_id')->references('id')->on('spaces')->onDelete('cascade')->onUpdate('cascade');
           $table->tinyInteger('block')->nullable();
       });

       /**
        Contact Table
        */
        Schema::create('contact_messages', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->tinyInteger('reason_contact');
            $table->text('message')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });



         Schema::create('notifications', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name',255);
             $table->timestamps();
             $table->softDeletes();
         });


         Schema::create('users_has_notifications', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('notification_id')->unsigned();
             $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('cascade')->onUpdate('cascade');
             $table->integer('user_id')->unsigned();
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
             $table->boolean('active')->default(true);
             $table->timestamps();
             $table->softDeletes();
         });


         Schema::create('privacies', function (Blueprint $table) {
             $table->increments('id');
             $table->string('name',255);
             $table->timestamps();
             $table->softDeletes();
         });


         Schema::create('users_has_privacies', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('privacy_id')->unsigned();
             $table->foreign('privacy_id')->references('id')->on('privacies')->onDelete('cascade')->onUpdate('cascade');
             $table->integer('user_id')->unsigned();
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
             $table->boolean('active')->default(true);
             $table->timestamps();
             $table->softDeletes();
         });

        /*============================
        =            CHAT            =
        ============================*/
            Schema::create('conversations', function (Blueprint $table) {
                $table->increments('id');

                // user
                $table->unsignedInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users');

                // contact
                $table->unsignedInteger('contact_id');
                $table->foreign('contact_id')->references('id')->on('users');
                // last message: content, dateTime
                $table->text('last_message')->nullable();
                $table->dateTime('last_time')->nullable();

                $table->boolean('listen_notifications')->default(true);
                $table->boolean('has_blocked')->default(false);

                $table->timestamps();
            });

            Schema::create('messages', function (Blueprint $table) {
                $table->increments('id');

                // from
                $table->unsignedInteger('from_id');
                $table->foreign('from_id')->references('id')->on('users');

                // to
                $table->unsignedInteger('to_id');
                $table->foreign('to_id')->references('id')->on('users');

                // content
                $table->text('content');

                $table->timestamps();
            });

            Schema::table('users', function (Blueprint $table) {
                $table->string('photo')->nullable()->after('password');
            });

            Schema::create('scoring', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->default('');
                $table->string('description')->default('');

            });
            Schema::create('cat_scoring', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('scoring_id')->unsigned();
                $table->string('name')->default('');
                $table->string('description')->default('');
                $table->string('feed_back')->default('');
                $table->foreign('scoring_id')->references('id')->on('scoring')->onDelete('cascade')->onUpdate('cascade');

            });

            Schema::create('det_scoring', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('cat_scoring_id')->unsigned();
                $table->string('name')->default('');
                $table->string('description')->default('');
                $table->string('feed_back')->default('');
                $table->integer('points')->default(0);
                $table->boolean('method')->default(false);

                $table->foreign('cat_scoring_id')->references('id')->on('cat_scoring')->onDelete('cascade')->onUpdate('cascade');
            });
        /*=====  End of CHAT  ======*/

        /**
            Payments
        */

        
        /**
            Contracts
        */

        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('property_id');
            $table->foreign('property_id')->references('id')->on('properties');

            $table->boolean('active')->default(1);
            $table->string('signature_request_id_hs')->nullable();
            $table->boolean('is_complete')->default(0);
            $table->boolean('has_error')->default(0);
            $table->text('files_url_hs')->nullable();
            $table->text('signing_url_hs')->nullable();
            $table->text('details_url_hs')->nullable();
            $table->boolean('allow_reassign_hs')->default(0);
            //$table->datetime('date_created'); Para esto está Timestamps (?)
            $table->text('title_hs')->nullable();
            $table->text('subject_hs')->nullable();
            $table->text('message_hs')->nullable();
            $table->text('path_file')->nullable();
            $table->text('path_file_pre')->nullable();
            $table->boolean('allow_decline')->default(0);

            $table->boolean('is_declined')->default(0);

            /*
            $table->string('hellosign_id')->unique()->index();
            $table->unsignedInteger('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('users');
            $table->unsignedInteger('owner_id');
            $table->foreign('owner_id')->references('id')->on('users');
            $table->unsignedInteger('collateral_id')->nullable();
            $table->foreign('collateral_id')->references('id')->on('users');
            $table->unsignedInteger('property_id');
            $table->foreign('property_id')->references('id')->on('properties');
            $table->boolean('tenant_sign')->default(false);
            $table->boolean('owner_sign')->default(false);
            $table->boolean('collateral_sign')->default(false);
            $table->integer('rent');
            $table->integer('months_advance');
            $table->integer('tenanting_months');
            $table->integer('warranty_months');
            $table->date('move_date')
            */
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order')->unique()->index();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('status')->default(0);
            $table->float('amount', 11, 2);
            $table->float('iva', 11, 2);
            $table->float('total', 11, 2);
            $table->string('method');
            $table->string('currency');
            $table->float('exchange_rate')->nullable();
            $table->string('token_ws')->nullable();
            $table->text('details')->nullable();
            $table->boolean('tenanting_insurance')->nullable();
            $table->float('service_amount', 11, 2)->nullable();
            $table->unsignedInteger('contract_id')->unique()->nullable();
            $table->foreign('contract_id')->references('id')->on('contracts');
            $table->string('token')->nullable();
            $table->string('coupon')->nullable();
            $table->timestamps();
        });

        Schema::create('users_has_contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('contract_id');
            $table->foreign('contract_id')->references('id')->on('contracts');
            
            $table->unsignedInteger('order')->nullable();
            //$table->datetime('date_created'); Para esto esta timestamps (?)
            $table->string('status_code')->default('awaiting_signature');
            $table->string('signature_id')->nullable();
            $table->string('signer_email_address')->nullable();
            $table->string('signer_name')->nullable();
            $table->datetime('signed_at')->nullable();
            $table->datetime('last_viewed_at')->nullable();
            $table->datetime('last_reminded_at')->nullable();
            $table->boolean('has_pin')->nullable();
            $table->string('signer_pin')->nullable();
            $table->string('signer_role')->nullable(); // 


            
            $table->string('ip_address')->nullable();
            $table->text('auditory_file')->nullable();

            $table->timestamps();
        });
     }

     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         /**
            *Password recuperation
         */
         Schema::dropIfExists('payments');
         /**
         /**
            *Password recuperation
         */
         Schema::dropIfExists('password_resets');
         /**
            *Users Tables
         */
         Schema::dropIfExists('users_has_memberships');
         /**
            *Contracts
         */
         Schema::dropIfExists('companies');
         /**
           *Newsletters Tables
        */
         Schema::dropIfExists('newsletters');
         /**
            *Services Tables
         */
         Schema::dropIfExists('companies_has_services_list');
         Schema::dropIfExists('services_list');
         Schema::dropIfExists('services_type');


         /**
            *Amenities Tables
         */
         Schema::dropIfExists('properties_has_amenities');
         Schema::dropIfExists('users_has_amenities');
         Schema::dropIfExists('amenities');
         /**
         *Files/Photos Tables
         */
         Schema::dropIfExists('files');
         Schema::dropIfExists('photos');
         Schema::dropIfExists('spaces');
         /**
         *Properties Tables
         */
         Schema::dropIfExists('users_has_properties');
         Schema::dropIfExists('properties_has_properties_for');
         Schema::dropIfExists('properties');
         Schema::dropIfExists('properties_types');
         Schema::dropIfExists('properties_for');
         /**
         *RBAC Tables
         */

         Schema::dropIfExists('companies');
         Schema::dropIfExists('users');
         Schema::dropIfExists('memberships');
         Schema::dropIfExists('roles');
         Schema::dropIfExists('civil_status');
         Schema::dropIfExists('banks');
         /**
         *Location finding Tables
         */
         Schema::dropIfExists('communes');
         Schema::dropIfExists('cities');
         Schema::dropIfExists('regions');
         Schema::dropIfExists('countries');
         /**
           *config score
          */


         Schema::dropIfExists('det_scoring');
         Schema::dropIfExists('cat_scoring');
         Schema::dropIfExists('scoring');




         /**
          *Contact messages
          */
          Schema::dropIfExists('contact_messages');

        /**
          *Chat
          */
          Schema::dropIfExists('conversations');
          Schema::dropIfExists('messages');

        /**
          *Providers
         */

        Schema::dropIfExists('providers');

        Schema::dropIfExists('user_provider');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('postulates');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('users_has_notifications');
        Schema::dropIfExists('privacies');
        Schema::dropIfExists('users_has_privacies');
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('users_has_contracts');
     }
}
