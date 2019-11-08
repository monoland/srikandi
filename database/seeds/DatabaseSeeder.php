<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call(AuthentsTableSeeder::class);
        $this->call(SettingsTableSeeder::class);
        $this->call(AgenciesTableSeeder::class);
        $this->call(SegmentsTableSeeder::class);
        $this->call(ItemsTableSeeder::class);
        $this->call(BrandsTableSeeder::class);
        $this->call(TypesTableSeeder::class);
        $this->call(BudgetsTableSeeder::class);
        $this->call(VehiclesTableSeeder::class);
        $this->call(BalancesTableSeeder::class);
        $this->call(GaragesTableSeeder::class);
        $this->call(AgencyUsersTableSeeder::class);
        $this->call(ControlsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
