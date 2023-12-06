<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\Billing;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class ProjectController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:show-project|create-project|edit-project|delete-project', ['only' => ['index','show']]);
       $this->middleware('permission:create-project', ['only' => ['create','store']]);
       $this->middleware('permission:edit-project', ['only' => ['edit','update']]);
       $this->middleware('permission:delete-project', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('projects.index', [
            'projects' => Project::latest()->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $selectedDate = Carbon::today();
        $clients = Client::get();
        return view('projects.create', [
            'clients' => $clients,
            'matchDate' => $selectedDate,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        Project::create($request->all());
        return redirect()->route('projects.index')
                ->withSuccess('New Project is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        // Load the related billings along with the client
        $project->load('billings');

        return view('projects.show', [
            'project' => $project,
            'billings' => $project->billings,
            'amountSum' => $project->amount_sum // Access the sum of the amount
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $clients = Client::get();
        $project->load('client');
        return view('projects.edit', [
            'project' => $project,
            'clients' => $clients
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->all());
        return redirect()->back()
                ->withSuccess('Project is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')
                ->withSuccess('Project is deleted successfully.');
    }
}
