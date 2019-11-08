<?php

use App\Imports\BudgetsImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class BudgetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new BudgetsImport(), storage_path('seeds'.DIRECTORY_SEPARATOR.'budgets.xlsx'));
    }
}
