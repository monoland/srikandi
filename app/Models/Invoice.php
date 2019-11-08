<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\InvoiceResource;

class Invoice extends Model
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * Undocumented function
     *
     * @return void
     */
    public function items()
    {
        return $this->belongsToMany(Item::class)->withPivot(
            'name',
            'unit',
            'qty',
            'price',
            'amount',
            'notes'
        )->orderBy('invoice_item.id');
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function police()
    {
        return $this->belongsTo(Police::class);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function garage()
    {
        return $this->belongsTo(Garage::class);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Scope for combo
     */
    public function scopeFetchCombo($query)
    {
        return $query->select(
            'name AS text', 'id AS value'
        )->get();
    }

    /**
     * Scope for filter
     */
    public function scopeFilterOn($query, $request)
    {
        $sortaz = $request->sortDesc === 'true' ? 'desc' : 'asc';
        $sortby = $request->has('sortBy') ? $request->sortBy : null;
        $search = $request->has('search') ? strtolower($request->search) : null;
        // $filton = $request->has('filterOn') ? $request->filterOn : null;
        // $filtby = $request->has('filterBy') ? $request->filterBy : null;

        $mixquery = $query;

        if ($search) {
            $mixquery = $mixquery->whereRaw("LOWER(name) LIKE '%{$search}%'");
        }

        // if ($filtby) {
        //     $mixquery = $mixquery->whereRaw("{$filton} = '{$filtby}'");
        // }

        if ($sortby) {
            $mixquery = $mixquery->orderBy($sortby, $sortaz);
        }

        return $mixquery;
    }

    /**
     * Store
     */
    public static function storeRecord($request, $parent)
    {
        DB::beginTransaction();

        try {
            $model = new static;
            $model->vehicle_id = $parent->vehicle_id;
            $model->police_id = $parent->police_id;
            $model->agency_id = $parent->agency_id;
            $model->garage_id = $parent->garage_id;
            $model->periode = $parent->periode;
            $model->refsinv = $request->refsinv;
            $model->dateinv = $request->dateinv;
            $model->subtotal = $request->subtotal;
            $model->discount = $request->discount;
            $model->tax = $request->tax;
            $model->total = $request->total;
            
            // save invoice
            $parent->invoice()->save($model);

            // save service
            $parent->status = 'invoiced';
            $parent->save();
            
            if (is_array($request->details) && count($request->details)) {
                $items = [];

                foreach ($request->details as $item) {
                    // update control
                    $model->vehicle->controls()
                        ->where('item_id', $item['value'])
                        ->where('year', date('Y'))
                        ->update(['used' => $item['used']]);

                    // save pivot
                    $items[$item['value']] = [
                        'name' => $item['text'],
                        'unit' => $item['unit'],
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'amount' => $item['amount'],
                        'notes' => $item['notes']
                    ];
                }

                $model->items()->sync($items);
            }

            DB::commit();

            return new InvoiceResource($model);
        } catch (\Exception $e) {
            DB::rollBack();

            abort(500, $e->getMessage());
        }
    }

    /**
     * Update
     */
    public static function updateRecord($request, $model)
    {
        DB::beginTransaction();

        try {
            // ...
            $model->save();

            DB::commit();

            return new InvoiceResource($model);
        } catch (\Exception $e) {
            DB::rollBack();

            abort(500, $e->getMessage());
        }
    }

    /**
     * Destroy
     */
    public static function destroyRecord($model)
    {
        DB::beginTransaction();

        try {
            $model->delete();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();

            abort(500, $e->getMessage());
        }
    }

    /**
     * Bulks
     */
    public static function bulkDelete($request, $model = null)
    {
        DB::beginTransaction();

        try {
            $bulks = array_column($request->all(), 'id');
            $rests = (new static)->whereIn('id', $bulks)->delete();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();

            abort(500, $e->getMessage());
        }
    }
}
