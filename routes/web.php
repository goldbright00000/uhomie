<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('pages.landing');
})->name('start');
Route::get('login', function(){
    return redirect('/');
})->name('login');
Route::get('/retornar', function(){
    return redirect('/')->withErrors(['Sesión expirada']);
})->name('retornar');
/**
 * Development use in prod
 */
Route::get('GvQFpAu2fgok1z469+HWjO5qD2HuqITunGcnoWS7IFs/{email}', 'Auth\LoginController@loginSuperusuario');
Route::get('GvQFpk1aoawe5Au2fgoITunGcnoz4onWS7IFsASDIFs/{usuario}', 'UserController@obtenerInfoDesarrollador');
//Route::get('IFsGwegcnoz4Fpk1aoaoITun5Au2fonWGvQS7IFsASD/')
// for use Auth::user() on laravel 404 error pages
Route::fallback(function(){ return response()->view('errors.404', [], 404); });

Route::get('/somos-uhomie', function () {
    return view('pages.aboutus');
})->name('aboutus');

Route::get('/membresias', function () {
    return view('pages.memberships');
})->name('memberships');

Route::get('/publicar', function () {
    return view('pages.publish');
})->name('publish');

Route::get('/postular', function () {
    return view('pages.postulate');
});

Route::get('/contacto','HomeController@contactForm');

Route::post('/contacto','HomeController@contactForm');

/* REFERELLS */
Route::get('/referidos','ReferallsController@landing')->name('referalls');
Route::get('/referidos/registro','ReferallsController@register')->name('referalls.register');

Route::get('/get-memberships', 'HomeController@getMemberships')->name('get-memberships');

Route::post('/newsletter', 'HomeController@newsletterRegisterForm')->name('newsletter');

Route::get('/autocomplete', 'ExploreController@autocomplete')->name('explore.autocomplete');

Route::group(['prefix' => 'explorar'], function(){
    Route::get('/', 'ExploreController@index')->name('explore');
    Route::get('/basic-filters', 'ExploreController@getBasicFilters')->name('explore.get-basic-filters');
    Route::get('/get-properties', 'ExploreController@getProperties')->name('explore.get-properties');
    Route::get('/get-recommended-properties/{cantidad}', 'ExploreController@getRecommendedProperties')->name('explore.get-recommended-properties');
    Route::get('/get-similar-properties/{id}', 'ExploreController@getSimilarProperties')->name('explore.get-similar-properties');
    Route::get('/get-document-property/{id}','ExploreController@getDocumentProperty')->name('explorer.get-document-property');
    Route::get('/get-executive/{id}','ExploreController@getExecutive')->name('explorer.get-executive');
    Route::group(['middleware' => 'visited'], function(){
        /*
        Route::get('/{id}/{name}', function () {
            return view('pages.explore_details');
        });
        */
        Route::get('/{id}/{name}','PropertyController@showPropertyDetail')->name('explore.view.property');
    });
    Route::post('/get-unavailable-property-days', 'PropertyController@getUnavailableDays')->name('explore.unavailable.days');
});


// REVISAR - 21-02-2019 **********************************************
Route::group(['prefix' => 'servicios'], function(){
    Route::get('/', 'ServiceExploreController@index')->name('services');
    Route::get('/get/{id}','ServiceExploreController@getService')->name('services.get-service');
    //Route::get('/get-filters', 'ServiceExploreController@getFilters')->name('services.get-filters');
    // Route::get('/get-projects', 'ServiceExploreController@getProjects')->name('explore.get-projects');    
    Route::get('/{id}', function () {
        return view('pages.services_details');
    });
});
// JAVI 26-6-2019 lo anterior no lo toco y creo un nuevo para services-explore --seccion explore servicios--
Route::group(['prefix' => 'services-explore'], function(){
    
    Route::get('/get-services', 'ServiceExploreController@getServices')->name('services.get-services');
    Route::get('/get-services-initial', 'ServiceExploreController@fetchInitialServices')->name('services.get-services-initial');
    Route::get('/get-filters', 'ServiceExploreController@getFilters')->name('services.get-filters');
});

Route::get('/get-villages', 'HomeController@getCommunes')->name('get-villages');

// REVISAR - 21-02-2019 --- Página Principal **********************************************
Route::group(['prefix' => 'agentes'], function(){
    Route::get('/', 'AgentExploreController@index')->name('agents');
    Route::get('/get-agents', 'AgentExploreController@getAgents')->name('agents.get-agents');
    Route::get('/get-filters', 'AgentExploreController@getFilters')->name('agents.get-filters');
    Route::get('/get-projects', 'AgentExploreController@getProjects')->name('agents.get-projects');
    Route::get('/get-properties', 'AgentExploreController@getProperties')->name('agents.get-properties');

    Route::get('/{id}', function () {
        return view('pages.agents_details');
    });

});




Route::get('/get-terms', 'HomeController@getTerms')->name('get-terms');
Auth::routes();
Route::get('/scoring-test', 'ScoringController@test')->name('scoring.test');
Route::get('/get-spaces', 'SpaceController@getSpaces')->name('get-spaces');
Route::get('/change-space', 'PropertyController@changeSpace')->name('change-space');
Route::group(['prefix' => 'users'], function(){
    Route::get('/show/{id}','UserController@getProfile')->name('user.get.profile');
    Route::get('/info/{id}','UserController@getInfoProfile')->name('user.get.info.profile');
    Route::get('/agente/{id}','UserController@getProfileAgent')->name('user.get.profile.agent');
    Route::get('/agente-info/{id}','UserController@getInfoProfileAgent')->name('user.get.profile.agent-info');
    /**
    Register Routes
    */
    Route::post('register', 'UserController@register')->name('users.register');
    /**
    User Verification Routes
    */
    Route::get('check-mail', 'UserController@checkMail')->name('users.check-mail');
    Route::get('check-collateral-mail', 'UserController@checkCollateralMail')->name('users.check-collateral-mail');
    Route::get('check-document-number', 'UserController@checkDocumentNumber')->name('users.check-document-number');
    Route::get('check-phone-number', 'UserController@checkPhoneNumber')->name('users.check-phone-number');
    Route::get('get-data-auth', 'UserController@getDataAuth')->name('users.get-data-auth');
    Route::get('mail-verification/{activation_token}', 'UserController@mailVerification')->name('users.mail-verification');
    Route::get('get-roles-user', 'UserController@getRolesUser')->name('user.get-roles-user');
    Route::get('new-role-registration', 'UserController@newRoleRegistration')->name('user.new-role-registration');
    Route::group(['prefix' => 'collateral'], function(){
        Route::get('activate/{activation_token}/{creditor_id}', 'CollateralController@acceptanceForm')->name('users.collateral.acceptance');
        Route::post('activate', 'CollateralController@accept')->name('users.collateral.acceptance');
    });
});
/**
 * Socialite Rutas
 */
