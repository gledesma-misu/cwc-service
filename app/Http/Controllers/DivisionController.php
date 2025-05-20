<?php

namespace App\Http\Controllers;

use App\Models\Division;
// use Illuminate\Contracts\Session\Session as Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class DivisionController extends Controller
{
    
    public function getDivisions()
    {
        return response()->json(Division::where('deleted', 0)->latest()->get());
    }
    public function storeDivision(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        Division::create([
            'name' => $request->name,
            'user_id' => Auth::id(),
        ]);
        return response()->json('success');
    }
    public function updateDivision(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);
        Division::where('id', $id)->update([
            'name' => $request->name,

        ]);
        return response()->json('success');
    }
    public function deleteDivision($id)
    {
        Division::where('id', $id)->update([
            'deleted' => 1
        ]);
        return response()->json('success');
    }
    public function index()
    {
        $divisions = Division::all();
        return view('management.divisions.index', compact('divisions'));
    }

    public function create()
    {
        return view('management.divisions.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        Division::create([
            'name' => $request->name,
            'user_id' => 1
            // 'test' => $request->name,
        ]);
        Session::flash('success', 'Division created successfully');
        return redirect()->route('divisionsIndex');
    }

    public function edit($id)
    {
        $divisions = Division::find($id);
        return view('management.divisions.edit', compact('divisions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);
        Division::where('id', $id)->update([
            'name' => $request->name,

            // 'test' => $request->name,
        ]);
        Session::flash('success', 'Division updated successfully');
        return redirect()->route('divisionsIndex');
    }
}
