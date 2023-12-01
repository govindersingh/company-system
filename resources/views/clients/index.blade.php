@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Client List</div>
    <div class="card-body">
        @can('create-client')
            <a href="{{ route('clients.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New Client</a>
        @endcan
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                <th scope="col">S#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Platform</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clients as $client)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->phone }}</td>
                    <td>{{ $client->platform }}</td>
                    <td>
                        <form action="{{ route('clients.destroy', $client->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <a href="{{ route('clients.show', $client->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> Show</a>

                            @can('edit-client')
                                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                            @endcan

                            @can('delete-client')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this client?');"><i class="bi bi-trash"></i> Delete</button>
                            @endcan
                        </form>
                    </td>
                </tr>
                @empty
                    <td colspan="6">
                        <span class="text-danger">
                            <strong>No Client Found!</strong>
                        </span>
                    </td>
                @endforelse
            </tbody>
        </table>

        {{ $clients->links() }}

    </div>
</div>
@endsection