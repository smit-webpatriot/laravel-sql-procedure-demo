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
            CREATE OR REPLACE PROCEDURE sync_project_technology(
                IN p_project_id INT,
                IN p_technologies TEXT
            )
            LANGUAGE plpgsql
            AS $$
            DECLARE
                v_technology_id INT;
                v_technology_name VARCHAR(255);
                v_technology_names TEXT[];
                v_technology_names_count INT;
                v_technology_names_index INT;
            BEGIN
                v_technology_names := string_to_array(p_technologies, \',\');
                v_technology_names_count := array_length(v_technology_names, 1);

                IF v_technology_names_count = 0 THEN
                    RETURN;
                END IF;

                FOR v_technology_names_index IN 1..v_technology_names_count LOOP
                    v_technology_name := v_technology_names[v_technology_names_index];

                    v_technology_id = get_technology_id(v_technology_name);

                    IF v_technology_id IS NULL THEN
                        CONTINUE;
                    END IF;

                    INSERT INTO project_technologies (project_id, technology_id) VALUES (p_project_id, v_technology_id);
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
        DB::unprepared('DROP PROCEDURE IF EXISTS sync_project_technology');
    }
};
