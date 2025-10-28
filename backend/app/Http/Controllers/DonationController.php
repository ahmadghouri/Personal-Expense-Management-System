<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DonationController extends Controller
{
    //
    public function index(Request $request)
    {

        $user = Auth::user();

        $perPage = $request->get('per_page', 10);

        if ($user->role === 'admin') {
            $donations = Donation::with(['category', 'user', 'donationType'])->paginate($perPage);
        } else {
            $donations = Donation::with(['category', 'user', 'donationType'])->where('user_id', $user->id)->latest()->paginate($perPage);
        }

        return response()->json([
            'donations' => $donations,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'donation_type_id' => 'required|exists:donation_types,id',
            'donation_date' => 'required|date',
            'amount' => 'nullable|numeric|min:0',
            'recipient_name' => 'nullable|string|max:255',
            'value' => 'nullable|string',
            'description' => 'nullable|string',
            'proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',

        ]);

        $path = null;

        if ($request->hasFile('proof')) {
            $path = $request->file('proof')->store('donations', 'public');
        }

        $donation = Donation::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'donation_type_id' => $request->donation_type_id,
            'recipient_name' => $request->recipient_name,
            'donation_date' => $request->donation_date,
            'amount' => $request->amount,
            'value' => $request->value,
            'description' => $request->description,
            'proof' => $path,

        ]);

        return response()->json([
            'message' => 'Donation added successfully',
            'donation' => $donation->load(['category', 'donationType']),
        ]);
    }

    public function update(Request $request, $id)
    {
        $donation = Donation::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $donation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'donation_type_id' => 'required|exists:donation_types,id',
            'donation_date' => 'required|date',
            'amount' => 'nullable|numeric|min:0',
            'recipient_name' => 'nullable|string|max:255',
            'value' => 'nullable|string',
            'description' => 'nullable|string',
            'proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($request->hasFile('proof')) {
            if ($donation->proof && Storage::disk('public')->exists($donation->proof)) {
                Storage::disk('public')->delete($donation->proof);
            }
            $donation->proof = $request->file('proof')->store('donations', 'public');
        }
        $donation->update($request->except('proof'));

        return response()->json(['message' => 'Donation updated successfully']);
    }

    public function destroy($id)
    {
        $donation = Donation::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $donation->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if ($donation->proof && Storage::disk('public')->exists($donation->proof)) {
            Storage::disk('public')->delete($donation->proof);
        }
        if ($donation->delete()) {
            return response()->json(['message' => 'Donation deleted successfully']);
        }
    }
}
