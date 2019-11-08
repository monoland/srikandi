<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Resources\BudgetCollection;
use App\Models\Budget;
use App\Models\Type;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Type $type)
    {
        $this->authorize('viewAny', Budget::class);

        return new BudgetCollection(
            $type->budgets()->filterOn($request)->paginate($request->itemsPerPage)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Type $type)
    {
        $this->authorize('create', Budget::class);

        $this->validate($request, [
            //
        ]);

        return Budget::storeRecord($request, $type);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Type  $type
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type, Budget $budget)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Type  $type
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type, Budget $budget)
    {
        $this->authorize('update', $budget);

        $this->validate($request, [
            //
        ]);

        return Budget::updateRecord($request, $budget);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Type  $type
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type, Budget $budget)
    {
        $this->authorize('delete', $budget);

        return Budget::destroyRecord($budget);
    }

    /**
     * Remove the bulkdelete resource from storage.
     *
     * @param  \App\Models\Budget  $budget
     * @return \Illuminate\Http\Response
     */
    public function bulkdelete(Request $request)
    {
        $this->authorize('bulkDelete', Budget::class);
        
        return Budget::bulkDelete($request);
    }

    /**
     * Display a listing for combo
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function combo(Request $request)
    {
        return Budget::fetchCombo($request);
    }
}
