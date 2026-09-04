<?php

namespace database\seeds;

use App\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            'integer', 'decimal', 'boolean', 'string'
        ];

        for($i=0; $i<count($types); $i++) {
            $type = new Type();
            $type->name = $types[$i];
            $type->save();
        }
    }
}