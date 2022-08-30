<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeadsDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('leads', static function (Blueprint $table) {
            $table->string('ward_lastname')->nullable();
            $table->string('ward_firstname')->nullable();
            $table->string('ward_patronymic')->nullable();
            $table->date('ward_birth_date')->nullable();
            $table->boolean('ward_inv')->nullable();
            $table->boolean('ward_hro')->nullable();
            $table->boolean('ward_uch')->nullable();
            $table->boolean('ward_spe')->nullable();
            $table->boolean('need_help')->nullable();

            $table->unsignedSmallInteger('region_id')->nullable();
            $table->unsignedBigInteger('subscription_id')->nullable();

            $table->foreign('region_id')->references('id')->on('dictionary_regions')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->restrictOnDelete()->cascadeOnUpdate();

            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('leads', static function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropForeign(['subscription_id']);

            $table->dropColumn('ward_lastname');
            $table->dropColumn('ward_firstname');
            $table->dropColumn('ward_patronymic');
            $table->dropColumn('ward_birth_date');
            $table->dropColumn('ward_inv');
            $table->dropColumn('ward_hro');
            $table->dropColumn('ward_uch');
            $table->dropColumn('ward_spe');
            $table->dropColumn('region_id');
            $table->dropColumn('need_help');
            $table->dropColumn('subscription_id');

            $table->unsignedBigInteger('client_id')->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->restrictOnDelete()->cascadeOnUpdate();
        });
    }
}
