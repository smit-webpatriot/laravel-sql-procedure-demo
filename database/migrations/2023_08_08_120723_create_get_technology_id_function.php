<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE OR REPLACE FUNCTION get_technology_id(
                IN p_name VARCHAR(255)
            ) RETURNS INT
            LANGUAGE plpgsql
            AS $$
            DECLARE
                p_id INT;
            BEGIN
                p_id := NULL;

                IF p_name IS NULL THEN
                    RETURN p_id;
                END IF;
            
                p_name := TRIM(p_name);
        
                IF LENGTH(p_name) = 0 THEN
                    RETURN p_id;
                END IF;

                SELECT id INTO p_id FROM technologies WHERE name = p_name;

                IF p_id IS NULL THEN
                    INSERT INTO technologies (name, created_at, updated_at) VALUES (p_name, NOW(), NOW());
                    SELECT LASTVAL() INTO p_id;
                END IF;
                    
                RETURN p_id;
            END;
            $$;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS get_technology_id');
    }
};
