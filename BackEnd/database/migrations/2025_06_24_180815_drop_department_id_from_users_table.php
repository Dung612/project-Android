<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // Tắt kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Xóa foreign key và cột department_id nếu tồn tại
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'department_id')) {
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $doctrineTable = $sm->listTableDetails('users');
                if ($doctrineTable->hasForeignKey('users_department_id_foreign')) {
                    $table->dropForeign('users_department_id_foreign');
                }
                $table->dropColumn('department_id');
            }
        });
        // Xóa bảng departments nếu còn
        Schema::dropIfExists('departments');
        // Bật lại kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
    public function down()
    {
        // Không cần rollback
    }
}; 