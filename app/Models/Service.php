<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use App\Http\Resources\ServiceResource;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
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
            'used',
            'blnc',
            'aprv',
            'exmn',
            'notes'
        )->orderBy('item_service.id');
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
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
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
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
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
     * Undocumented function
     *
     * @param [type] $query
     * @param [type] $request
     * @return void
     */
    public function scopeFilterOn($query, $request)
    {
        $sortaz = $request->sortDesc === 'true' ? 'desc' : 'asc';
        $sortby = $request->has('sortBy') ? $request->sortBy : null;
        $search = $request->has('search') ? strtolower($request->search) : null;
        // $filton = $request->has('filterOn') ? $request->filterOn : null;
        // $filtby = $request->has('filterBy') ? $request->filterBy : null;

        $mixquery = $query;

        $authent = $request->user()->authent->name;

        switch ($authent) {
            case 'operator':
                $mixquery
                    ->where('agency_id', $request->user()->userable->id)
                    ->whereNotIn('status', ['invoiced']);
                break;

            case 'kabiro':
                $mixquery
                    ->where('agency_id', $request->user()->userable->id)
                    ->whereIn('status', ['dispositioned']);
                break;

            case 'pptk':
                $mixquery
                    ->whereIn('status', ['submissioned']);
                break;

            case 'kpa':
                $mixquery
                    ->whereIn('status', ['examined']);
                break;

            case 'tata-usaha':
                $mixquery
                    ->whereIn('status', ['printed']);
                break;
        }

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
     * Undocumented function
     *
     * @param [type] $request
     * @return void
     */
    public static function storeRecord($request)
    {
        DB::beginTransaction();

        try {
            $model = new static;
            $model->vehicle_id = $request->vehicle['value'];
            $model->police_id = $request->vehicle['police_id'];
            $model->agency_id = $request->vehicle['agency_id'];
            $model->periode = $request->periode;
            $model->year = date('Y');
            $model->status = 'drafted';
            $model->user_id = $request->user()->id;
            $model->save();

            if (is_array($request->details) && count($request->details)) {
                $items = [];

                foreach ($request->details as $item) {
                    $items[$item['value']] = [
                        'name' => $item['text'],
                        'unit' => $item['unit'],
                        'used' => $item['used'],
                        'blnc' => $item['blnc']
                    ];
                }

                $model->items()->sync($items);
            }

            DB::commit();

            return new ServiceResource($model);
        } catch (\Exception $e) {
            DB::rollBack();

            abort(500, $e->getMessage());
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @param [type] $model
     * @return void
     */
    public static function updateRecord($request, $model)
    {
        DB::beginTransaction();

        try {
            $model->periode = $request->periode;
            $model->year = date('Y');
            $model->status = 'drafted';
            $model->user_id = $request->user()->id;
            $model->save();

            if (is_array($request->details) && count($request->details)) {
                $items = [];

                foreach ($request->details as $item) {
                    $items[$item['value']] = [
                        'name' => $item['text'],
                        'unit' => $item['unit'],
                        'used' => $item['used'],
                        'blnc' => $item['blnc'],
                    ];
                }

                $model->items()->sync($items);
            }

            DB::commit();

            return new ServiceResource($model);
        } catch (\Exception $e) {
            DB::rollBack();

            abort(500, $e->getMessage());
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $model
     * @return void
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
     * Undocumented function
     *
     * @param [type] $request
     * @param [type] $model
     * @return void
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

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @param [type] $model
     * @return void
     */
    public static function updateDrafted($request, $model)
    {
        DB::beginTransaction();

        try {
            // root biro
            $rootAgency = Agency::where('root', true)->first();

            if ($request->user()->userable->id === $rootAgency->id) {
                $model->status = 'submissioned';
            } else {
                $model->status = 'dispositioned';
            }

            $model->save();

            DB::commit();

            return new ServiceResource($model);
        } catch (\Exception $e) {
            DB::rollBack();

            abort(500, $e->getMessage());
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @param [type] $model
     * @return void
     */
    public static function updateDispositioned($request, $model)
    {
        DB::beginTransaction();

        try {
            $model->status = 'submissioned';
            $model->save();

            DB::commit();

            return new ServiceResource($model);
        } catch (\Exception $e) {
            DB::rollBack();

            abort(500, $e->getMessage());
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @param [type] $model
     * @return void
     */
    public static function updateSubmissioned($request, $model)
    {
        DB::beginTransaction();

        try {
            $model->garage_id = $request->garage['value'];
            $model->notes = $request->notes;
            $model->status = 'examined';
            $model->save();

            if (is_array($request->details) && count($request->details)) {
                $items = [];

                foreach ($request->details as $item) {
                    if (array_key_exists('exmn', $item) && $item['exmn'] === true && $item['aprv'] === false) {
                        continue;
                    }

                    $items[$item['value']] = [
                        'name' => $item['text'],
                        'unit' => $item['unit'],
                        'used' => $item['used'],
                        'blnc' => $item['blnc'],
                        'aprv' => true,
                    ];
                }

                $model->items()->syncWithoutDetaching($items);
                $model->items()->newPivotStatement()->update(['exmn' => true]);
            }

            DB::commit();

            return new ServiceResource($model);
        } catch (\Exception $e) {
            DB::rollBack();

            abort(500, $e->getMessage());
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @param [type] $model
     * @return void
     */
    public static function updateExamined($request, $model)
    {
        DB::beginTransaction();

        try {
            $model->status = 'approved';
            $model->save();

            DB::commit();

            return new ServiceResource($model);
        } catch (\Exception $e) {
            DB::rollBack();

            abort(500, $e->getMessage());
        }
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @param [type] $model
     * @return void
     */
    public static function updateApproved($request, $model)
    {
        DB::beginTransaction();

        try {
            $model->status = 'printed';
            $model->save();

            DB::commit();

            return new ServiceResource($model);
        } catch (\Exception $e) {
            DB::rollBack();

            abort(500, $e->getMessage());
        }
    }
}
