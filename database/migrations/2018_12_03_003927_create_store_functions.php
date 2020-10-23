
<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateStoreFunctions extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $file = realpath(__DIR__.'/../routines/sf_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sp_score_user.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sf_demand.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sf_demand_property.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sf_favorite.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sf_applied.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/v_scoring.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sf_contact_score_user.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sf_identity_score_user.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sf_nationality_score_user.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sf_endorsement_score_user.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sf_job_user_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sf_docs_user_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sf_preferences_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sf_conditions_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sf_membership_user_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sf_finantial_user_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sp_contact_score_user.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sp_nationality_score_user.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sp_identity_score_user.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sp_endorsement_score_user.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sp_job_user_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sp_docs_user_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sp_preferences_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sp_conditions_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sp_membership_user_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/sp_finantial_user_score.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/v_properties.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/v_properties_wu.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/v_agent.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/v_services.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/v_properties_descriptor_days.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/v_newsletter_for_months.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/v_leased_properties.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/v_roles_memberships_users.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/v_users_memberships_count.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/v_users_count_role.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/v_scoring_points.sql');
        DB::unprepared( file_get_contents($file) );

        $file = realpath(__DIR__.'/../routines/v_scoring_category_points.sql');
        DB::unprepared( file_get_contents($file) );
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    $command =

        "DROP FUNCTION IF EXISTS sf_contact_score_user; ".
        "DROP FUNCTION IF EXISTS sf_docs_user_score; ".
        "DROP FUNCTION IF EXISTS sf_endorsement_score_user; ".
        "DROP FUNCTION IF EXISTS sf_finantial_user_score; ".
        "DROP FUNCTION IF EXISTS sf_job_user_score; ".
        "DROP FUNCTION IF EXISTS sf_membership_user_score; ".
        "DROP FUNCTION IF EXISTS sf_preferences_score; ".
        "DROP FUNCTION IF EXISTS sf_demand; ".
        "DROP FUNCTION IF EXISTS sf_score; ".
        "DROP FUNCTION IF EXISTS sf_applied;" .
        "DROP FUNCTION IF EXISTS sf_demand_property;" .
        "DROP FUNCTION IF EXISTS sf_nationality_score_user;" .
        "DROP FUNCTION IF EXISTS sf_conditions_score;" .
        "DROP FUNCTION IF EXISTS sf_favorite; ".
        "DROP FUNCTION IF EXISTS sf_identity_score_user; ".
        

        "DROP PROCEDURE IF EXISTS sp_score_user;" .
        "DROP PROCEDURE IF EXISTS sp_contact_score_user;" .
        "DROP PROCEDURE IF EXISTS sp_nationality_score_user;" .
        "DROP PROCEDURE IF EXISTS sp_identity_score_user;" .
        "DROP PROCEDURE IF EXISTS sp_endorsement_score_user;" .
        "DROP PROCEDURE IF EXISTS sp_job_user_score;" .
        "DROP PROCEDURE IF EXISTS sp_docs_user_score;" .
        "DROP PROCEDURE IF EXISTS sp_preferences_score;" .
        "DROP PROCEDURE IF EXISTS sp_conditions_score;" .
        "DROP PROCEDURE IF EXISTS sp_finantial_user_score;" .
        "DROP PROCEDURE IF EXISTS sp_membership_user_score; ".
        
        
        "DROP VIEW IF EXISTS v_properties_wu; ".
        "DROP VIEW IF EXISTS v_properties; ".
        "DROP VIEW IF EXISTS v_agent; ".
        "DROP VIEW IF EXISTS v_services; ".
        "DROP VIEW IF EXISTS v_properties_descriptor_days; ".
        "DROP VIEW IF EXISTS v_leased_properties; ".
        "DROP VIEW IF EXISTS v_newsletter_for_months; ".
        "DROP VIEW IF EXISTS v_roles_memberships_users; ".
        "DROP VIEW IF EXISTS v_users_memberships_count; ".
        "DROP VIEW IF EXISTS v_users_count_role; " .
        "DROP VIEW IF EXISTS v_scoring; " .
        "DROP VIEW IF EXISTS v_scoring_points; " .
        "DROP VIEW IF EXISTS v_scoring_category_points; "
        ;

        DB::connection()->getPdo()->exec($command);

    }
}
