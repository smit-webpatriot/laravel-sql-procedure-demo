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
            CREATE OR REPLACE PROCEDURE create_project(
                IN p_name VARCHAR(255),
                IN p_description TEXT,
                IN p_frontend VARCHAR(255),
                IN p_backend VARCHAR(255),
                IN p_database VARCHAR(255),
                IN p_technologies TEXT,
                IN p_keywords TEXT
            )
            LANGUAGE plpgsql
            AS $$
            DECLARE
                v_project_id INT;
                v_frontend_id INT;
                v_backend_id INT;
                v_database_id INT;
            BEGIN
                v_frontend_id := get_technology_id(p_frontend);
                v_backend_id := get_technology_id(p_backend);
                v_database_id := get_technology_id(p_database);

                INSERT INTO projects (
                    name, 
                    description, 
                    frontend_id,
                    backend_id,
                    database_id,
                    created_at, 
                    updated_at
                ) VALUES (
                    p_name, 
                    p_description, 
                    v_frontend_id,
                    v_backend_id,
                    v_database_id,
                    NOW(), 
                    NOW()
                );

                SELECT LASTVAL() INTO v_project_id;

                IF v_project_id IS NULL THEN
                    RETURN;
                END IF;

                CALL sync_project_technology(v_project_id, p_technologies);
                CALL sync_project_keyword(v_project_id, p_keywords);
            END;
            $$;
        ');
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS create_project');
    }
};
