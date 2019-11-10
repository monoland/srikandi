<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\VehicleResource;

class Vehicle extends Model
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
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function balances()
    {
        return $this->hasMany(Balance::class);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function balance()
    {
        return $this->hasOne(Balance::class)->where('year', date('Y'));
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
    public function police()
    {
        return $this->hasOne(Police::class)->where('active', true);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function polices()
    {
        return $this->hasMany(Police::class);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function controls()
    {
        return $this->hasMany(Control::class);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Scope for combo
     */
    public function scopeFetchCombo($query)
    {
        return $query
            ->join('agencies', 'agencies.id', '=', 'vehicles.agency_id')
            ->join('police', 'police.vehicle_id', '=', 'vehicles.id')
            ->join('types', 'types.id', '=', 'vehicles.type_id')
            ->select(
                DB::raw("CONCAT(police.id, ' - ', agencies.name, ' a/n ', police.name) AS text"), 'vehicles.agency_id', 'police.id as police_id', 'types.kind', 'vehicles.id AS value'
            )->get();
    }

    /**
     * Undocumented function
     *
     * @param [type] $query
     * @return void
     */
    public function scopeFilterKind($query, $kind)
    {
        return $query->where('kind', $kind);
    }

    /**
     * Undocumented function
     *
     * @param [type] $query
     * @param [type] $user
     * @return void
     */
    public function scopeFilterUser($query, $user)
    {
        if (optional($user->userable)->id) {
            if ($user->isKPA() || $user->isPPTK()) {
                return $query;        
            }
            
            return $query->where('agency_id', $user->userable->id);
        }
        
        return $query;
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

            // create vehicle
            $model->year = $request->year;
            $model->frame_numb = $request->frame_numb;
            $model->machine_numb = $request->machine_numb;
            $model->brand_id = $parent->brand->id;
            $model->refs_numb = $request->refs_numb;
            $model->agency_id = $request->agency_id;
            $model->condition = $request->condition;
            $model->kind = $parent->kind;

            $parent->vehicles()->save($model);

            // find or create-new police
            $police = Police::findOrNew($request->police_id);
            $police->id = $request->police_id;
            $police->name = $request->name;
            $police->color = $request->color;
            $police->taxdue = $request->taxdue;
            $police->taxsum = $request->taxsum;
            $police->active = true;

            $model->polices()->save($police);

            // get budget
            $budget = $parent->budgets()->where('year', '=', $request->year)->first();
            
            // set balance
            $balance = Balance::firstOrNew([
                'vehicle_id' => $model->id,
                'year' => date('Y')
            ]);
            $balance->maxi = $budget->maxi;
            $balance->warn = $budget->warn;
            $balance->blnc = $budget->maxi;

            $model->balances()->save($balance);

            DB::commit();

            return new VehicleResource($model);
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
            // find police-numb and set active = false
            $old_police = $model->polices()->where('active', true)->first();
            
            if ($old_police) {
                $old_police->active = false;
                $old_police->save();
            }

            // find or create-new police
            $police = Police::findOrNew($request->police_id);
            $police->id = $request->police_id;
            $police->name = $request->name;
            $police->color = $request->color;
            $police->taxdue = $request->taxdue;
            $police->taxsum = $request->taxsum;
            $police->active = true;

            $model->polices()->save($police);

            if ($request->year !== $model->year) {
                // get budget
                $budget = $model->type->budgets()->where('year', '=', $request->year)->first();

                // update existing balance
                $balance = $model->balance; 
                $balance->maxi = $budget->maxi;
                $balance->warn = $budget->warn;
                $balance->blnc = $budget->maxi;

                $model->balances()->save($balance);
            }

            // update vehicle
            $model->year = $request->year;
            $model->frame_numb = $request->frame_numb;
            $model->machine_numb = $request->machine_numb;
            $model->refs_numb = $request->refs_numb;
            $model->agency_id = $request->agency_id;
            $model->condition = $request->condition;
            $model->save();

            DB::commit();

            return new VehicleResource($model);
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
