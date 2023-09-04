<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToDictionaryOrganizationRequisites extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dictionary_organization_requisites', function (Blueprint $table) {
            $table->string('header_of_contract')->nullable();
            $table->string('organization_ogrn')->nullable();
            $table->string('legal_address')->nullable();
            $table->string('email')->nullable();
            $table->string('web_site')->nullable();
            $table->string('phone')->nullable();
            $table->string('sign')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dictionary_organization_requisites', function (Blueprint $table) {
            $table->dropColumn('header_of_contract');
            $table->dropColumn('organization_ogrn');
            $table->dropColumn('legal_address');
            $table->dropColumn('email');
            $table->dropColumn('web_site');
            $table->dropColumn('phone');
            $table->dropColumn('sign');
        });
    }
}
