@extends('layouts.app')

@section('content')
@php($reportsData = null)
<div class="card">
    <div class="card-header d-flex justify-content-between">Report List <strong class="d-flex text-nowrap align-items-center gap-2" text-right>Fetch Result For: <input type="date" id="fetchData" value="{{ $matchDate ? $matchDate->format('Y-m-d') : '' }}" class="form-control"></strong></div>
    <div class="card-body">
        @can('create-report')
            <a href="{{ route('reports.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New Report</a>
            <button type="button" onClick="previewBilling();" class="btn btn-success btn-sm my-2" data-bs-toggle="modal" data-bs-target="#billingPreviewPopUp"><i class="bi bi-eye"></i> Preview billing</button>
        @endcan
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                <th scope="col">S#</th>
                <th scope="col">Client Name</th>
                <th scope="col">Project Name</th>
                <th scope="col">Developer Name</th>
                <th scope="col">Project Type</th>
                <th scope="col">Working Hours</th>
                <th scope="col">Total</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reports as $key => $report)

                @php($reportsData[$key]['client_name'] = $report->client->name)
                @php($reportsData[$key]['client_rate'] = $report->project->hourly_rate)
                @php($reportsData[$key]['bill_type'] = $report->project->project_type)
                @php($reportsData[$key]['developer_name'] = $report->user->name)
                @php($reportsData[$key]['working_hours'] = $report->working_hours)
                @php($reportsData[$key]['profile'] = $report->project->project_profile ?? 'No profile')
                
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $report->client->name }}</td>
                    <td>{{ $report->project->name }}</td>
                    <td>{{ $report->user->name }}</td>
                    <td>{{ $report->project->project_type }}</td>
                    <td>{{ $report->working_hours }}</td>
                    <td>${{ $report->total }}</td>
                    
                    <td>
                        <form action="{{ route('reports.destroy', $report->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <!-- <a href="{{ route('reports.show', $report->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> Show</a> -->

                            @can('edit-report')
                                <a href="{{ route('reports.edit', $report->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                            @endcan

                            @can('delete-report')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this report?');"><i class="bi bi-trash"></i> Delete</button>
                            @endcan
                        </form>
                    </td>
                </tr>
                @empty
                    <td colspan="8">
                        <span class="text-danger">
                            <strong>No Report Found!</strong>
                        </span>
                    </td>
                @endforelse
            </tbody>
        </table>

        {{ $reports->links() }}

    </div>

    <div class="col-md-12 mt-5">
        <div class="card">
            <div class="card-header text-center"><h3>( <strong>Hours: {{$WorkingHours}} </strong> ) ( <strong class="text-success">Pay in: ${{$Total}}</strong> )</h3></div>
        </div>
    </div>
</div>
                        
<div class="modal fade modal-lg" id="billingPreviewPopUp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"><strong>Report</strong></h5>
                <a href="javascript:void(0);" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <div class="modal-body">
                <textarea id="description" style="width:100%;height:700px;"></textarea>
            </div>
        </div>
    </div>
</div>
@php($reportsJson = json_encode($reportsData))
@php($totalJson = json_encode($Total))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('fetchData').addEventListener('change', function() {
            var date = this.value;
            window.location.href = '/reports?date=' + date;
        });
    });

    // Helper functions to convert hours format
    function convertHoursToDecimal(hoursString) {
        const [hours, minutes] = hoursString.split(":").map(Number);
        return hours + minutes / 60;
    }

    function convertDecimalToHours(decimalHours) {
        const hours = Math.floor(decimalHours);
        const minutes = Math.round((decimalHours - hours) * 60);
        return `${hours}:${minutes.toString().padStart(2, '0')}`;
    }

    // Function to generate billing report text
    function generateBillingReport(reports, total) {
        let text = "TODAY's Billing:\n";
        reports.forEach(entry => {
            text += `- ${entry.client_name.padEnd(30)} : ${convertDecimalToHours(entry.working_hours)} [${entry.bill_type}]-[${entry.profile}]-${entry.developer_name}\n`;
        });

        let totalBillingHours = 0;
        reports.forEach(entry => {
            totalBillingHours += entry.working_hours;
        });
        const totalBillingFormatted = convertDecimalToHours(totalBillingHours);
        text += "======================================================================\n";
        text += `TOTAL BILLING                    : ${totalBillingFormatted}\n`;
        text += "======================================================================\n";

        // Group by developer name and calculate billing
        const devBilling = {};
        reports.forEach(entry => {
            if (!devBilling[entry.developer_name]) {
                devBilling[entry.developer_name] = [];
            }
            devBilling[entry.developer_name].push({
                client_name: entry.client_name,
                client_rate: entry.client_rate,
                working_hours: entry.working_hours
            });
        });

        Object.keys(devBilling).forEach(devName => {
            const devEntries = devBilling[devName];
            let devText = `- ${devName.padEnd(30)} : `;
            let totalAmount = 0;
            devEntries.forEach((entry, index) => {
                if (index > 0) devText += " + ";
                devText += `${entry.working_hours}*${entry.client_rate} (${entry.client_name})`;
                totalAmount += entry.working_hours * entry.client_rate;
            });
            devText += ` = ${totalAmount}\n`;
            text += devText;
        });
        text += "-----------------------------------------------------------------------\n";
        text += `TOTAL REVENUE                    : ${total} USD\n`;

        return text;
    }
    
    function previewBilling(){
        let previewDate = document.getElementById('fetchData').value;
        var reports = {!! $reportsJson !!};
        var total = {!! $totalJson !!};
        if(reports){
            const billingReportText = generateBillingReport(reports, total);
            document.getElementById('description').textContent = billingReportText;
            console.log(billingReportText);
        }
    }
    previewBilling();
</script>
@endsection