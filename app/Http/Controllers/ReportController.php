<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use App\Models\Report;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Instantiate a new clientController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:show-report|create-report|edit-report|delete-report', ['only' => ['index','show']]);
        $this->middleware('permission:create-report', ['only' => ['create','store']]);
        $this->middleware('permission:edit-report', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-report', ['only' => ['destroy']]);
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

        // Fetch reports for the selected date
        $reports = Report::whereDate('date', $selectedDate)
            ->latest()
            ->paginate(50);
            

        // Calculate the sum of working_hours
        $WorkingHours = $reports->sum('working_hours');

        // Calculate the sum of working_hours
        $Total = $reports->sum('total');

        return view('reports.index', [ 
            'reports' => $reports,
            'matchDate' => $selectedDate,
            'WorkingHours' => $WorkingHours,
            'Total' => $Total,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::get();
        $users = User::role(['Client Manager', 'Developer'])->get();
        $selectedDate = Carbon::today();

        return view('reports.create',[
            'clients' => $clients,
            'users' => $users,
            'matchDate' => $selectedDate
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {
        Report::create($request->all());
        return redirect()->route('reports.index')
                ->withSuccess('New Report is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report)
    {
        // Load the related projects along with the client
        $client->load('projects');

        return view('reports.show', [
            'report' => $report,
            'projects' => $client->projects
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Report $report)
    {
        $clients = Client::get();
        $users = User::role(['Client Manager', 'Developer'])->get();
        $selectedDate = Carbon::today();

        return view('reports.edit', [
            'report' => $report,
            'clients' => $clients,
            'users' => $users,
            'matchDate' => $selectedDate
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, Report $report)
    {
        $report->update($request->all());
        return redirect()->back()
                ->withSuccess('Report is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        $report->delete();
        return redirect()->route('reports.index')
                ->withSuccess('Report is deleted successfully.');
    }
}
