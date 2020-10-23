<?php

use Illuminate\Database\Seeder;
use App\Membership;
use App\Role;

class MembershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::all();
        foreach ($roles as $role) {
            if (!$role->hidden) {
                switch ($role->id) {
                    case 1: # Tenant Memberships...
                        $memberships = array(
                            array(
                                "name" => "Basic",
                                "role_id" => $role->id,
                                "features" => json_encode([
                                    "package_amount" => "0",
                                    "applications_received_count" => 2,
                                    "score_display" => 1,
                                    "display_all_properties" => 1,
                                    "commission" => 0,
                                    "owner_contact" => 0,
                                    "suggestions_to_owners" => 0,
                                    "owner_verification" => 1,
                                    "trust_seal" => 0,
                                    "smart_agent" => 1,
                                    "recommendations" => 0,
                                ]),
                                "enabled" => true
                            ),
                            array(
                                "name" => "Select",
                                "role_id" => $role->id,
                                "features" => json_encode([
                                    "package_amount" => "9.500",
                                    "applications_received_count" => 8,
                                    "score_display" => 1,
                                    "display_all_properties" => 1,
                                    "commission" => 0,
                                    "owner_contact" => 1,
                                    "suggestions_to_owners" => 1,
                                    "owner_verification" => 1,
                                    "trust_seal" => 0,
                                    "smart_agent" => 1,
                                    "recommendations" => 1,
                                ]),
                                "enabled" => true
                            ),

                            array(
                                "name" => "Premium",
                                "role_id" => $role->id,
                                "features" => json_encode([
                                    "package_amount" => "14.750",
                                    "applications_received_count" => -1,
                                    "score_display" => 1,
                                    "display_all_properties" => 1,
                                    "commission" => 0,
                                    "owner_contact" => 2,
                                    "suggestions_to_owners" => 1,
                                    "owner_verification" => 1,
                                    "trust_seal" => 1,
                                    "smart_agent" => 1,
                                    "recommendations" => 2,
                                ]),
                                "enabled" => true
                            ),

                            array(
                                "name" => "Default",
                                "role_id" => $role->id,
                                "features" => json_encode(["hey" => "ho"]),
                                "enabled" => false
                            ),
                        );

                        break;
                    case 2: # Owner Memberships...
                        $memberships = array(
                            array(
                                "name" => "Basic",
                                "role_id" => $role->id,
                                "features" => json_encode([
                                    "package_amount" => "0",
                                    "application_count" => -1,
                                    "properties_count" => -1,
                                    "score_display" => true,
                                    "applications_received_count" => 2,
                                    "tenanting_fee" => "15",
                                    "owner_contact" => 0,
                                    "suggestions_to_tenants" => false,
                                    "owner_verification" => true,
                                    "trust_seal" => false,
                                    "smart_agent" => false,
                                    "public_support" => false,
                                    "recommendations" => 0,
                                ]),
                                "enabled" => true
                            ),
                            array(
                                "name" => "Select",
                                "role_id" => $role->id,
                                "features" => json_encode([
                                    "package_amount" => "16.750",
                                    "application_count" => -1,
                                    "properties_count" => -1,
                                    "score_display" => true,
                                    "applications_received_count" => 8,
                                    "tenanting_fee" => "15",
                                    "owner_contact" => 1,
                                    "suggestions_to_tenants" => true,
                                    "owner_verification" => true,
                                    "trust_seal" => false,
                                    "smart_agent" => true,
                                    "public_support" => true,
                                    "recommendations" => 1,
                                ]),
                                "enabled" => true
                            ),
                            array(
                                "name" => "Premium",
                                "role_id" => $role->id,
                                "features" => json_encode([
                                    "package_amount" => "22.550",
                                    "application_count" => -1,
                                    "properties_count" => -1,
                                    "score_display" => true,
                                    "applications_received_count" => -1,
                                    "tenanting_fee" => "15",
                                    "owner_contact" => 2,
                                    "suggestions_to_tenants" => true,
                                    "owner_verification" => true,
                                    "trust_seal" => true,
                                    "smart_agent" => true,
                                    "public_support" => true,
                                    "recommendations" => 2,
                                ]),
                                "enabled" => true
                            ),
                            array(
                                "name" => "Default",
                                "role_id" => $role->id,
                                "features" => json_encode(["hey" => "ho"]),
                                "enabled" => false
                            ),
                        );

                        break;

                    case 3: # Agent Memberships...
                        $memberships = array(
                            array(
                                "name" => "Basic",
                                "role_id" => $role->id,
                                "features" => json_encode([
                                    "package_amount" => "7.550",
                                    "montly_publications" => 1,
                                    "photos_per_project" => 5,
                                    "videos_per_project" => 0,
                                    "project_due_days" => 15,
                                    "owner_contact" => 0,
                                    "public_support" => false,
                                    "recommendations" => 0,
                                ]),
                                "enabled" => true
                            ),
                            array(
                                "name" => "Select",
                                "role_id" => $role->id,
                                "features" => json_encode([
                                    "package_amount" => "16.550",
                                    "montly_publications" => 5,
                                    "photos_per_project" => 8,
                                    "videos_per_project" => 0,
                                    "project_due_days" => 30,
                                    "owner_contact" => 1,
                                    "public_support" => true,
                                    "recommendations" => 1,
                                ]),
                                "enabled" => true
                            ),
                            array(
                                "name" => "Premium",
                                "role_id" => $role->id,
                                "features" => json_encode([
                                    "package_amount" => "20.550",
                                    "montly_publications" => -1,
                                    "photos_per_project" => 15,
                                    "videos_per_project" => 1,
                                    "project_due_days" => 30,
                                    "owner_contact" => 2,
                                    "public_support" => true,
                                    "recommendations" => 2,
                                ]),
                                "enabled" => true
                            ),
                            array(
                                "name" => "Default",
                                "role_id" => $role->id,
                                "features" => json_encode(["hey" => "ho"]),
                                "enabled" => false
                            ),
                        );

                        break;

                    case 4: # Service Memberships...
                        $memberships = array(
                            array(
                                "name" => "Basic",
                                "role_id" => $role->id,
                                "features" => json_encode([
                                    "package_amount" => "990",
                                    'services_counts' => -1,
                                    'photos_per_project' => 3,
                                    'videos_per_project' => 0,
                                    'project_due_days' => 5,
                                    'add_days' => 5,
                                    'owner_contact' => 0,
                                    'add_zones' => 0,
                                    'public_support' => 0,
                                    'service_fee' => 0,
                                    'trust_seal' => false,
                                    'recommendations' => 0,
                                    'main_services' => 1,
                                    'secondary_services' => 1
                                ]),
                                "enabled" => true
                            ),
                            array(
                                "name" => "Select",
                                "role_id" => $role->id,
                                "features" => json_encode([
                                    "package_amount" => "3.250",
                                    'services_counts' => -1,
                                    'photos_per_project' => 5,
                                    'videos_per_project' => 0,
                                    'project_due_days' => 15,
                                    'add_days' => 15,
                                    'owner_contact' => 1,
                                    'add_zones' => 0,
                                    'public_support' => 0,
                                    'service_fee' => 0,
                                    'trust_seal' => false,
                                    'recommendations' => 1,
                                    'main_services' => 2,
                                    'secondary_services' => 4
                                ]),
                                "enabled" => true
                            ),
                            array(
                                "name" => "Premium",
                                "role_id" => $role->id,
                                "features" => json_encode([
                                    "package_amount" => "6.550",
                                    'services_counts' => -1,
                                    'photos_per_project' => 10,
                                    'videos_per_project' => 1,
                                    'project_due_days' => 30,
                                    'add_days' => 30,
                                    'owner_contact' => 2,
                                    'add_zones' => 2,
                                    'public_support' => 1,
                                    'service_fee' => 0,
                                    'trust_seal' => true,
                                    'recommendations' => 2,
                                    'main_services' => 3,
                                    'secondary_services' => 9
                                ]),
                                "enabled" => true
                            ),
                            array(
                                "name" => "Default",
                                "role_id" => $role->id,
                                "features" => json_encode(["hey" => "ho"]),
                                "enabled" => false
                            ),
                        );

                        break;

                    default:
                        # code...
                        break;
                }
                DB::table("memberships")->insert($memberships);
            }else {
                $memberships = array(
                    array(
                        "name" => "Default",
                        "role_id" => $role->id,
                        "features" => json_encode(["hey" => "ho"]),
                        "enabled" => false
                    ),
                );
                DB::table("memberships")->insert($memberships);
            }
        }
    }
}
