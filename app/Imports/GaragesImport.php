<?php

namespace App\Imports;

use App\Models\Garage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GaragesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Garage([
            'name' => strtoupper($row['name']),
            'slug' => Str::slug($row['name']),
            'address' => $row['address'],
            'phone' => $row['phone']
        ]);
    }
}
