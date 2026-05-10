<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('polls', function (Blueprint $table) {
            $table->index('is_active', 'polls_is_active_index');
        });

        Schema::table('votes', function (Blueprint $table) {
            $table->index(['poll_id', 'option_id'], 'votes_poll_option_index');
        });

        DB::unprepared('DROP FUNCTION IF EXISTS fn_poll_total_votes');
        DB::unprepared(<<<'SQL'
            CREATE FUNCTION fn_poll_total_votes(p_poll_id BIGINT UNSIGNED)
            RETURNS INT
            DETERMINISTIC
            READS SQL DATA
            BEGIN
                DECLARE v_total INT;
                SELECT COUNT(*) INTO v_total FROM votes WHERE poll_id = p_poll_id;
                RETURN v_total;
            END
        SQL);

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_poll_results');
        DB::unprepared(<<<'SQL'
            CREATE PROCEDURE sp_get_poll_results(IN p_poll_id BIGINT UNSIGNED)
            BEGIN
                DECLARE v_total INT;
                SET v_total = fn_poll_total_votes(p_poll_id);

                SELECT
                    o.id            AS option_id,
                    o.text          AS option_text,
                    o.`order`       AS option_order,
                    COUNT(v.id)     AS votes_count,
                    IF(v_total > 0, ROUND(COUNT(v.id) / v_total * 100, 2), 0) AS percentage
                FROM options o
                LEFT JOIN votes v ON v.option_id = o.id
                WHERE o.poll_id = p_poll_id
                GROUP BY o.id, o.text, o.`order`
                ORDER BY o.`order` ASC;
            END
        SQL);

        DB::unprepared('DROP TRIGGER IF EXISTS trg_votes_before_insert');
        DB::unprepared(<<<'SQL'
            CREATE TRIGGER trg_votes_before_insert
            BEFORE INSERT ON votes
            FOR EACH ROW
            BEGIN
                DECLARE v_is_active BOOLEAN;

                SELECT is_active INTO v_is_active
                FROM polls
                WHERE id = NEW.poll_id;

                IF v_is_active = 0 THEN
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Cannot vote: poll is not active';
                END IF;
            END
        SQL);
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_votes_before_insert');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_get_poll_results');
        DB::unprepared('DROP FUNCTION IF EXISTS fn_poll_total_votes');

        Schema::table('votes', function (Blueprint $table) {
            $table->dropIndex('votes_poll_option_index');
        });

        Schema::table('polls', function (Blueprint $table) {
            $table->dropIndex('polls_is_active_index');
        });
    }
};
