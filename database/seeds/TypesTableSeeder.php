<?php

use App\Imports\TypesImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new TypesImport(), storage_path('seeds'.DIRECTORY_SEPARATOR.'types.xlsx'));
    }
}
