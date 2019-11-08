<?php

use App\Models\Balance;
use App\Models\Budget;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class BalancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vehicles = Vehicle::all();

        foreach($vehicles as $vehicle) {
            $budget = Budget::where([
                'type_id' => $vehicle->type_id,
                'year' => $vehicle->year
            ])->first();

            if ($budget) {
                $balance = new Balance();
                $balance->year = date('Y');
                $balance->vehicle_id = $vehicle->id;
                $balance->maxi = $budget->maxi;
                $balance->warn = $budget->warn;
                $balance->blnc = $budget->maxi;
                $balance->save();
            }
        }
    }
}
