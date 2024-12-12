<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        # Set lock wait timeout for simultaneous data manipulation
        // DB::statement('SET GLOBAL innodb_lock_wait_timeout = 10'); # DISABLED
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        # Optionally reset to default if necessary
        // DB::statement('SET GLOBAL innodb_lock_wait_timeout = DEFAULT');
    }
};