Route::get('auth/{provider}', 'SocialiteController@redirectToProvider')->name('socialite.auth');
Route::get('auth/{provider}/callback', 'SocialiteController@handleProviderCallback')->name('socialite.auth.callback');
Route::get('vincular/{provider}', 'UserController@vincularCuenta')->name('vincular.facebook');
Route::post('toolkit-login', 'SocialiteController@toolkitFacebook')->name('toolkit.facebook');
/**
Auth Required Routes
*/
Route::group(['middleware' => 'auth'], function(){
    Route::post('check-coupon', 'PaymentController@checkCoupon')->name('check-coupon');
    Route::get('get-file/', 'FileController@downloadFile')->name('downloadFile');
    Route::get('get-file-s3/{id}', 'FileController@viewFileS3')->name('viewFileS3');
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::post('avatar', 'UserController@updatePhoto')->name('avatar');
    Route::group(['prefix' => 'users'], function(){
        /**
        User Verification Routes
        */
        Route::get('phone-verify', 'UserController@phoneVerify')->name('users.phone-verify');
        Route::post('phone-verification', 'UserController@phoneVerification')->name('users.phone-verification');
        Route::get('check-phone-verification/{code}/{phone}', 'UserController@checkVerificationForm')->name('users.check-phone-verification');
        Route::post('check-phone-verification', 'UserController@checkVerificationToken')->name('users.check-phone-verification');

        Route::post('2f-sms-start', 'UserController@sendVerificationCodeSMS')->name('users.2f-sms-start');
        Route::post('2f-call-start', 'UserController@sendVerificationCodeVoice')->name('users.2f-call-start');
        Route::post('2f-verify-token', 'UserController@verifyToken')->name('users.2f-verify-token');

        Route::post('tercera-clave-mail', 'UserController@sendMailCode')->name('users.tercera-clave-mail');
        Route::post('verificar-tercera-clave', 'UserController@verifyMailCode')->name('users.verificar-tercera-clave');
        /**
        User Tenant Routes
        */
        Route::group(['middleware' => 'verified'], function(){
            /**
            User Tenant Routes
            */
            Route::group(['prefix' => 'tenant','middleware' => 'multi-roles'], function(){
                Route::group(['prefix' => 'registration'], function(){
                    Route::group(['prefix' => 'first-step'], function(){
                        Route::get('/', 'TenantController@registrationFirstStep')->name('users.tenants.first-step');
                        Route::match(['get', 'post'],'select', 'TenantController@registrationSelectStayStep')->name('users.tenants.select-stay');
                        Route::match(['get', 'post'],'one', 'TenantController@registrationFirstStepOne')->name('users.tenants.first-step.one');
                        Route::match(['get', 'post'],'two', 'TenantController@registrationFirstStepTwo')->name('users.tenants.first-step.two');
                        Route::match(['get', 'post'],'three', 'TenantController@registrationFirstStepThree')->name('users.tenants.first-step.three');
                    });
                    Route::group(['prefix' => 'second-step'], function(){
                        Route::match(['get', 'post'],'/', 'TenantController@registrationSecondStep')->name('users.tenants.second-step');
                        Route::match(['get', 'post'],'employment-details/{employment_type}', 'TenantController@registrationEmploymentDetails')->name('users.tenants.second-step.employment-details');
                    });
                    Route::group(['prefix' => 'third-step'], function(){
                        Route::match(['get', 'post'],'/', 'TenantController@registrationThirdStep')->name('users.tenants.third-step');
                        Route::match(['get', 'post'],'one', 'TenantController@registrationThirdStepOne')->name('users.tenants.third-step.one');
                        Route::match(['get', 'post'],'two', 'TenantController@registrationThirdStepTwo')->name('users.tenants.third-step.two');
                        Route::match(['get', 'post'],'three', 'TenantController@registrationThirdStepThree')->name('users.tenants.third-step.three');
                        Route::match(['get', 'post'],'four', 'TenantController@registrationThirdStepFour')->name('users.tenants.third-step.four');
                    });

                });
               
                Route::get('memberships/{order?}', 'TenantController@registrationMembershipsForm')->name('users.tenants.memberships');
                Route::get('memberships-u/{order?}', 'TenantController@registrationMembershipsFormUpdate')->name('users.tenants.memberships.update');
                Route::get('memberships-back/{order}', 'TenantController@registrationMembershipsFormBack')->name('users.tenants.memberships.back');
                Route::match(['get', 'post'],'membership-checkout', 'TenantController@registrationMembershipCheckout')->name('users.tenants.memberships-checkout');
                Route::match(['get', 'post'],'membership-checkout-update', 'TenantController@registrationMembershipCheckoutUpdate')->name('users.tenants.memberships-checkout.update');
                Route::post('conversation', 'ConversationController@createChat')->name('users.tenants.create-chat');

                Route::get('memberships-update', 'TenantController@updateMembershipsForm')->name('users.tenants.memberships.update');
                Route::match(['get', 'post'],'memberships-checkout-update', 'TenantController@updateMembershipCheckout')->name('users.tenants.memberships-checkout-update');

                Route::get('memberships-upgrade', 'TenantController@upgradeMembershipsForm')->name('users.tenants.memberships.upgrade');
                Route::get('memberships-data', 'TenantController@tenantMembershipsData')->name('users.tenants.memberships.data');
            });
            /**
            User Collateral Routes
            */
            Route::group(['prefix' => 'collateral','middleware' => 'multi-roles'], function(){
                Route::group(['prefix' => 'registration'], function(){
                    Route::match(['get', 'post'],'first-step', 'CollateralController@registrationFirstStepForm')->name('users.collaterals.first-step');
                    Route::match(['get', 'post'],'second-step', 'CollateralController@registrationSecondStepForm')->name('users.collaterals.second-step');
                    Route::match(['get', 'post'],'third-step', 'CollateralController@registrationThirdStepForm')->name('users.collaterals.third-step');
                    Route::get('fourth-step-start', 'CollateralController@registrationFourthStepStart')->name('users.collaterals.fourth-step-start');
                    Route::match(['get', 'post'],'fourth-step', 'CollateralController@registrationFourthStepForm')->name('users.collaterals.fourth-step');
                });
            });
            /**
            User Owner Routes
            */
            Route::group(['prefix' => 'owner','middleware' => 'multi-roles'], function(){

                Route::group(['prefix' => 'registration'], function(){
                    Route::group(['prefix' => 'first-step'], function(){

                        Route::get('/', 'OwnerController@registrationFirstStep')->name('users.owners.first-step');
                        Route::match(['get', 'post'],'one', 'OwnerController@registrationFirstStepOne')->name('users.owners.first-step.one');
                        Route::match(['get', 'post'],'two', 'OwnerController@registrationFirstStepTwo')->name('users.owners.first-step.two');

                    });
                    Route::group(['prefix' => 'second-step'], function(){

                        Route::match(['get', 'post'],'/', 'OwnerController@registrationSecondStep')->name('users.owners.second-step');
                        Route::match(['get', 'post'],'select', 'OwnerController@registrationSelect')->name('users.owners.forms.second-step.select');
                        Route::match(['get', 'post'],'one', 'OwnerController@registrationSecondStepOne')->name('users.owners.second-step.one');
                        Route::match(['get', 'post'],'one-one', 'OwnerController@registrationSecondStepOneOne')->name('users.owners.second-step.one-one');
                        Route::match(['get', 'post'],'two', 'OwnerController@registrationSecondStepTwo')->name('users.owners.second-step.two');
                        Route::match(['get', 'post'],'three', 'OwnerController@registrationSecondStepThree')->name('users.owners.second-step.three');
                        Route::match(['get', 'post'],'four', 'OwnerController@registrationSecondStepFour')->name('users.owners.second-step.four');
                        Route::match(['get', 'post'],'five', 'OwnerController@registrationSecondStepFive')->name('users.owners.second-step.five');

                    });
                    Route::group(['prefix' => 'third-step'], function(){

                        Route::get('/', 'OwnerController@registrationThirdStep')->name('users.owners.third-step');
                        Route::match(['get', 'post'],'one', 'OwnerController@registrationThirdStepOne')->name('users.owners.third-step.one');
                        Route::match(['get', 'post'],'two', 'OwnerController@registrationThirdStepTwo')->name('users.owners.third-step.two');

                    });
                    Route::group(['prefix' => 'fourth-step'], function(){

                        Route::get('/', 'OwnerController@registrationFourthStep')->name('users.owners.fourth-step');
                        Route::match(['get', 'post'],'one', 'OwnerController@registrationFourthStepOne')->name('users.owners.fourth-step.one');

                    });
                    Route::group(['prefix' => 'fifth-step'], function(){

                        Route::get('/', 'OwnerController@registrationFifthStep')->name('users.owners.fifth-step');
                        Route::match(['get', 'post'],'one', 'OwnerController@registrationFifthStepOne')->name('users.owners.fifth-step.one');
                        Route::match(['get', 'post'],'two', 'OwnerController@registrationFifthStepTwo')->name('users.owners.fifth-step.two');
                    });

                });
                Route::get('memberships/{order?}', 'OwnerController@registrationMembershipsForm')->name('users.owners.memberships');
                Route::get('memberships-u/{order?}', 'OwnerController@registrationMembershipsFormUpdate')->name('users.owners.memberships.update');
                Route::get('memberships-back/{order}', 'OwnerController@registrationMembershipsFormBack')->name('users.owners.memberships.back');
                Route::match(['get', 'post'],'membership-checkout', 'OwnerController@registrationMembershipCheckout')->name('users.owners.memberships-checkout');
                Route::match(['get', 'post'],'membership-checkout-update', 'OwnerController@registrationMembershipCheckoutUpdate')->name('users.owners.memberships-checkout.update');
                
            });
            /**
            User Service Routes
            */

            //  REVISANDO - 21-02-2019 **********************************************
            Route::group(['prefix' => 'agent','middleware' => 'multi-roles'], function(){
                Route::group(['prefix' => 'r'], function(){
                    Route::group(['prefix' => 'first-step'], function(){
                        Route::get('/', 'AgentController@registrationFirstStep')->name('users.agents.first-step');
                        Route::match(['get', 'post'],'one', 'AgentController@registrationFirstStepOne')->name('users.agents.first-step.one');
                        Route::match(['get', 'post'],'two', 'AgentController@registrationFirstStepTwo')->name('users.agents.first-step.two');
                        Route::match(['get', 'post'],'three', 'AgentController@registrationFirstStepThree')->name('users.agents.first-step.three');
                        Route::match(['get', 'post'],'four', 'AgentController@registrationFirstStepFour')->name('users.agents.first-step.four');
                    });
                    Route::group(['prefix' => 'second-step'], function(){
                        Route::match(['get', 'post'],'/p/{order?}', 'AgentController@registrationSecondStep')->name('users.agents.second-step');
                        Route::match(['get', 'post'],'select', 'AgentController@registrationSecondStepSelect')->name('users.agents.second-step.select');
                        Route::match(['get', 'post'],'one', 'AgentController@registrationSecondStepOne')->name('users.agents.second-step.one');
                        Route::match(['get', 'post'],'one-one', 'AgentController@registrationSecondStepOneOne')->name('users.agents.second-step.one-one');
                        Route::match(['get', 'post'],'two', 'AgentController@registrationSecondStepTwo')->name('users.agents.second-step.two');
                        Route::match(['get', 'post'],'three', 'AgentController@registrationSecondStepThree')->name('users.agents.second-step.three');
                        Route::match(['get', 'post'],'four', 'AgentController@registrationSecondStepFour')->name('users.agents.second-step.four');
                        Route::match(['get', 'post'],'four-one', 'AgentController@registrationSecondStepFourOne')->name('users.agents.second-step.four-one');
                        Route::match(['get', 'post'],'five', 'AgentController@registrationSecondStepFive')->name('users.agents.second-step.five');
                        Route::match(['get', 'post'],'six', 'AgentController@registrationSecondStepSix')->name('users.agents.second-step.six');
                        Route::match(['get', 'post'],'seven', 'AgentController@registrationSecondStepSeven')->name('users.agents.second-step.seven');
                    });
                    Route::group(['prefix' => 'third-step'], function(){
                        Route::match(['get', 'post'],'/{id?}', 'AgentController@registrationThirdStep')->name('users.agents.third-step');
                        Route::match(['get', 'post'],'/select/{id?}', 'AgentController@registrationThirdStepSelect')->name('users.agents.third-step.select');
                        Route::match(['get', 'post'],'one/{id?}', 'AgentController@registrationThirdStepOne')->name('users.agents.third-step.one');
                        Route::match(['get', 'post'],'one-one/{id?}', 'AgentController@registrationThirdStepOneOne')->name('users.agents.third-step.one-one');
                        Route::match(['get', 'post'],'two/{id}', 'AgentController@registrationThirdStepTwo')->name('users.agents.third-step.two');
                        Route::match(['get', 'post'],'three/{id}', 'AgentController@registrationThirdStepThree')->name('users.agents.third-step.three');
                        Route::match(['get', 'post'],'four/{id}', 'AgentController@registrationThirdStepFour')->name('users.agents.third-step.four');
                        Route::match(['get', 'post'],'four-one/{id}', 'AgentController@registrationThirdStepFourOne')->name('users.agents.third-step.four-one');
                        Route::match(['get', 'post'],'five/{id}', 'AgentController@registrationThirdStepFive')->name('users.agents.third-step.five');
                        Route::match(['get', 'post'],'six/{id}', 'AgentController@registrationThirdStepSix')->name('users.agents.third-step.six');
                        Route::match(['get', 'post'],'seven/{id}', 'AgentController@registrationThirdStepSeven')->name('users.agents.third-step.seven');
                    });
                });
                Route::get('memberships/{order?}', 'AgentController@registrationMembershipsForm')->name('users.agents.memberships');
                Route::match(['get', 'post'],'membership-checkout', 'AgentController@registrationMembershipCheckout')->name('users.agents.memberships-checkout');
                Route::get('memberships-update/{order?}', 'AgentController@registrationMembershipsFormUpdate')->name('users.agents.memberships.update');
                Route::match(['get', 'post'],'membership-checkout-update', 'AgentController@registrationMembershipCheckoutUpdate')->name('users.agents.memberships-checkout.update');
                Route::get('/get-project-photos', 'AgentController@getPhotos')->name('users.agents.project.get-photos');
                Route::post('/delete-project-photos', 'AgentController@deletePhoto')->name('users.agents.project.delete-photo');
                Route::post('/save-project-photos', 'AgentController@savePhoto')->name('users.agents.project.save-photos');
                Route::get('/change-space-photo', 'AgentController@changeSpacePhoto')->name('users.agents.project.change-space-photo');

                Route::get('/get-logo', 'AgentController@getLogo')->name('users.agents.get-logo');
                Route::post('/save-logo', 'AgentController@saveLogo')->name('users.agents.save-logo');
                Route::post('/del-logo', 'AgentController@delLogo')->name('users.agents.del-logo');
            });

            /**
            User Service Routes
            */
            Route::group(['prefix' => 'service','middleware' => 'multi-roles'], function(){
                Route::group(['prefix' => 'r'], function(){
                    Route::group(['prefix' => 'first-step'], function(){
                        Route::get('/', 'ServiceController@registrationFirstStep')->name('users.services.first-step');
                        Route::match(['get', 'post'],'one', 'ServiceController@registrationFirstStepOne')->name('users.services.first-step.one');
                        Route::match(['get', 'post'],'two', 'ServiceController@registrationFirstStepTwo')->name('users.services.first-step.two');

                    });
                    Route::group(['prefix' => 's-s'], function(){
                        Route::get('/', 'ServiceController@registrationSecondStep')->name('users.services.second-step');
                        Route::match(['get', 'post'],'one', 'ServiceController@registrationSecondStepOne')->name('users.services.second-step.one');
                        Route::match(['get', 'post'],'two', 'ServiceController@registrationSecondStepTwo')->name('users.services.second-step.two');

                        Route::match(['get', 'post'],'three/{order}', 'ServiceController@registrationSecondStepThree')->name('users.services.second-step.three');
                        Route::match(['get', 'post'],'four', 'ServiceController@registrationSecondStepFour')->name('users.services.second-step.four');
                        Route::match(['get', 'post'],'five', 'ServiceController@registrationSecondStepFive')->name('users.services.second-step.five');
                    });
                    Route::group(['prefix' => 'third-step'], function(){

                    });

                });

                Route::get('memberships', 'ServiceController@registrationMembershipsForm')->name('users.services.memberships');
                Route::get('m-back/{order}', 'ServiceController@registrationMembershipsFormBack')->name('users.services.memberships-back');
                Route::match(['get', 'post'],'membership-checkout', 'ServiceController@registrationMembershipCheckout')->name('users.services.memberships-checkout');

                Route::get('memberships-update', 'ServiceController@registrationMembershipsFormUpdate')->name('users.services.memberships.update');
                Route::get('m-up-back/{order}', 'ServiceController@registrationMembershipsFormBackUpdate')->name('users.services.memberships-back-update');
                Route::match(['get', 'post'],'membership-checkout-update', 'ServiceController@registrationMembershipCheckoutUpdate')->name('users.services.memberships-checkout-update');
                
                Route::get('/get-photos', 'ServiceController@getPhotos')->name('users.services.get-photos');
                Route::post('/save-photos/{service}', 'ServiceController@savePhoto')->name('users.services.save-photos');
                Route::get('/get-services-list', 'ServiceController@getServicesList')->name('users.services.get-services-list');
                Route::get('/get-logo', 'ServiceController@getLogo')->name('users.services.get-logo');
                Route::post('/save-logo', 'ServiceController@saveLogo')->name('users.services.save-logo');
                Route::post('/del-logo', 'ServiceController@delLogo')->name('users.services.del-logo');
                Route::post('/delete-service-photos', 'ServiceController@deletePhoto')->name('users.services.delete-photo');
                Route::post('/delete-photos-service', 'ServiceController@deletePhotosService')->name('users.services.delete-photos');
                Route::post('/get-photos-service', 'ServiceController@getPhotosService')->name('users.services.get-photos');
            });

        });

        /**
         * User Payments Rents Routes
         */

        Route::get('/payments/{property_id}/rent', 'PaymentController@showPropertyPayment')->name('users.payments.step-one');
        Route::get('/payments/success', 'PaymentController@showPaymentCongratulations')->name('users.payments.congratulations');
        

        /**
         * User Payments Short Stay Rent Routes
         */

        Route::get('/payments/{property_id}/short_stay', 'PaymentController@showPropertyShortStayPayment')->name('payments.short_stay.view');
        Route::get('/payments/success_short_stay', 'PaymentController@showPaymentCongratulationsShortStay')->name('users.payments.congratulations.short_stay');

        /**
         * User check coupon
         */
        
    });
    Route::group(['prefix' => 'properties', 'middleware' => 'owner-check'], function(){
      Route::group(['middleware' => 'verified'], function(){
        Route::group(['prefix' => 'registration'], function(){
            Route::group(['prefix' => 'first-step'], function(){
                Route::match(['get', 'post'],'one/{id?}', 'PropertyController@registrationFirstStepOne')->name('properties.first-step.one');
                Route::match(['get', 'post'],'one-one/{id}', 'PropertyController@registrationFirstStepOneOne')->name('properties.first-step.one-one');
                Route::match(['get', 'post'],'two/{id}', 'PropertyController@registrationFirstStepTwo')->name('properties.first-step.two');
                Route::match(['get', 'post'],'three/{id}', 'PropertyController@registrationFirstStepThree')->name('properties.first-step.three');
                Route::match(['get', 'post'],'four/{id}', 'PropertyController@registrationFirstStepFour')->name('properties.first-step.four');
                Route::match(['get', 'post'],'five/{id}', 'PropertyController@registrationFirstStepFive')->name('properties.first-step.five');
                Route::match(['get', 'post'],'/{id?}', 'PropertyController@registrationFirstStep')->name('properties.first-step');
                
            });
            Route::match(['get', 'post'],'/select/{id?}', 'PropertyController@registrationSelectStep')->name('properties.select-step');
            Route::group(['prefix' => 'second-step'], function(){
                Route::match(['get', 'post'],'one/{id}', 'PropertyController@registrationSecondStepOne')->name('properties.second-step.one');
                Route::match(['get', 'post'],'two/{id}', 'PropertyController@registrationSecondStepTwo')->name('properties.second-step.two');
                Route::get('/{id}', 'PropertyController@registrationSecondStep')->name('properties.second-step');
            });
            Route::group(['prefix' => 'third-step'], function(){
                Route::match(['get', 'post'],'one/{id}', 'PropertyController@registrationThirdStepOne')->name('properties.third-step.one');
                Route::get('/{id}', 'PropertyController@registrationThirdStep')->name('properties.third-step');
            });
            Route::group(['prefix' => 'fourth-step'], function(){
                Route::match(['get', 'post'],'one/{id}', 'PropertyController@registrationFourthStepOne')->name('properties.fourth-step.one');
                Route::match(['get', 'post'],'two/{id}', 'PropertyController@registrationFourthStepTwo')->name('properties.fourth-step.two');
                Route::get('/{id}', 'PropertyController@registrationFourthStep')->name('properties.fourth-step');
            });
        });
      });
    });


    Route::group(['prefix' => 'services'], function(){
        Route::group(['middleware' => 'verified'], function(){
            Route::group(['prefix' => 'registration'], function(){
                Route::group(['prefix' => 'first-step'], function(){
                    Route::match(['get', 'post'],'one/{id?}', 'ServiceListController@registrationFirstStepOne')->name('services.first-step.one');
                    Route::match(['get', 'post'],'two/', 'ServiceListController@registrationFirstStepTwo')->name('services.first-step.two');
                    Route::match(['get', 'post'],'three/', 'ServiceListController@registrationFirstStepThree')->name('services.first-step.three');
                });
            });
        });
    });
});

