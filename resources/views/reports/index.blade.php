@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">Report List <strong class="d-flex text-nowrap align-items-center gap-2" text-right>Fetch Result For: <input type="date" id="fetchData" value="{{ $matchDate ? $matchDate->format('Y-m-d') : '' }}" class="form-control"></strong></div>
    <div class="card-body">
        @can('create-report')
            <a href="{{ route('reports.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New Report</a>
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
                @forelse ($reports as $report)
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('fetchData').addEventListener('change', function() {
            var date = this.value;
            window.location.href = '/reports?date=' + date;
        });
    });
</script>
@endsection