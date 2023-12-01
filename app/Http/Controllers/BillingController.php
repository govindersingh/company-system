<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Project;
use App\Http\Requests\StoreBillingRequest;
use App\Http\Requests\UpdateBillingRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BillingController extends Controller
{

    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:show-billing|create-billing|edit-billing|delete-billing', ['only' => ['index','show']]);
       $this->middleware('permission:create-billing', ['only' => ['create','store']]);
       $this->middleware('permission:edit-billing', ['only' => ['edit','update']]);
       $this->middleware('permission:delete-billing', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('billings.index', [
            'billings' => Billing::latest()->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::get();
        return view('billings.create', [
            'projects' => $projects
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBillingRequest $request)
    {
        Billing::create($request->all());
        return redirect()->route('billings.index')
                ->withSuccess('New Billing is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Billing $billing)
    {
        return view('billings.show', [
            'billing' => $billing
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Billing $billing)
    {
        $Projects = Project::get();
        $billing->load('project');
        return view('billings.edit', [
            'billing' => $billing,
            'Projects' => $Projects
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBillingRequest $request, Billing $billing)
    {
        $billing->update($request->all());
        return redirect()->back()
                ->withSuccess('Billing is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Billing $billing)
    {
        $billing->delete();
        return redirect()->route('billings.index')
                ->withSuccess('Billing is deleted successfully.');
    }
}
