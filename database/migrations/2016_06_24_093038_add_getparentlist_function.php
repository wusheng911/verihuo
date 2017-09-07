<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGetparentlistFunction_20160624093038 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
            create function `getParentList`(rootId int)   
            returns varchar(1000)   
            begin   
                declare fId varchar(100) default '';   
                declare str varchar(1000) default rootId;   

                while rootId is not null  do   
                    set fId = (SELECT pid from content_comments where id = rootId);   
                    if fId is not null then
                        set str = concat(str, ',', fId);   
                        set rootId = fId;   
                    else   
                        SET rootId = fId;   
                    END IF;   
                end while;   
                return str;  
            END
SQL;
        DB::unprepared("drop function if exists getParentList");
        DB::unprepared($sql);        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("drop function if exists getParentList");
    }
}
