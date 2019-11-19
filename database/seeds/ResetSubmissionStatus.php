<?php

use App\Models\Service;
use Illuminate\Database\Seeder;

class ResetSubmissionStatus extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $services = Service::where('status', 'submissioned')->get();

        foreach ($services as $service) {
            $service->items()->newPivotStatement()->where('service_id', $service->id)->update(['exmn' => false]);
        }
    }
}
