@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Edit Project
                </div>
                <div class="float-end">
                    <a href="{{ route('projects.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('projects.update', $project->id) }}" method="post">
                    @csrf
                    @method("PUT")

                    <div class="mb-3 row">
                        <label for="client_id" class="col-md-4 col-form-label text-md-end text-start">Client</label>
                        <div class="col-md-6">
                            <select class="form-control @error('client_id') is-invalid @enderror" id="client_id" name="client_id" value="{{ old('client_id') }}">
                                @foreach ($clients as $client)
                                <option value="{{$client->id}}" @if($client->id == $project->client_id) {{ "selected" }} @endif>{{ $client->name }}</option>
                                @endforeach 
                            </select>
                          
                            @if ($errors->has('client_id'))
                                <span class="text-danger">{{ $errors->first('client_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">Project Name</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $project->name }}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="description" class="col-md-4 col-form-label text-md-end text-start">Description</label>
                        <div class="col-md-6">
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ $project->description }}</textarea>
                            @if ($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="start_date" class="col-md-4 col-form-label text-md-end text-start">Start Date</label>
                        <div class="col-md-6">
                          <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ $project->start_date }}">
                            @if ($errors->has('start_date'))
                                <span class="text-danger">{{ $errors->first('start_date') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="end_date" class="col-md-4 col-form-label text-md-end text-start">End Date</label>
                        <div class="col-md-6">
                          <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ $project->end_date }}">
                            @if ($errors->has('end_date'))
                                <span class="text-danger">{{ $errors->first('end_date') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="budget" class="col-md-4 col-form-label text-md-end text-start">Budget</label>
                        <div class="col-md-6">
                          <input type="number" min="0" class="form-control @error('budget') is-invalid @enderror" id="budget" name="budget" value="{{ $project->budget }}">
                            @if ($errors->has('budget'))
                                <span class="text-danger">{{ $errors->first('budget') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="status" class="col-md-4 col-form-label text-md-end text-start">Status</label>
                        <div class="col-md-6">
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" value="{{ old('status') }}">
                                @foreach (Config::get('app.enum_project_status') as $status)
                                <option value="{{$status}}" @if($status == $project->status) {{ "selected" }} @endif>{{ $status }}</option>
                                @endforeach 
                            </select>
                            @if ($errors->has('status'))
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Update">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    
</div>
<script>
    ClassicEditor
        .create( document.querySelector( '#description' ) )
        .catch( error => {
            console.error( error );
        } );
</script>    
@endsection