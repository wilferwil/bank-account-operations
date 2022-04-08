<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountEventsTable extends Migration
{
    private $tableName = 'bank_account_events';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::hasTable($this->tableName) ?: Schema::create($this->tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['deposit', 'withdraw', 'transfer']);
            $table->integer('origin')->nullable();
            $table->integer('destination')->nullable();
            $table->decimal('amount');
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
        Schema::dropIfExists($this->tableName);
    }
}
