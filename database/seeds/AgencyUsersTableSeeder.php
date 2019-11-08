<?php

use App\Imports\UsersImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class AgencyUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new UsersImport(), storage_path('seeds'.DIRECTORY_SEPARATOR.'users.xlsx'));
    }
}