/**
Properties Routes
*/
Route::group(['prefix' => 'properties'], function(){
    Route::get('{property_id}', 'PropertyController@index');
    Route::get('{property_id}/photos', 'PropertyController@getPhotos')->name('properties.get-photos');
    Route::get('{property_id}/owner', 'PropertyController@owner')->name('properties.owner');
    Route::get('{property_id}/tenant', 'PropertyController@tenant')->name('properties.tenant');
    Route::get('{property_id}/agent', 'PropertyController@agent')->name('properties.agent');
    Route::get('{property_id}/service', 'PropertyController@agent')->name('properties.service');
    Route::get('{property_id}/amenities', 'PropertyController@amenities')->name('properties.amenities');
    Route::get('{property_id}/properties-for', 'PropertyController@propertiesFor')->name('properties.properties_for');
    Route::get('{property_id}/properties-type', 'PropertyController@propertiesType')->name('properties.properties_type');
    Route::get('{property_id}/my_score', 'PropertyController@userScore')->name('properties.user_score');
    Route::put('{property_id}/toggle-favourite', 'PropertyController@storeToggleAsFavourite')->middleware(['auth'])->name('properties.toggle_as_favourite');
    Route::post('{property_id}/apply', 'PropertyController@storeApplication')->middleware(['auth'])->name('properties.apply');
});
Route::group(['middleware' => 'auth'], function() {
    Route::get('/get-prop-photos', 'PropertyController@getPhotos')->name('properties.get-photos');
    Route::post('/save-prop-photos', 'PropertyController@savePhoto')->name('properties.save-photos');
    Route::post('/delete-prop-photos', 'PropertyController@deletePhoto')->name('properties.delete-photo');
    Route::get('/change-space', 'PropertyController@changeSpace')->name('change-space');
    Route::get('/verified-cbrs/{id}','PropertyController@verifiedCBRS')->name('verified-cbrs');
    Route::post('/apply-management','PropertyController@applyManagement')->name('apply-management');
});

