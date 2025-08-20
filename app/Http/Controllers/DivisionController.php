<?php

namespace App\Http\Controllers;

use App\Models\Division;
// use Illuminate\Contracts\Session\Session as Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Validator;

class DivisionController extends Controller
{

    public function searchDivision()
    {
        if ($search = \Request::get('name')) {
            $division = Division::where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            })->latest()->paginate(10);
        } else {
            $division = Division::latest()->paginate(10);
        }
        return response()->json($division);
    }
    public function getDivisions()
    {
        return response()->json(Division::latest()->paginate(2));
    }
    public function storeDivision(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);


        // Check if the role name already exists and is not soft deleted
        $checkDuplicate = Division::where('name', $request->name)->whereNull('deleted_at')->first();

        if ($checkDuplicate) {
            // Role exists and is active, so return validation error
            return back()->withErrors(['name' => 'A division/name with that name already exists.']);
        }

        $existing = Division::withTrashed()->where('name', $request->name)->first();

        if ($existing) {
            if ($existing->trashed()) {
                // Restore the soft-deleted role
                $existing->restore();

                Session::flash('success', 'Division/Unit created successfully');
            } else {
                Session::flash('error', 'Division/Unit with that name already exists.');
                return response();
            }
        } else {
            Division::create([
                'name' => $request->name,
                'user_id' => Auth::id(),
            ]);
            Session::flash('success', 'Division/Unit created successfully');
        }

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
        $division = Division::findOrFail($id);

        $division->delete();
        // Division::where('id', $id)->update([
        //     'deleted' => 1
        // ]);
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
        // $request->validate([
        //     'name' => 'required',
        // ]);

        $validation = Validator::make($request->all(), [
            'name'           => 'required',
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation);
        }

        // Check if the role name already exists and is not soft deleted
        $checkDuplicate = Division::where('name', $request->name)->whereNull('deleted_at')->first();

        if ($checkDuplicate) {
            // Role exists and is active, so return validation error
            return back()->withErrors(['name' => 'A division/name with that name already exists.'])->withInput();
        }

        $existing = Division::withTrashed()->where('name', $request->name)->first();

        if ($existing) {
            if ($existing->trashed()) {
                // Restore the soft-deleted role
                $existing->restore();

                Session::flash('success', 'Division/Unit created successfully');
            } else {
                Session::flash('error', 'Division/Unit with that name already exists.');
                return response();
            }
        } else {
            Division::create([
                'name' => $request->name,
            ]);
            Session::flash('success', 'Division/Unit created successfully');
        }

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
