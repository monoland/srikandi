<?php

use App\Models\Control;
use App\Models\Item;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class SyncControlTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // vehicles
        $vehicles = Vehicle::withCount('controls')->get();

        foreach ($vehicles as $vehicle) {
            if ($vehicle->controls_count > 0) {
                continue;
            }

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

        // items
        $items = Item::withCount('controls')->get();

        foreach ($items as $item) {
            if ($item->controls_count > 0) {
                continue;
            }

            $vehicles = Vehicle::where('kind', $item->kind)->get();
            $controls = [];

            foreach ($vehicles as $vehicle) {
                array_push(
                    $controls, new Control([
                        'vehicle_id' => $vehicle->id, 
                        'year' => date('Y'),
                        'maxi' => $item->maxi,
                        'blnc' => $item->maxi
                    ])
                );
            }

            if (count($controls) > 0) {
                $item->controls()->saveMany($controls);
            }
        }
    }
}