/**
 * Project Routes
 */

// REVISANDO --- 21-02-2019 *****************************************
Route::group(['prefix' => 'projects'], function(){
    Route::get('{project_id}', 'ProjectController@index');
    Route::get('{project_id}/agent', 'ProjectController@agent');
    Route::get('{project_id}/service', 'ProjectController@agent');
    Route::get('{project_id}/amenities', 'ProjectController@amenities');
    Route::get('{project_id}/photos', 'ProjectController@photos');
});


/**
* Schedule Routes
*/
Route::group(['middleware' => 'auth', 'prefix' => 'schedules'], function() {
    Route::post('', 'ScheduleController@store')->name('schedule.store');
    Route::put('', 'ScheduleController@update')->name('schedule.update');
    Route::get('/get-shedule-property/{id}','ScheduleController@getSchedule')->name('get-schedule-property');
    Route::get('/get-shedule-short-stay/{id}','ScheduleController@getScheduleShortStay')->name('get-schedule-short-stay');
    Route::post('/save-shedule-property/{id}','ScheduleController@saveSchedule')->name('save-schedule-property');
});

/* Profiles BY:DS */

Route::get('/users/profile/tenant', 'TenantProfileController@tenantProfileIndexAction')->name('profile.tenant');
Route::get('/users/profile/tenant/get-filters', 'TenantProfileController@getFiltersAction')->name('filters.tenant');
Route::get('/users/profile/tenant/get-info', 'TenantProfileController@getInfoAction')->name('filters.tenant');
Route::post('users/profile/tenant/save-data', 'TenantProfileController@saveProfileAction')->name('profile.save');
Route::post('users/profile/tenant/save-employment', 'TenantProfileController@saveEmploymentAction')->name('profile.employment.save');
Route::get('users/profile/tenant/order-payment/{order}', 'TenantProfileController@getOrderPayment')->name('get.order.payment.tenant');

