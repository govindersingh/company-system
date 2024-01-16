<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use App\Models\Report;
use App\Models\Project;
use App\Models\Billing;
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
        $client_id = $request->input('client_id');
        $project_id = $request->input('project_id');
        $user_id = $request->input('user_id');

        // Create new report.
        $report = Report::create($request->all());

        // get Project from report project.
        $project = Project::where('id', $project_id)->first();

        if($project->project_type == 'Hourly'){
            $billing = array(
                'project_id' => $project_id,
                'report_id' => $report->id,
                'amount' => number_format((float)$request->input('working_hours'), 2, '.', '') * number_format((float)$project->hourly_rate, 2, '.', ''),
                'date' => $request->input('date'),
                'status' => 'Paid',
            );
        }else{
            $billing = array(
                'project_id' => $project_id,
                'report_id' => $report->id,
                'milestone' => $request->input('milestone_number'),
                'amount' => $request->input('total'),
                'date' => $request->input('date'),
                'status' => 'Paid',
            );
        }
        // create billing from report project.
        Billing::create($billing);

        // Update billing in product budget
        $project->load('billings');
        $project_update = $project->toArray();
        $project_update['budget'] = $project->amount_sum;
        $project_update['milestones_rate'] = $request->input('milestones_rate');
        $project->update($project_update);
        
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
        $client_id = $request->input('client_id');
        $project_id = $request->input('project_id');
        $user_id = $request->input('user_id');

        // get Project from report project.
        $project = Project::where('id', $project_id)->first();

        // get billing from report project.
        $billing_update = Billing::where('report_id', $report->id)->first();

        if($project->project_type == 'Hourly'){
            $billing = array(
                'project_id' => $project_id,
                'report_id' => $report->id,
                'amount' => number_format((float)$request->input('working_hours'), 2, '.', '') * number_format((float)$project->hourly_rate, 2, '.', ''),
                'date' => $request->input('date'),
                'status' => 'Paid',
            );
        }else{
            $billing = array(
                'project_id' => $project_id,
                'report_id' => $report->id,
                'amount' => $request->input('total'),
                'date' => $request->input('date'),
                'status' => 'Paid',
            );
        }

        // create/update billing from report project.
        if($billing_update){
            Billing::where('report_id', $report->id)->update($billing);
        }else{
            Billing::create($billing);
        }

        // Update billing in product budget
        $project->load('billings');
        $project_update = $project->toArray();
        $project_update['budget'] = $project->amount_sum;
        $project_update['milestones_rate'] = $request->input('milestones_rate');
        $project->update($project_update);
        
        $report->update($request->all());
        return redirect()->back()
                ->withSuccess('Report is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Report $report)
    {
        $project = Project::where('id', $report->project_id)->first();
        $milestone = [];
        $billing = Billing::where('report_id', $report->id)->first();
        if($project && $project->project_type == 'Fixed'){
            foreach (json_decode($project->milestones_rate, true) as $key => $value) {
                if($billing && $value['milestone'] == (int)$billing->milestone){
                    $milestoneData = array(
                        'milestone' => $value['milestone'],
                        'price' => $value['price'],
                        'status' => 'unpaid',
                    );
                    $milestone[] = $milestoneData;
                }else{
                    $milestone[] = $value;
                }
            }
        }
        if($billing){
            Billing::where('report_id', $report->id)->delete();
        }

        if($project){
            $project->load('billings');
            $project_update = $project->toArray();
            $project_update['budget'] = $project->amount_sum;
            $project_update['milestones_rate'] = json_encode($milestone);
            $project->update($project_update);
        }

        $report->delete();
        return redirect()->route('reports.index')
                ->withSuccess('Report is deleted successfully.');
    }
}
