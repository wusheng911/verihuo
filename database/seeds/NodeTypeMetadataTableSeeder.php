<?php

use Illuminate\Database\Seeder;

class NodeTypeMetadataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('node_type_metadata')->truncate();
        //Metadata for NodeType:Slide Item
        DB::table('node_type_metadata')->insert([
            'node_type_id' => '1',
            'seq_no' => '1',
            'node_attr_id' => '1',
            'cardinality' => 'one',
            'editable' => 'default',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        DB::table('node_type_metadata')->insert([
            'node_type_id' => '1',
            'seq_no' => '2',
            'node_attr_id' => '2',
            'cardinality' => 'one',
            'editable' => 'default',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        DB::table('node_type_metadata')->insert([
            'node_type_id' => '1',
            'seq_no' => '3',
            'node_attr_id' => '4',
            'cardinality' => 'one',
            'editable' => 'default',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        //Metadata for NodeType:Content Item
        DB::table('node_type_metadata')->insert([
            'node_type_id' => '2',
            'seq_no' => '1',
            'node_attr_id' => '3',
            'cardinality' => 'one',
            'editable' => 'default',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        //Metadata for NodeType:Banner
        DB::table('node_type_metadata')->insert([
            'node_type_id' => '3',
            'seq_no' => '1',
            'node_attr_id' => '1',
            'cardinality' => 'one',
            'editable' => 'default',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        DB::table('node_type_metadata')->insert([
            'node_type_id' => '3',
            'seq_no' => '2',
            'node_attr_id' => '2',
            'cardinality' => 'one',
            'editable' => 'default',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);   
        DB::table('node_type_metadata')->insert([
            'node_type_id' => '3',
            'seq_no' => '3',
            'node_attr_id' => '4',
            'cardinality' => 'one',
            'editable' => 'default',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);  
        //Metadata for NodeType:BannerNoRadius
        DB::table('node_type_metadata')->insert([
            'node_type_id' => '4',
            'seq_no' => '1',
            'node_attr_id' => '1',
            'cardinality' => 'one',
            'editable' => 'default',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
        DB::table('node_type_metadata')->insert([
            'node_type_id' => '4',
            'seq_no' => '2',
            'node_attr_id' => '2',
            'cardinality' => 'one',
            'editable' => 'default',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);   
        DB::table('node_type_metadata')->insert([
            'node_type_id' => '4',
            'seq_no' => '3',
            'node_attr_id' => '4',
            'cardinality' => 'one',
            'editable' => 'default',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);  
        //Metadata for NodeType:Shop Cover Flow
        DB::table('node_type_metadata')->insert([
            'node_type_id' => '6',
            'seq_no' => '1',
            'node_attr_id' => '5',
            'cardinality' => 'many',
            'editable' => 'default',
            'created_at' => Carbon\Carbon::now(),
            'updated_at' => Carbon\Carbon::now()
        ]);
    }
}
