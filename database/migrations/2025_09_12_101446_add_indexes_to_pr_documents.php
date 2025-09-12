<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddIndexesToPrDocuments extends Migration
{
    public function up()
    {
        Schema::table('pr_documents', function (Blueprint $table) {
            // Add indexes for frequently queried columns
            $table->index('pr_detail_id');
            $table->index('pr_folder_name_id');
            $table->index('reference_no');
            $table->index('document_date');
            $table->index(['pr_detail_id', 'pr_folder_name_id']);
        });

        Schema::table('pr_document_contents', function (Blueprint $table) {
            $table->index('pr_document_id');
        });

        // Create prefix index for content column using raw SQL
        DB::statement('CREATE INDEX pr_document_contents_content_index ON pr_document_contents (content(255))');

        Schema::table('short_urls', function (Blueprint $table) {
            $table->index('original_url');
            $table->index('short_code');
        });
    }

    public function down()
    {
        Schema::table('pr_documents', function (Blueprint $table) {
            $table->dropIndex(['pr_detail_id']);
            $table->dropIndex(['pr_folder_name_id']);
            $table->dropIndex(['reference_no']);
            $table->dropIndex(['document_date']);
            $table->dropIndex(['pr_detail_id', 'pr_folder_name_id']);
        });

        Schema::table('pr_document_contents', function (Blueprint $table) {
            $table->dropIndex(['pr_document_id']);
        });

        // Drop the prefix index
        DB::statement('DROP INDEX pr_document_contents_content_index ON pr_document_contents');

        Schema::table('short_urls', function (Blueprint $table) {
            $table->dropIndex(['original_url']);
            $table->dropIndex(['short_code']);
        });
    }
}
