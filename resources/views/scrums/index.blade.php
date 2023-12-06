@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">Scrum List <strong class="d-flex text-nowrap align-items-center gap-2" text-right>Fetch Result For: <input type="date" id="fetchData" value="{{ $matchDate ? $matchDate->format('Y-m-d') : '' }}" class="form-control"></strong></div>
    <div class="card-body">
        @can('create-scrum')
            <a href="{{ route('scrums.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New Scrum</a>
        @endcan
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                <th scope="col">S#</th>
                <th scope="col">Client Name</th>
                <th scope="col">Project Name</th>
                <th scope="col">Developer Name</th>
                <th scope="col">Working Hours</th>
                <th scope="col">Date</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($scrums as $scrum)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $scrum->client->name }}</td>
                    <td>{{ $scrum->project->name }}</td>
                    <td>{{ $scrum->user->name }}</td>
                    <td>{{ $scrum->working_hours }}</td>
                    <td>{{ $scrum->date }}</td>
                    <td>
                        <form action="{{ route('scrums.destroy', $scrum->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <a href="{{ route('scrums.show', $scrum->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> Show</a>

                            @can('edit-scrum')
                            @if(Auth::user()->id == $scrum->user_id)
                                <a href="{{ route('scrums.edit', $scrum->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                            @endif
                            @endcan

                            @can('delete-scrum')
                            @if(Auth::user()->id == $scrum->user_id)
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this scrum?');"><i class="bi bi-trash"></i> Delete</button>
                            @endif
                            @endcan
                        </form>
                    </td>
                </tr>
                @empty
                    <td colspan="7">
                        <span class="text-danger">
                            <strong>No Scrum Found!</strong>
                        </span>
                    </td>
                @endforelse
            </tbody>
        </table>

        {{ $scrums->links() }}

    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('fetchData').addEventListener('change', function() {
            var date = this.value;
            window.location.href = '/scrums?date=' + date;
        });
    });
</script>
@endsection