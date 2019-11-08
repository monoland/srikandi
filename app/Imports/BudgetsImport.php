<?php

namespace App\Imports;

use App\Models\Budget;
use App\Models\Type;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BudgetsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $type = Type::where('slug', Str::slug($row['name']))->first();
        
        return new Budget([
            'year' => $row['year'],
            'maxi' => $row['maxi'],
            'warn' => $row['warn'],
            'type_id' => $type->id,
        ]);
    }
}
