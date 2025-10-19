<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) user_id カラムが無ければ追加（NULL許可）
        if (!Schema::hasColumn('questions', 'user_id')) {
            Schema::table('questions', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id');
            });
        } else {
            // 2) 既にある場合、NULL許可にしておく（外部キー張りやすくする）
            // doctrine/dbal が無くても通るように生SQLで対応（MySQL想定）
            DB::statement('ALTER TABLE `questions` MODIFY `user_id` BIGINT UNSIGNED NULL');
        }

        // 3) 外部キーが未設定なら追加（ユーザー削除時は user_id を NULL に）
        // 既に存在する場合のために try/catch
        try {
            Schema::table('questions', function (Blueprint $table) {
                $table->foreign('user_id')
                        ->references('id')->on('users')
                        ->nullOnDelete(); // ユーザー削除 → user_id をNULL
            });
        } catch (\Throwable $e) {
            // 既に外部キーがある場合はスルー
        }
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            try { $table->dropForeign(['user_id']); } catch (\Throwable $e) {}
            try { $table->dropColumn('user_id'); } catch (\Throwable $e) {}
        });
    }
};
