@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Add New Scrum
                </div>
                <div class="float-end">
                    <a href="{{ route('scrums.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('scrums.store') }}" method="post">
                    @csrf

                    <div class="mb-3 row">
                        <label for="client_id" class="col-md-4 col-form-label text-md-end text-start">Client Name</label>
                        <div class="col-md-6">
                            <select class="form-control @error('client_id') is-invalid @enderror" id="client_id" name="client_id" value="{{ old('client_id') }}">
                                @foreach ($clients as $client)
                                <option value="{{$client->id}}">{{ $client->name }}</option>
                                @endforeach 
                            </select>
                          
                            @if ($errors->has('client_id'))
                                <span class="text-danger">{{ $errors->first('client_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="project_id" class="col-md-4 col-form-label text-md-end text-start">Project Name</label>
                        <div class="col-md-6">
                            <select class="form-control @error('project_id') is-invalid @enderror" id="project_id" name="project_id" value="{{ old('project_id') }}"></select>
                          
                            @if ($errors->has('project_id'))
                                <span class="text-danger">{{ $errors->first('project_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="user_id" class="col-md-4 col-form-label text-md-end text-start">Developer Name</label>
                        <div class="col-md-6 developerNameField">
                            <span class="inputValueName">{{ Auth::user()->name }}</span>
                            <input type="hidden" class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id"  value="{{ Auth::user()->id }}">
                            @if ($errors->has('user_id'))
                                <span class="text-danger">{{ $errors->first('user_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="description" class="col-md-4 col-form-label text-md-end text-start">Description</label>
                        <div class="col-md-6">
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                            @if ($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="working_hours" class="col-md-4 col-form-label text-md-end text-start">Working Hours</label>
                        <div class="col-md-6">
                          <input type="number" class="form-control @error('working_hours') is-invalid @enderror" id="working_hours" name="working_hours" value="{{ old('working_hours') }}">
                            @if ($errors->has('working_hours'))
                                <span class="text-danger">{{ $errors->first('working_hours') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="date" class="col-md-4 col-form-label text-md-end text-start">Date</label>
                        <div class="col-md-6">
                          <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date') }}">
                            @if ($errors->has('date'))
                                <span class="text-danger">{{ $errors->first('date') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Add Scrum">
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('client_id').addEventListener('change', function() {
            var clientId = this.value;
            getProjects(clientId);
        });
    });

    function getProjects(clientId){
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/scrums/getprojectsbyclientid/' + clientId, true);

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 400) {
                document.getElementById('project_id').innerHTML = '';
                var data = JSON.parse(xhr.responseText);
                data.forEach(function(project) {
                    var option = document.createElement('option');
                    option.value = project.id;
                    option.text = project.name;
                    document.getElementById('project_id').add(option);
                });
            } else {
                console.error('Error: ' + xhr.statusText);
            }
        };

        xhr.onerror = function() {
            console.error('Request failed');
        };
        xhr.send();
    }

    var client_id = document.getElementById('client_id').value;
    getProjects(client_id);
</script>
@endsection