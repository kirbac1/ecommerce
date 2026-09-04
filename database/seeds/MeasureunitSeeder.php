<?php

namespace database\seeds;

use App\Type;
use App\Measureunit;
use Illuminate\Database\Seeder;

class MeasureunitSeeder extends Seeder
{
    public function run()
    {
        $measures = [
            'pcs' => 'integer',
            'kg'  => 'decimal',
            'g'   => 'decimal',
            'cm'  => 'decimal',
            'm'   => 'decimal',
            'lt'  => 'decimal'
        ];

        foreach($measures as $key => $value)
        {
            $measure = new Measureunit();
            $measure->name = $key;
            $measure->type_id = Type::where('name', $value)->firstOrFail()->id;
            $measure->save();
        }
    }
}