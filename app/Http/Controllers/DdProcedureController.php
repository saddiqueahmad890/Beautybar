<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DdProcedure;
use App\Models\DdProcedureCategory;
use App\Models\UserLogs;

class DdProcedureController extends Controller

{
    public function index(Request $request)
    {
        $i = 1;
        $ddProcedures = $this->filter($request)->orderBy('created_at', 'desc')->get();
        return view('dd-procedure.index', compact('ddProcedures'));
    }

    public function filter(Request $request)
    {
        $query = DdProcedure::query();

        // Filter by procedure category
        if ($request->has('procedure_category') && $request->procedure_category !== '') {
            $query->whereHas('ddprocedurecategory', function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->procedure_category . '%');
            });
        }

        // Filter by title
        if ($request->has('title') && $request->title !== '') {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        return $query;
    }





    public function create()
    {
        $ddProcedureCategories = DdProcedureCategory::get();

        return view('dd-procedure.create', ['ddProcedures' => $ddProcedureCategories]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $this->validation($request);

        $procedureData = $request->only(['title', 'description', 'dd_procedure_id', 'price']);
        $procedureData['created_by'] = Auth::id();

        $procedure = new DdProcedure($procedureData);
        $procedure->save();
        $ddProcedure = $procedure->id;

        return redirect()->route('dd-procedures.edit', $ddProcedure)->with('success', trans('Services  created successfully'));
    }
    public function show(DdProcedure  $ddProcedure)
    {
        return view('dd-procedure.show', compact('ddProcedure'));
    }
    public function edit(DdProcedure  $ddProcedure)
    {
        $ddProcedureCategories = DdProcedureCategory::get();
        return view('dd-procedure.edit', compact('ddProcedure', 'ddProcedureCategories'));
    }


    public function update(Request $request, DdProcedure  $ddProcedure)

    {
        $this->validation($request);
        $data = $request->all();
        $data['updated_by'] = Auth::id();
        $ddProcedure->update($data);

        return redirect()->route('dd-procedures.edit', $ddProcedure)->with('success', trans('Services Updated successfully'));
    }


    public function destroy(DdProcedure  $ddProcedure)
    {
        $ddProcedure->delete();
        return redirect()->route('dd-procedures.index')->with('success', trans('Services Deleted Successfully'));
    }
    private function validation(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            'dd_procedure_id' => ['required', 'integer'],
            'price' => ['required', 'integer'],
        ], [
            'dd_procedure_id.required' => 'Service Category is required.',
        ]);
    }
}
