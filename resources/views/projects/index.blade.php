@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Project List</div>
    <div class="card-body">
        @can('create-project')
            <a href="{{ route('projects.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New Project</a>
        @endcan
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                <th scope="col">S#</th>
                <th scope="col">Project Name</th>
                <th scope="col">Client Name</th>
                <!-- <th scope="col">Description</th> -->
                <th scope="col">Start Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Project Type</th>
                <th scope="col">Budget</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($projects as $project)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->client->name }}</td>
                    <!-- <td>{{ $project->description }}</td> -->
                    <td>{{ $project->start_date }}</td>
                    <td>{{ $project->end_date }}</td>
                    <td>{{ $project->project_type }}</td>
                    <td>{{ $project->budget }}</td>
                    <td>
                        @if($project->status == "Open")<span class="badge rounded-pill bg-success">{{ $project->status }}</span>
                        @elseif($project->status == "Close")<span class="badge rounded-pill bg-secondary">{{ $project->status }}</span>
                        @elseif($project->status == "Cancel")<span class="badge rounded-pill bg-warning text-dark">{{ $project->status }}</span>@endif
                        
                    </td>
                    <td>
                        <form action="{{ route('projects.destroy', $project->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> Show</a>

                            @can('edit-project')
                                <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                            @endcan

                            @can('delete-project')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this project?');"><i class="bi bi-trash"></i> Delete</button>
                            @endcan
                        </form>
                    </td>
                </tr>
                @empty
                    <td colspan="8">
                        <span class="text-danger">
                            <strong>No project Found!</strong>
                        </span>
                    </td>
                @endforelse
            </tbody>
        </table>

        {{ $projects->links() }}

    </div>
</div>
@endsection