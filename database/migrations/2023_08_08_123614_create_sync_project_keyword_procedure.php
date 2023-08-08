<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE OR REPLACE PROCEDURE sync_project_keyword(
                IN p_project_id INT,
                IN p_keywords TEXT
            )
            LANGUAGE plpgsql
            AS $$
            DECLARE
                v_keyword_id INT;
                v_keyword_name VARCHAR(255);
                v_keyword_names TEXT[];
                v_keyword_names_count INT;
                v_keyword_names_index INT;
            BEGIN
                v_keyword_names := string_to_array(p_keywords, \',\');
                v_keyword_names_count := array_length(v_keyword_names, 1);

                IF v_keyword_names_count = 0 THEN
                    RETURN;
                END IF;

                FOR v_keyword_names_index IN 1..v_keyword_names_count LOOP
                    v_keyword_name := v_keyword_names[v_keyword_names_index];

                    v_keyword_id = get_keyword_id(v_keyword_name);

                    IF v_keyword_id IS NULL THEN
                        CONTINUE;
                    END IF;

                    INSERT INTO project_keywords (project_id, keyword_id) VALUES (p_project_id, v_keyword_id);
                END LOOP;
            END;
            $$;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sync_project_keyword');
    }
};
