<?php

namespace App\Http\Controllers;

use App\Models\Scrum;
use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use App\Models\Billing;
use App\Models\Report;
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
        $scrum = Scrum::create($request->all());

        $client_id = $request->input('client_id');
        $project_id = $request->input('project_id');
        $user_id = $request->input('user_id');

        $project = Project::where('id', $project_id)->first();
        if($project->project_type == 'Hourly'){
            // create report from scrum project
            $report = array(
                'client_id' => $client_id,
                'project_id' => $project_id,
                'user_id' => $user_id,
                'working_hours' => $request->input('working_hours'),
                'total' => number_format((float)$request->input('working_hours'), 2, '.', '') * number_format((float)$project->hourly_rate, 2, '.', ''),
                'date' =>$request->input('date'),
            );
            $report_return = Report::create($report);

            // create billing from scrum project.
            $billing = array(
                'project_id' => $project_id,
                'scrum_id' => $scrum->id,
                'report_id' => $report_return->id,
                'amount' => number_format((float)$request->input('working_hours'), 2, '.', '') * number_format((float)$project->hourly_rate, 2, '.', ''),
                'date' => $request->input('date'),
                'status' => 'Paid',
            );
            Billing::create($billing);

            // Update billing in product budget
            $project->load('billings');
            $project_update = $project->toArray();
            $project_update['budget'] = $project->amount_sum;
            $project->update($project_update);
        }else{
            // create report from scrum project
            $report = array(
                'client_id' => $client_id,
                'project_id' => $project_id,
                'user_id' => $user_id,
                'working_hours' => $request->input('working_hours'),
                'total' => 0,
                'date' =>$request->input('date'),
            );
            $report_return = Report::create($report);
        }
        
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
        $client_id = $request->input('client_id');
        $project_id = $request->input('project_id');
        $user_id = $request->input('user_id');

        $project = Project::where('id', $project_id)->first();
        if($project->project_type == 'Hourly'){
            // Update report from scrum project
            $billing_update = Billing::where('scrum_id', $scrum->id)->first();
            $report = array(
                'client_id' => $client_id,
                'project_id' => $project_id,
                'user_id' => $user_id,
                'working_hours' => $request->input('working_hours'),
                'total' => number_format((float)$request->input('working_hours'), 2, '.', '') * number_format((float)$project->hourly_rate, 2, '.', ''),
                'date' =>$request->input('date'),
            );
            Report::where('id', $billing_update->report_id)->update($report);

            // update billing from scrum project.
            $billing = array(
                'project_id' => $project_id,
                'scrum_id' => $scrum->id,
                'amount' => number_format((float)$request->input('working_hours'), 2, '.', '') * number_format((float)$project->hourly_rate, 2, '.', ''),
                'date' => $request->input('date'),
                'status' => 'Paid',
            );
            Billing::where('scrum_id', $scrum->id)->update($billing);

            // Update billing in product budget
            $project->load('billings');
            $project_update = $project->toArray();
            $project_update['budget'] = $project->amount_sum;
            $project->update($project_update);
        }


        $scrum->update($request->all());
        return redirect()->back()
                ->withSuccess('Scrum is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Scrum $scrum)
    {
        $billing_update = Billing::where('scrum_id', $scrum->id)->first();
        if($billing_update){
            Billing::where('scrum_id', $scrum->id)->delete();
            Report::where('id', $billing_update->report_id)->delete();
        }

        $project = Project::where('id', $scrum->project_id)->first();
        $project->load('billings');
        $project_update = $project->toArray();
        $project_update['budget'] = $project->amount_sum;
        $project->update($project_update);

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
