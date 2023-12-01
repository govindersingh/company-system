@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Scrum Information
                </div>
                <div class="float-end">
                    <a href="{{ route('scrums.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <label for="client_id" class="col-md-4 col-form-label text-md-end text-start"><strong>Client Name:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $scrum->client->name }}
                    </div>
                </div>

                <div class="row">
                    <label for="project_id" class="col-md-4 col-form-label text-md-end text-start"><strong>Project Name:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $scrum->project->name }}
                    </div>
                </div>

                <div class="row">
                    <label for="user_id" class="col-md-4 col-form-label text-md-end text-start"><strong>Developer Name:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $scrum->user->name }}
                    </div>
                </div>

                <div class="row">
                    <label for="working_hours" class="col-md-4 col-form-label text-md-end text-start"><strong>Working Hours:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $scrum->working_hours }}
                    </div>
                </div>

                <div class="row">
                    <label for="date" class="col-md-4 col-form-label text-md-end text-start"><strong>Date:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $scrum->date }}
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
                                    <h5 class="modal-title" id="staticBackdropLabel"><strong>Client Name: {{ $scrum->client->name }}</strong></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <textarea id="description">{{ $scrum->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @canany(['show-scrum'])
                <div class="row">
                    <label for="description" class="col-md-4 col-form-label text-md-end text-start"><strong>View Project Details:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        <a class="btn btn-warning btn-sm" href="{{ route('projects.show', $scrum->project->id) }}"><i class="bi bi-eye"></i> Show</a>
                    </div>
                </div>
                @endcanany
            </div>
        </div>
    </div>    

    @canany(['show-scrum', 'create-scrum', 'edit-scrum', 'delete-scrum'])
    <div class="col-md-12 mt-5">
        <div class="card">
        <div class="card-header d-flex justify-content-between">Scrum List <strong text-right>Total Working Hours: {{$totalWorkingHours }}</strong></div>
            <div class="card-body">
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
                        @forelse ($scrums_list as $list)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $list->client->name }}</td>
                            <td>{{ $list->project->name }}</td>
                            <td>{{ $list->user->name }}</td>
                            <td>{{ $list->working_hours }}</td>
                            <td>{{ $list->date }}</td>
                            <td>
                                <form action="{{ route('scrums.destroy', $list->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <a href="{{ route('scrums.show', $list->id) }}" class="btn btn-warning btn-sm"><i class="bi bi-eye"></i> Show</a>

                                    @can('edit-scrum')
                                        <a href="{{ route('scrums.edit', $list->id) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                                    @endcan

                                    @can('delete-scrum')
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