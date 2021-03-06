<?php

use App\Imports\BrandsImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new BrandsImport(), storage_path('seeds'.DIRECTORY_SEPARATOR.'brands.xlsx'));
    }
}
