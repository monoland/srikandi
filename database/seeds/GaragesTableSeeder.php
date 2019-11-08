<?php

use App\Imports\GaragesImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class GaragesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new GaragesImport(), storage_path('seeds'.DIRECTORY_SEPARATOR.'garages.xlsx'));
    }
}
