<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMemberships14102019 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('memberships')
            ->where([
                'id' => 9,
                'name' => 'Basic',
                'role_id' => 3
                ])
            ->update(['features' => json_encode([
                "package_amount" => "1",
                "montly_publications" => 15,
                "photos_per_project" => 5,
                "videos_per_project" => 0,
                "project_due_days" => 15,
                "owner_contact" => 0,
                "public_support" => false,
                "recommendations" => 0,
            ])]);
        DB::table('memberships')
            ->where([
                'id' => 10,
                'name' => 'Select',
                'role_id' => 3
                ])
            ->update(['features' => json_encode([
                "package_amount" => "2,2",
                "montly_publications" => 40,
                "photos_per_project" => 8,
                "videos_per_project" => 0,
                "project_due_days" => 30,
                "owner_contact" => 1,
                "public_support" => true,
                "recommendations" => 1,
            ])]);
        DB::table('memberships')
            ->where([
                'id' => 11,
                'name' => 'Premium',
                'role_id' => 3
                ])
            ->update(['features' => json_encode([
                "package_amount" => "3,3",
                "montly_publications" => -1,
                "photos_per_project" => 15,
                "videos_per_project" => 1,
                "project_due_days" => 30,
                "owner_contact" => 2,
                "public_support" => true,
                "recommendations" => 2,
            ])]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
