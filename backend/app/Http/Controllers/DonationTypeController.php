<?php

namespace App\Http\Controllers;

use App\Models\DonationType;
use Illuminate\Http\Request;

class DonationTypeController extends Controller
{
    //
    public function index()
    {
        $type = DonationType::orderBy('id', 'asc')->get();

        return response()->json($type);
    }
   public function show()
    {
         $donations = DonationType::withCount('donations')
        ->withSum('donations', 'amount')
        ->get();

        return response()->json($donations);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:donation_types,name',
            'description' => 'nullable|string',
        ]);

        $type = DonationType::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Donation type created successfully',
            'data' => $type,
        ]);
    }

    public function update(Request $request, $id)
    {
        $type = DonationType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:donation_types,name,'.$id,
            'description' => 'nullable|string',
        ]);
        $type->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Donation type updated successfully',
            'data' => $type,
        ]);
    }

    public function destroy($id)
    {
        $type = DonationType::findOrFail($id);
        $type->delete();

        return response()->json(['message' => 'Donation Type deleted successfully']);
    }
}