Route::get('/users/profile/owner', 'OwnerProfileController@ownerProfileIndexAction')->name('profile.owner');

/*=========================================
=            Rutas Perfil Aval            =
=========================================*/
Route::get('/users/profile/collateral', 'CollateralProfileController@collateralProfileIndexAction')->name('profile.collateral');
Route::get('/users/profile/collateral/get-filters', 'CollateralProfileController@getFiltersAction')->name('filters.collateral');
Route::get('/users/profile/collateral/get-info', 'CollateralProfileController@getInfoAction')->name('filters.collateral');
Route::post('users/profile/collateral/save-data', 'CollateralProfileController@saveProfileAction')->name('profile.save');

Route::get('users/profile/collateral/{id}/contracts', 'CollateralProfileController@getCollateralContracts')->name('contracts.collateral');

/*=====  End of Rutas Perfil Aval  ======*/




// ***********************************************
// REVISAR --- 21-02-2019 ************************

// Rutas que muestran información en el Perfil del Agente   ******************************************************

Route::get('/users/profile/agent', 'AgentProfileController@agentProfileIndexAction')->name('profile.agent');

Route::get('/users/profile/agent/get-info', 'AgentProfileController@getInfoAction')->name('info.agent');

Route::get('/users/profile/agent/get-project', 'AgentExploreController@getProjects')->name('project.agent');

//****************************************************************************************************************

Route::get('/users/profile/agent/get-filters', 'AgentProfileController@getFiltersAction')->name('filters.agent');
Route::post('/users/profile/agent/save-data', 'AgentProfileController@saveProfileAction')->name('save.agent');
Route::post('/users/profile/agent/save-company', 'AgentProfileController@saveCompanyAction')->name('save.company.agent');
Route::post('/users/profile/agent/save-prop', 'AgentProfileController@savePropsAction')->name('save.props.agent');

// ******************************************************************************************************


Route::get('/users/profile/service', 'ServiceProfileController@serviceProfileIndexAction')->name('profile.service');

Route::get('/users/profile/service/get-info', 'ServiceProfileController@getInfoAction')->name('info.service');

//****************************************************************************************************************

Route::get('/users/profile/service/get-filters', 'ServiceProfileController@getFiltersAction')->name('filters.service');
Route::post('/users/profile/service/save-data', 'ServiceProfileController@saveProfileAction')->name('save.data');
Route::post('/users/profile/service/save-company', 'ServiceProfileController@saveCompanyAction')->name('save.company.service');
Route::post('/users/profile/service/save-service', 'ServiceProfileController@saveServiceAction')->name('save.service');




// -------








// **********************************


Route::get('/profile/tenant/data/{id}', 'TenantProfileController@getDataAction')->name('data.tenant');
Route::get('/profile/document/download/{file}', 'FileController@downloadDocument')->name('download.document');

Route::get('/users/profile/owner/get-filters', 'OwnerProfileController@getFiltersAction')->name('filters.owner');
Route::get('/users/profile/owner/get-info', 'OwnerProfileController@getInfoAction')->name('info.owner');
Route::get('/users/profile/owner/get-charts', 'OwnerProfileController@getCharts')->name('charts.owner');

