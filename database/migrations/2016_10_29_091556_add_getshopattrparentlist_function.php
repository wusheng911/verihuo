<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGetshopattrparentlistFunction_20161029091556 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
            create function `getShopAttrParentList`(rootId int)   
            returns varchar(1000)   
            begin   
                declare fId varchar(100) default '';   
                declare str varchar(1000) default rootId;   

                while (rootId is not null) and (rootId <> 0)  do   
                    set fId = (SELECT pid from shop_attributes where id = rootId);   
                    if (fId is not null) and (fId <> 0) then
                        set str = concat(str, ',', fId);   
                        set rootId = fId;   
                    else   
                        SET rootId = fId;   
                    END IF;   
                end while;   
                return str;  
            END
SQL;
        DB::unprepared("drop function if exists getShopAttrParentList");
        DB::unprepared($sql); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("drop function if exists getShopAttrParentList");
    }
}
