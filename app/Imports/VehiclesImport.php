<?php

namespace App\Imports;

use App\Models\Brand;
use App\Models\Police;
use App\Models\Vehicle;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VehiclesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $brand = Brand::where('slug', Str::slug($row['merek']))->first();
        $type = $brand->types()->where('slug', Str::slug($row['type']))->first();

        $vehicle = new Vehicle();
        $vehicle->frame_numb = $row['frame_numb'];
        $vehicle->machine_numb = $row['machine_numb'];
        $vehicle->brand_id = $brand->id;
        $vehicle->type_id = $type->id;
        $vehicle->kind = $type->kind;
        $vehicle->year = $row['year'];
        $vehicle->agency_id = $row['agency_id'];
        $vehicle->save();

        $police = new Police();
        $police->id = $row['police_id'];
        $police->name = strtoupper($row['name']);
        $police->color = $row['color'];
        $police->taxsum = $row['taxsum'];
        $police->active = true;
        $vehicle->polices()->save($police);

        return $vehicle;
    }
}