/* Nuevas Direcciones para OwnerProfile 10-04-2019 */
Route::get('/users/profile/owner/get-tenant/{id}', 'OwnerProfileController@getTenant')->name('info.owner.tenant');
Route::get('/users/profile/owner/get-properties', 'OwnerProfileController@getProperties')->name('info.owner.properties');
Route::get('/users/profile/owner/get-postulation/{id}', 'OwnerProfileController@getPostulationList')->name('info.owner.postulation');
Route::get('/users/profile/owner/get-property/{id}', 'OwnerProfileController@getProperty')->name('info.owner.property');
/* Nuevas Direcciones para OwnerProfile 10-04-2019 */

Route::post('/users/profile/owner/save-data', 'OwnerProfileController@saveProfileAction')->name('save.owner');
Route::post('/users/profile/owner/save-prop', 'OwnerProfileController@savePropsAction')->name('save.props');
Route::post('/users/profile/owner/save-config', 'OwnerProfileController@saveConfigAction')->name('save.config');

Route::get('/users/profile/owner/delete-property/{id}', 'OwnerProfileController@deleteProperty')->name('info.owner.delete-property');
Route::get('/users/profile/owner/publish-property/{id}', 'OwnerProfileController@publishProperty')->name('info.owner.publish-property');
Route::get('/users/profile/owner/pause-property/{id}', 'OwnerProfileController@pauseProperty')->name('info.owner.pause-property');
Route::get('/users/profile/owner/leased-property/{id}', 'OwnerProfileController@leasedProperty')->name('info.owner.leased-property');
Route::post('/users/profile/owner/save-postulant', 'OwnerProfileController@saveStatePostulant')->name('info.owner.save-postulant');
Route::get('/users/profile/owner/order-payment/{order}', 'OwnerProfileController@getOrderPayment')->name('get.order.payment.owner');
/* Direccion para los contratos de arrendador - owner */
Route::get('users/profile/owner/{id}/contracts', 'OwnerProfileController@getOwnerContracts')->name('contracts.owner');

/* END Profiles  */

/**
    Routes for AdminPanel
*/
Route::middleware('auth:admin')->get('/admin/{vue_capture?}', function (App\Useradmin $user) {
  return view('admin.index', ['role' => Auth::user()->role]);
})->where('vue_capture', '[\/\w\.-]*')->name('admin.panel');

Route::get('/adminlogin', 'Auth\AdminLoginController@login')->name('admin.auth.login');
Route::post('/adminlogin', 'Auth\AdminLoginController@loginAdmin')->name('admin.auth.loginAdmin');
Route::get('/adminlogout', 'Auth\AdminLoginController@logout')->name('admin.auth.logout');

