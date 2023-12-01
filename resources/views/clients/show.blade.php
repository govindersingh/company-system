@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Client Information
                </div>
                <div class="float-end">
                    <a href="{{ route('clients.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Name:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $client->name }}
                    </div>
                </div>

                <div class="row">
                    <label for="email" class="col-md-4 col-form-label text-md-end text-start"><strong>Email:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $client->email }}
                    </div>
                </div>

                <div class="row">
                    <label for="phone" class="col-md-4 col-form-label text-md-end text-start"><strong>Phone:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $client->phone }}
                    </div>
                </div>

                <div class="row">
                    <label for="platform" class="col-md-4 col-form-label text-md-end text-start"><strong>Platform:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $client->platform }}
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
                                    <h5 class="modal-title" id="staticBackdropLabel"><strong>Client Name: {{ $client->name }}</strong></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <textarea id="description">{{ $client->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    

    @canany(['create-project', 'edit-project', 'delete-project'])
    <div class="col-md-12 mt-5">
        <div class="card">
        <div class="card-header">Project List</div>
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                        <th scope="col">S#</th>
                        <th scope="col">Project Name</th>
                        <!-- <th scope="col">Client</th> -->
                        <!-- <th scope="col">Description</th> -->
                        <th scope="col">Start Date</th>
                        <th scope="col">End Date</th>
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
                            <!-- <td>{{ $project->client->name }}</td> -->
                            <!-- <td>{{ $project->description }}</td> -->
                            <td>{{ $project->start_date }}</td>
                            <td>{{ $project->end_date }}</td>
                            <td>{{ $project->budget }}</td>
                            <td>{{ $project->status }}</td>
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
                            <td colspan="6">
                                <span class="text-danger">
                                    <strong>No project Found!</strong>
                                </span>
                            </td>
                        @endforelse
                    </tbody>
                </table>
            </div>
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