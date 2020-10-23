<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableProperties3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->boolean('room_enablement')->nullable()->after('manage');
            $table->boolean('civil_work')->nullable()->after('manage');
            $table->boolean('arquitecture_project')->nullable()->after('manage');
            $table->boolean('work_electric_water')->nullable()->after('manage');
            $table->text('building_name')->nullable()->after('manage');
            $table->integer('level')->nullable()->after('manage');
            $table->integer('rooms')->nullable()->after('manage');
            $table->integer('meeting_room')->nullable()->after('manage');
            $table->text('exclusions')->nullable()->after('manage');
            $table->integer('term_year')->nullable()->after('manage');
            $table->integer('rent_year_1')->default(0)->after('manage');
            $table->integer('rent_year_2')->default(0)->after('manage');
            $table->integer('rent_year_3')->default(0)->after('manage');
            $table->integer('penalty_fees')->nullable()->after('manage');
            $table->boolean('warranty_ticket')->nullable()->after('manage');
            $table->integer('warranty_ticket_price')->nullable()->after('manage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'room_enablement',
                'civil_work',
                'arquitecture_project',
                'work_electric_water',
                'building_name',
                'level',
                'rooms',
                'meeting_room',
                'exclusions',
                'term_year',
                'rent_year_1',
                'rent_year_2',
                'rent_year_3',
                'penalty_fees',
                'warranty_ticket',
                'warranty_ticket_price']);
        });
    }
}