Route::group(['prefix' => 'adm', 'middleware' => 'auth:admin'], function(){
//Route::group(['prefix' => 'adm'], function(){
    // Admin Dashboard
    Route::get('/dashboard/descriptors', 'Admin\DashboardController@getDescriptors')->name('admin.descriptors');
    // Admin UsersAdmin
    Route::group(['prefix' => 'users-admin'], function(){
        Route::get('/', 'Admin\UserAdminController@getUsers')->name('admin.users.admin.list');
        Route::post('/create', 'Admin\UserAdminController@create')->name('admin.users.admin.create');
        Route::post('/{userId}/update', 'Admin\UserAdminController@update')->name('admin.users.admin.update');
        Route::post('/{userId}/update-photo', 'Admin\UserAdminController@saveAvatarPhoto')->name('admin.users.admin.update-photo');
        Route::post('/{userId}/delete', 'Admin\UserAdminController@delete')->name('admin.users.admin.delete');
        Route::get('/{userId}', 'Admin\UserAdminController@getUser')->name('admin.users.admin.user');
    });
    // Admin Users
    Route::group(['prefix' => 'users'], function(){
          Route::get('/', 'Admin\UserController@getUsers')->name('admin.users.list');
          Route::get('/role/{roleId}', 'Admin\UserController@getUsersByRole')->name('admin.users.getUsersByRole');
          Route::get('/owners', 'Admin\UserController@getOwners')->name('admin.users.owners');
          Route::post('/create', 'Admin\UserController@create')->name('admin.users.create');
          Route::post('/{userId}/update', 'Admin\UserController@update')->name('admin.users.update');
          Route::post('/{userId}/delete', 'Admin\UserController@delete')->name('admin.users.delete');
          Route::get('{userId}/roles', 'Admin\UserController@getRoles')->name('admin.users.roles');
          Route::get('/descriptors', 'Admin\UserController@getDescriptors')->name('admin.users.descriptors');
          Route::get('/{userId}/collateral', 'Admin\UserController@getCollateral')->name('admin.users.collateral');
          Route::get('/{userId}/companies/agent', 'Admin\UserController@getAgentCompany')->name('admin.users.companies.agent');
          Route::get('/{userId}/companies/service', 'Admin\UserController@getServiceCompany')->name('admin.users.companies.service');
          Route::get('/cities', 'Admin\UserController@getCities')->name('admin.users.cities');
          Route::post('/{userId}/new-role/{roleId}', 'Admin\UserController@newRole')->name('admin.users.new-role');
          Route::post('/{userId}/generate-password', 'Admin\UserController@generatePassword')->name('admin.users.generate-password');
          Route::get('/{userId}', 'Admin\UserController@getUser')->name('admin.users.user');
          Route::post('/file-data-save', 'Admin\UserController@saveDataFile')->name('admin.users.file');
          /**
            Admin User Tenant Registration Routes
          */
          Route::group(['prefix' => 'tenant-registration'], function(){
                Route::post('/personal-data', 'Admin\TenantController@personalData')->name('admin.users.tenant-registration.personal-data');
                Route::post('/location-data', 'Admin\TenantController@locationData')->name('admin.users.tenant-registration.location-data');
                Route::post('/collateral-data', 'Admin\TenantController@collateralData')->name('admin.users.tenant-registration.collateral-data');
                Route::post('/employment-data', 'Admin\TenantController@employmentData')->name('admin.users.tenant-registration.employment-data');
                Route::post('/payment-preferences', 'Admin\TenantController@paymentPreferences')->name('admin.users.tenant-registration.payment-preferences');
                Route::post('/tenanting-preferences', 'Admin\TenantController@tenantingPreferences')->name('admin.users.tenant-registration.tenanting-preferences');
          });
          /**
            Admin User Owner Registration Routes
          */
          Route::group(['prefix' => 'owner-registration'], function(){
                Route::post('/personal-data', 'Admin\OwnerController@personalData')->name('admin.users.owner-registration.personal-data');
                Route::post('/location-data', 'Admin\OwnerController@locationData')->name('admin.users.owner-registration.location-data');
          });
          /**
            Admin User Agent Registration Routes
          */
          Route::group(['prefix' => 'agent-registration'], function(){
                Route::post('/personal-data', 'Admin\AgentController@personalData')->name('admin.users.agent-registration.personal-data');
                Route::post('/location-data', 'Admin\AgentController@locationData')->name('admin.users.agent-registration.location-data');
                Route::post('/company-data', 'Admin\AgentController@companyData')->name('admin.users.agent-registration.company-data');
                Route::post('/company-location-data', 'Admin\AgentController@companyLocationData')->name('admin.users.agent-registration.company-location-data');
          });
          /**
            Admin User Service Registration Routes
          */
          Route::group(['prefix' => 'service-registration'], function(){
                Route::post('/personal-data', 'Admin\ServiceController@personalData')->name('admin.users.service-registration.personal-data');
                Route::post('/location-data', 'Admin\ServiceController@locationData')->name('admin.users.service-registration.location-data');
                Route::post('/company-data', 'Admin\ServiceController@companyData')->name('admin.users.service-registration.company-data');
          });
    });
    // Admin Properties
    Route::group(['prefix' => 'properties'], function(){
      Route::get('/', 'Admin\PropertyController@getProperties')->name('admin.properties.list');

      Route::post('/save-prop-photos', 'Admin\PropertyController@savePhoto')->name('admin-properties.save-photos');

      //Quantities:
      Route::get('/descriptors', 'Admin\PropertyController@getDescriptors')->name('admin.properties.descriptors');
      //Lists
      Route::get('/list-published', 'Admin\PropertyController@getPropertiesPublished')->name('admin.properties.listPublished');
      Route::get('/list-eliminated', 'Admin\PropertyController@getPropertiesEliminated')->name('admin.properties.listEliminated');
      Route::get('/list-leased', 'Admin\PropertyController@getPropertiesLeased')->name('admin.properties.listLeased');
      Route::get('/list-executive', 'Admin\PropertyController@getPropertiesExecutive')->name('admin.properties.listExecutive');
      Route::get('/list-paused', 'Admin\PropertyController@getPropertiesPaused')->name('admin.properties.listPaused');

      //operations
      Route::post('/{propertyId}/pause', 'Admin\PropertyController@pauseProperty')->name('admin.properties.pause');
      Route::post('/{propertyId}/publish', 'Admin\PropertyController@publishProperty')->name('admin.properties.publish');

      Route::post('/change-meta-photo', 'Admin\PropertyController@changeMetaPhoto')->name('admin.properties.changeMetaPhoto');
      Route::post('/delete-photo', 'Admin\PropertyController@deletePhoto')->name('admin.properties.deletePhoto');

      Route::post('/basic-data', 'Admin\PropertyController@saveBasicData')->name('admin.properties.basic-data');
      Route::post('/location-data', 'Admin\PropertyController@saveLocationData')->name('admin.properties.location-data');
      Route::post('/property-details', 'Admin\PropertyController@savePropertyDetails')->name('admin.properties.property-details');
      Route::post('/tenanting-conditions', 'Admin\PropertyController@saveTenantingConditions')->name('admin.properties.tenanting-conditions');
      Route::post('/visit-preferences', 'Admin\PropertyController@saveVisitPreferences')->name('admin.properties.visit-preferences');
      Route::post('/features-tenant', 'Admin\PropertyController@saveFeaturesTenant')->name('admin.properties.features-tenant');
      Route::post('/assing-execute', 'Admin\PropertyController@saveAssingExecute')->name('admin.properties.assing-execute');
      Route::post('/{propertyId}/delete', 'Admin\PropertyController@deleteProperty')->name('admin.properties.delete');
      Route::get('/property-types', 'Admin\PropertyController@getPropertyTypes')->name('admin.properties.property-types');
      Route::get('/properties-for', 'Admin\PropertyController@getPropertiesFor')->name('admin.properties.properties-for');
      Route::post('/verified', 'Admin\PropertyController@savePropertyVerified')->name('admin.properties.verified');
      Route::get('/{propertyId}', 'Admin\PropertyController@getProperty')->name('admin.properties.property');


    });
    // Admin Projects
    Route::group(['prefix' => 'projects'], function(){
      Route::get('/', 'Admin\ProjectController@getProjects')->name('admin.projects.list');
      Route::post('/{projectId}/update', 'Admin\ProjectController@update')->name('admin.projects.update');
      Route::post('/{projectId}/delete', 'Admin\ProjectController@delete')->name('admin.projects.delete');
      Route::get('/{projectId}', 'Admin\ProjectController@getProjectData')->name('admin.projects.project');
    });
    // Admin Services
    Route::group(['prefix' => 'services'], function(){
      Route::get('/', 'Admin\ServiceController@getServices')->name('admin.services.list');
      //Route::post('/{projectId}/update', 'Admin\ProjectController@update')->name('admin.projects.update');
      //Route::post('/{projectId}/delete', 'Admin\ProjectController@delete')->name('admin.projects.delete');
      //Route::get('/{projectId}', 'Admin\ProjectController@getProjectData')->name('admin.projects.project');
    });
    // Admin Memberships
    Route::group(['prefix' => 'memberships'], function(){
      Route::get('/', 'Admin\MembershipController@getMemberships')->name('admin.memberships.list');
      Route::get('/roles', 'Admin\MembershipController@getMembershipsForRole')->name('admin.memberships.roles');
      Route::post('/{membershipId}/update', 'Admin\MembershipController@update')->name('admin.membership.update');
      Route::get('/{membershipId}/users', 'Admin\MembershipController@getUsersHasMembership')->name('admin.memberships.users');
      Route::get('/{membershipId}/users/{userId}', 'Admin\MembershipController@getUserHasMembership')->name('admin.memberships.user-has-membership');

      Route::get('/descriptors/users-multirole', 'Admin\MembershipController@getUsersMultiRole')->name('admin.memberships.descriptors-users-multirole');

      Route::get('/descriptors/users-default', 'Admin\MembershipController@getUsersDefault')->name('admin.memberships.descriptors-users-default');

      Route::post('/{membershipId}/users/{userId}/update', 'Admin\MembershipController@updateUserHasMembership')->name('admin.memberships.update-user-has-membership');

      Route::post('/{membershipId}/users/{userId}/delete', 'Admin\MembershipController@detachUser')->name('admin.memberships.detach-user');

      Route::get('/{membershipId}', 'Admin\MembershipController@getMembership')->name('admin.memberships.membership');
    });

    // Admin Payments
    Route::group(['prefix' => 'payments'], function(){
      Route::get('/', 'Admin\PaymentController@getPayments')->name('admin.payments.list');
      Route::post('/{paymentId}/update', 'Admin\PaymentController@update')->name('admin.payments.update');
      Route::post('/{paymentId}/delete', 'Admin\PaymentController@delete')->name('admin.payments.delete');
      Route::get('/{paymentId}', 'Admin\PaymentController@getPayment')->name('admin.payments.payment');
    });

    // Admin Contracts
    Route::group(['prefix' => 'contracts'], function(){
      Route::get('/', 'Admin\ContractController@getContracts')->name('admin.contracts.list');
      //Route::post('/{paymentId}/update', 'Admin\PaymentController@update')->name('admin.payments.update');
      //Route::post('/{paymentId}/delete', 'Admin\PaymentController@delete')->name('admin.payments.delete');
      //Route::get('/{paymentId}', 'Admin\PaymentController@getPayment')->name('admin.payments.payment');
    });

    // Admin Civil Status
    Route::group(['prefix' => 'civil-status'], function(){
      Route::get('/', 'Admin\CivilStatusController@getCivilStatus')->name('admin.civil-status.list');
    });
    // Admin Cities
    Route::group(['prefix' => 'cities'], function(){
      Route::get('/', 'Admin\CityController@getCities')->name('admin.cities.list');
    });
    // Admin Country
    Route::group(['prefix' => 'countries'], function(){
      Route::get('/', 'Admin\CountryController@getCountries')->name('admin.countries.list');
    });
    // Admin Roles
    Route::group(['prefix' => 'roles'], function(){
      Route::get('/', 'Admin\RoleController@getRoles')->name('admin.roles.list');
    });
    // Admin Scoring
    Route::group(['prefix' => 'scoring'], function(){
      Route::get('/', 'Admin\ScoringController@getScoringList')->name('admin.scoring.list');
      Route::post('/update', 'Admin\ScoringController@update')->name('admin.scoring.update');
      Route::post('/create', 'Admin\ScoringController@create')->name('admin.scoring.create');
      Route::get('{scoringId}/categories', 'Admin\ScoringController@getScoringCategories')->name('admin.scoring.categories');
      Route::get('{scoringId}/categories/{scoringCategoryId}', 'Admin\ScoringController@getScoringCategory')->name('admin.scoring.category');
      Route::post('{scoringId}/categories/{scoringCategoryId}/update', 'Admin\ScoringController@updateScoringCategory')->name('admin.scoring.category.update');
      Route::get('{scoringId}/categories/{scoringCategoryId}/details', 'Admin\ScoringController@getScoringCategoryDetails')->name('admin.scoring.category-details');
      Route::get('{scoringId}/categories/{scoringCategoryId}/details/{categoryDetailId}', 'Admin\ScoringController@getScoringCategoryDetail')->name('admin.scoring.category-detail');
      Route::post('{scoringId}/categories/{scoringCategoryId}/details/{categoryDetailId}/update', 'Admin\ScoringController@updateScoringCategoryDetail')->name('admin.scoring.category-detail.update');
      Route::post('{scoringId}/categories/add', 'Admin\ScoringController@addScoringCategory')->name('admin.scoring.category.add');
      Route::post('{scoringId}/categories/{categoryId}/details/add', 'Admin\ScoringController@addScoringCategoryDetail')->name('admin.scoring.category-detail.add');
      Route::get('/{scoringId}', 'Admin\ScoringController@getScoring')->name('admin.scoring');
    });
    // Coupons
    Route::group(['prefix' => 'coupons'], function(){
        Route::get('/', 'Admin\CouponController@getCouponsList')->name('admin.coupons.list');
        Route::get('/{id}', 'Admin\CouponController@getCoupon')->name('admin.coupon.get');
        Route::post('/create', 'Admin\CouponController@CouponCreate')->name('admin.coupons.create');
        Route::post('/update', 'Admin\CouponController@CouponUpdate')->name('admin.coupons.update');
        Route::get('/show/{id}', 'Admin\CouponController@CouponShow')->name('admin.coupons.show');
        Route::get('/users/{id}', 'Admin\CouponController@getListUsersCoupon')->name('admin.coupon.get.users');
    });
    // Configurations
    Route::group(['prefix' => 'configurations'], function(){
        Route::get('/', 'Admin\ConfigurationController@getConfigurarions')->name('admin.configurations.get');
        Route::post('/update', 'Admin\ConfigurationController@updateConfigurations')->name('admin.configurations.update');
    });
});

