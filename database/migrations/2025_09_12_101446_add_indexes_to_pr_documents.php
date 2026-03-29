<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddIndexesToPrDocuments extends Migration
{
    public function up()
    {
        Schema::table('pr_documents', function ($table) {
            $table->index('reference_no');
            $table->index('document_date');
            $table->index(['pr_detail_id', 'pr_folder_name_id']);
        });

        // Prefix index (safe)
        try {
            DB::statement('CREATE INDEX pr_document_contents_content_index ON pr_document_contents (content(255))');
        } catch (\Exception $e) {
        }

        Schema::table('short_urls', function ($table) {
            $table->index('original_url');
            $table->index('short_code');
        });
    }

    public function down()
    {
        // =========================
        // 🔹 HANDLE pr_documents
        // =========================

        // 1️⃣ Get foreign keys dynamically
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'pr_documents'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        // Drop all foreign keys safely
        foreach ($foreignKeys as $fk) {
            try {
                DB::statement("ALTER TABLE pr_documents DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
            } catch (\Exception $e) {
            }
        }

        // 2️⃣ Get indexes
        $indexes = DB::select("SHOW INDEX FROM pr_documents");
        $indexNames = array_unique(array_column($indexes, 'Key_name'));

        // 3️⃣ Drop indexes safely
        $dropIndexes = [
            'pr_documents_reference_no_index',
            'pr_documents_document_date_index',
            'pr_documents_pr_detail_id_pr_folder_name_id_index',
        ];

        foreach ($dropIndexes as $index) {
            if (in_array($index, $indexNames)) {
                try {
                    DB::statement("DROP INDEX {$index} ON pr_documents");
                } catch (\Exception $e) {
                }
            }
        }

        // =========================
        // 🔹 pr_document_contents
        // =========================
        try {
            DB::statement('DROP INDEX pr_document_contents_content_index ON pr_document_contents');
        } catch (\Exception $e) {
        }

        // =========================
        // 🔹 short_urls
        // =========================
        $indexes = DB::select("SHOW INDEX FROM short_urls");
        $indexNames = array_unique(array_column($indexes, 'Key_name'));

        $dropIndexes = [
            'short_urls_original_url_index',
            'short_urls_short_code_index',
        ];

        foreach ($dropIndexes as $index) {
            if (in_array($index, $indexNames)) {
                try {
                    DB::statement("DROP INDEX {$index} ON short_urls");
                } catch (\Exception $e) {
                }
            }
        }
    }
}
