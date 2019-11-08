<?php

use App\Models\Control;
use App\Models\Item;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class ControlsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vehicles = Vehicle::get();

        foreach ($vehicles as $vehicle) {
            $items = Item::where('kind', $vehicle->kind)->get();
            $controls = [];

            foreach ($items as $item) {
                array_push(
                    $controls, new Control([
                        'item_id' => $item->id, 
                        'year' => date('Y'),
                        'maxi' => $item->maxi,
                        'blnc' => $item->maxi
                    ])
                );
            }

            if (count($controls) > 0) {
                $vehicle->controls()->saveMany($controls);
            }
        }
    }
}