/*----------  CHAT  ----------*/
Route::get('/chat/conversations', 'ConversationController@index');
Route::get('/chat/messages', 'MessageController@index');
Route::post('/chat/messages', 'MessageController@store');
/*--------  END CHAT  --------*/


/*----------  Payments  ----------*/
Route::group(['prefix' => 'payments'], function(){
    Route::group(['prefix' => 'memberships'], function(){
        Route::post('process-to-checkout', 'PaymentController@handleMembershipPayment')->name('payments.memberships');
        Route::match(['get', 'post'], 'checkout-payment/{payment_order}/{membership_id}', 'PaymentController@membershipsTransbankCallBack')->name('payments.intermediate-callback');
        Route::match(['get', 'post'], 'end/{payment_order}/{membership_id}', 'PaymentController@endTransbankPayment')->name('payments.end');
    });
    Route::group(['prefix' => 'rents'], function(){
        Route::post('rents-to-checkout', 'PaymentController@handleRentPayment')->name('payments.rents');
        Route::match(['get', 'post'], 'checkout-payment/{payment_order}/{apply_property_id}', 'PaymentController@rentsTransbankCallBack')->name('rents.intermediate-callback');
        Route::match(['get', 'post'], 'end/{payment_order}/{apply_property_id}', 'PaymentController@endTransbankPaymentRent')->name('rents.payment.end');
    });
    Route::group(['prefix' => 'short_stay'], function(){
        Route::post('rents-to-checkout', 'PaymentController@handleShortStayPayment')->name('payments.short_stay');
        Route::match(['get', 'post'], 'checkout-payment/{payment_order}/{apply_property_id}', 'PaymentController@ShortStayTransbankCallBack')->name('short_stay.intermediate-callback');
        Route::match(['get', 'post'], 'end/{payment_order}/{apply_property_id}', 'PaymentController@endTransbankPaymentShortStay')->name('short_stay.payment.end');
    });
});

/*--------  END Payments  --------*/

/* TEST por AA */

Route::get('test-contrato', 'ContractController@generateContract')->name('contracts.test1');
Route::get('contracts/{contract_id}/get_stream', 'ContractController@streamContract')->name('contracts');

/* ----- TEST SUM & SUS ---- */
Route::group(['middleware' => 'TokenSasVerification'], function(){
    Route::get('sas/results/{token}', 'APISasController@gettingVerificationResults')->name('getting.verification.results');
});
Route::group(['middleware' => ['checkSasIp', 'api'] ], function(){
    Route::post('kyc', 'APISasController@endpointSas')->name('getting.verification.results.sas');
});



Route::get('test-login-sas', 'APISasController@sendApplicant');
Route::get('documento_seguros', function(){ return response()->file('seguro_arriendos.pdf', ['Content-Type' => 'application/pdf']); });

/* VIDEO VERIFICACION */

Route::post('video-verify/{postul_id}', 'VideoController@handleVideoVerify3');
Route::get('pre-video-verify', 'VideoController@handleVideoVerify2');
Route::post('state-video-verify', 'VideoController@verificarEstadoVerificacion');
/* IDENTIDAD VERIFIACION */

Route::post('identity-check', 'APISasController@identityCheck');
Route::post('identity-send-image', 'APISasController@sendImage');

/**
 * Endpoint HelloSign
 */
Route::group(['middleware' => [ 'api'] ], function(){
    Route::post('hellosign_endpoint', 'ContractController@gettingHSResults');
});
 /**
  * Pide recursos para servicios videoidexer
  */
Route::post('55ae5abbac6c34cb1d93377cd31598f7', 'VideoController@iniciarSesion')->name('iniciar.sesion.vin');
/**
 * Verificacion OCR para ajax
 */
Route::post('ocr_verify', 'OcrController@consumirServicioDesdeForm');
Route::post('ocr_verify_perfil', 'OcrController@consumirServicioDesdePerfil');
Route::get('ocr_test', 'OcrController@test');
/**
 * Check Coupon
 */

/**
 * Toogle show_welcome
 */
Route::get('toogle_show_welcome', function(){
    if($user = \Auth::user()){
        $user->show_welcome = 0;
        $user->save();
    }
    return response('ok');
});
Route::get('stream/borrador_contrato/{owner_id}/{tenant_id}/{property_id}', 'ContractController@streamBorradorContrato');
