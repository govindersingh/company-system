@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Billing List</div>
    <div class="card-body">
        @can('create-billing')
            <a href="{{ route('billings.create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New Billing</a>
        @endcan
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                <th scope="col">S#</th>
                <th scope="col">Project</th>
                <th scope="col">Amount</th>
                <th scope="col">Date</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($billings as $billing)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $billing->project->name }}</td>
                    <td>{{ $billing->amount }}</td>
                    <td>{{ $billing->date }}</td>
                    <td>{{ $billing->status }}</td>
                    <td>
                        <form action="{{ route('billings.destroy', $billing->id) }}" method="post">
                            @csrf
                            @method('DELETE')

                            <a href="{{ route('billings.show', $billing->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> Show</a>

                            @can('edit-billing')
                                <a href="{{ route('billings.edit', $billing->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                            @endcan

                            @can('delete-billing')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete this billing?');"><i class="bi bi-trash"></i> Delete</button>
                            @endcan
                        </form>
                    </td>
                </tr>
                @empty
                    <td colspan="6">
                        <span class="text-danger">
                            <strong>No billing Found!</strong>
                        </span>
                    </td>
                @endforelse
            </tbody>
        </table>

        {{ $billings->links() }}

    </div>
</div>
@endsection