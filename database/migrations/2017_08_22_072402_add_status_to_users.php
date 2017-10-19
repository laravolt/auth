<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToUsers extends Migration
{
    protected $table;

    protected $column = 'status';

    protected $columnExists;

    /**
     * AddStatusToUsers constructor.
     */
    public function __construct()
    {
        $this->table = app(config('auth.providers.users.model'))->getTable();
        $this->columnExists = Schema::hasColumn($this->table, $this->column);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->table, function (Blueprint $table) {
            if (!$this->columnExists) {
                $table->string($this->column)->after('email')->index()->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->table, function (Blueprint $table) {
            if ($this->columnExists) {
                $table->dropColumn($this->column);
            }
        });
    }
}
