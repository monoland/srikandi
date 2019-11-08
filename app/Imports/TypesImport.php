<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Type;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TypesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $brand = Brand::where('slug', Str::slug($row['brand']))->first();

        return new Type([
            'name' => strtoupper($row['name']),
            'slug' => Str::slug($row['name']),
            'kind' => $row['kind'],
            'brand_id' => $brand->id
        ]);
    }
}
