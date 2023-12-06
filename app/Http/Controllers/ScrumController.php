<?php

namespace App\Http\Controllers;

use App\Models\Scrum;
use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use App\Http\Requests\StoreScrumRequest;
use App\Http\Requests\UpdateScrumRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class ScrumController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:show-scrum|create-scrum|edit-scrum|delete-scrum', ['only' => ['index','show']]);
       $this->middleware('permission:create-scrum', ['only' => ['create','store']]);
       $this->middleware('permission:edit-scrum', ['only' => ['edit','update']]);
       $this->middleware('permission:delete-scrum', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if a date is provided and is valid
        if (isset($_GET['date']) && $_GET['date'] !== null) {
            try {
                $selectedDate = Carbon::createFromFormat('Y-m-d', $_GET['date']);
            } catch (\Exception $e) {
                // Use today's date if the provided date is invalid
                $selectedDate = Carbon::today();
            }
        } else {
            // Use today's date if no date is provided
            $selectedDate = Carbon::today();
        }

        // Fetch scrums for the selected date
        $scrums = Scrum::whereDate('date', $selectedDate)
            ->latest()
            ->paginate(50);

        return view('scrums.index', [ 
            'scrums' => $scrums,
            'matchDate' => $selectedDate
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $selectedDate = Carbon::today();
        $clients = Client::get();
        return view('scrums.create', [
            'clients' => $clients,
            'matchDate' => $selectedDate
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScrumRequest $request)
    {
        Scrum::create($request->all());
        return redirect()->route('scrums.index')
                ->withSuccess('New Scrum is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Scrum $scrum)
    {
        $scrums_list = Scrum::where('client_id', $scrum->client_id)
            ->where('project_id', $scrum->project_id)
            ->get();

        // Calculate the sum of working_hours
        $totalWorkingHours = $scrums_list->sum('working_hours');

        return view('scrums.show', [
            'scrum' => $scrum,
            'scrums_list' => $scrums_list,
            'totalWorkingHours' => $totalWorkingHours,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Scrum $scrum)
    {
        $clients = Client::get();
        return view('scrums.edit', [
            'scrum' => $scrum,
            'clients' => $clients
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScrumRequest $request, Scrum $scrum)
    {
        $scrum->update($request->all());
        return redirect()->back()
                ->withSuccess('Scrum is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scrum $scrum)
    {
        $scrum->delete();
        return redirect()->route('scrums.index')
                ->withSuccess('Scrum is deleted successfully.');
    }

    /**
     * Get client related projects from database.
     */
    public function getProjectsByClientId($clientId){
        $projects = Project::where('client_id', $clientId)->get();
        return response()->json($projects);
    }
}
