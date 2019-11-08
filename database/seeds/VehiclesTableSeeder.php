<?php

use App\Imports\VehiclesImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class VehiclesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new VehiclesImport(), storage_path('seeds'.DIRECTORY_SEPARATOR.'vehicles.xlsx'));
    }
}
