<?php

namespace App\Http\Controllers;

use App\Http\Requests\Leads\LeadStoreRequest;
use App\Http\Requests\Leads\LeadUpdateRequest;
use App\Models\Lead;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leads = Lead::with('convertedToCustomer')->get();
        return Inertia::render('leads/Index', [
            'leads' => $leads
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('leads/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeadStoreRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $validatedData['user_id'] = auth()->id();
            $validatedData['status'] = 'new';
            $lead = Lead::create($validatedData);
            return redirect()->route('leads.index')->with('success', 'Lead created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create lead.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        return Inertia::render('leads/Show', [
            'lead' => $lead
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        return Inertia::render('leads/Edit', [
            'lead' => $lead
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LeadUpdateRequest $request, Lead $lead)
    {
        $validatedData = $request->validated();

        try {
            $validatedData['user_id'] = auth()->id();
            $lead->update($validatedData);
            return redirect()->route('leads.index')->with('success', 'Lead updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update lead.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        try {
            $lead->delete();
            return redirect()->route('leads.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
