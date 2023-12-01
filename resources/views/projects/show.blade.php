@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Project Information
                </div>
                <div class="float-end">
                    <a href="{{ route('projects.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <label for="client" class="col-md-4 col-form-label text-md-end text-start"><strong>Client:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $project->client->name }}
                    </div>
                </div>

                <div class="row">
                    <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Project Name:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $project->name }}
                    </div>
                </div>

                <div class="row">
                    <label for="description" class="col-md-4 col-form-label text-md-end text-start"><strong>Description:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#descriptionPopUp"><i class="bi bi-door-open"></i> Open</button>
                        
                        <div class="modal fade modal-lg" id="descriptionPopUp" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel"><strong>Project Name: {{ $project->name }}</strong></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <textarea id="description">{{ $project->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <label for="start_date" class="col-md-4 col-form-label text-md-end text-start"><strong>Start Date:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $project->start_date }}
                    </div>
                </div>

                <div class="row">
                    <label for="end_date" class="col-md-4 col-form-label text-md-end text-start"><strong>End Date:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $project->end_date }}
                    </div>
                </div>

                <div class="row">
                    <label for="budget" class="col-md-4 col-form-label text-md-end text-start"><strong>Budget:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $project->budget }}
                    </div>
                </div>

                <div class="row">
                    <label for="status" class="col-md-4 col-form-label text-md-end text-start"><strong>Status:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $project->status }}
                    </div>
                </div>
        
            </div>
        </div>
    </div>    

    @canany(['create-billing', 'edit-billing', 'delete-billing'])
    <div class="col-md-12 mt-5">
        <div class="card">
            <div class="card-header d-flex justify-content-between">Billing List <strong text-right>Totel Earn: {{$amountSum}}</strong></div>
            <div class="card-body">
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
            </div>
        </div>
    </div>

    <div class="col-md-12 mt-5">
        <div class="card">
            <div class="card-header text-center text-success"><h2><strong>Totel Earn: {{$amountSum}}</strong></h2></div>
        </div>
    </div>
    @endcanany
</div>
<script>
    ClassicEditor
        .create( document.querySelector( '#description' ) )
        .catch( error => {
            console.error( error );
        } );
</script>     
@endsection