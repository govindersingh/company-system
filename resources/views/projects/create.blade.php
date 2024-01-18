@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Add New Project
                </div>
                <div class="float-end">
                    <a href="{{ route('projects.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('projects.store') }}" method="post">
                    @csrf

                    <div class="mb-3 row">
                        <label for="client_id" class="col-md-4 col-form-label text-md-end text-start">Client</label>
                        <div class="col-md-6">
                            <select class="js-example-basic-single form-control @error('client_id') is-invalid @enderror" id="client_id" name="client_id" value="{{ old('client_id') }}">
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
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">Project Name</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
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
                        <label for="start_date" class="col-md-4 col-form-label text-md-end text-start">Start Date</label>
                        <div class="col-md-6">
                          <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ $matchDate ? $matchDate->format('Y-m-d') : '' }}">
                            @if ($errors->has('start_date'))
                                <span class="text-danger">{{ $errors->first('start_date') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="end_date" class="col-md-4 col-form-label text-md-end text-start">End Date</label>
                        <div class="col-md-6">
                          <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date">
                            @if ($errors->has('end_date'))
                                <span class="text-danger">{{ $errors->first('end_date') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="project_type" class="col-md-4 col-form-label text-md-end text-start">Project Type</label>
                        <div class="col-md-6">
                            <select class="form-control @error('project_type') is-invalid @enderror" id="project_type" name="project_type" value="{{ old('project_type') }}">
                                @foreach (Config::get('app.enum_project_type') as $project_type)
                                <option value="{{$project_type}}">{{ $project_type }}</option>
                                @endforeach 
                            </select>
                            @if ($errors->has('project_type'))
                                <span class="text-danger">{{ $errors->first('project_type') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row" id="milestone_element">
                        <label for="milestones_rate" class="col-md-4 col-form-label text-md-end text-start">Milestones Rate</label>
                        <div class="col-md-6">
                            <div id="milestones">
                                <div class="input-group" data-index="1">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Milestone 1</div>
                                    </div>
                                    <input type="number" min="0" class="form-control milestones_data @error('milestones_data') is-invalid @enderror" name="milestones_data[1]" placeholder="Price in number">
                                </div>
                            </div>
                            <a href="javascript:void(0);" onClick="addMilestone();" class="btn btn-primary btn-sm mt-3 mx-auto">Add Milestone +</a>
                            <input type="hidden" class="form-control @error('milestones_rate') is-invalid @enderror" name="milestones_rate" id="milestonesDataInput">
                            
                            @if ($errors->has('milestones_rate'))
                                <span class="text-danger">{{ $errors->first('milestones_rate') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row" id="hourly_element" style="display:none;">
                        <label for="hourly_rate" class="col-md-4 col-form-label text-md-end text-start">Hourly Rate</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">$</div>
                                </div>
                                <input type="number" min="0" class="form-control @error('hourly_rate') is-invalid @enderror" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate') }}">
                            </div>
                            
                            @if ($errors->has('hourly_rate'))
                                <span class="text-danger">{{ $errors->first('hourly_rate') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="status" class="col-md-4 col-form-label text-md-end text-start">Status</label>
                        <div class="col-md-6">
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" value="{{ old('status') }}">
                                @foreach (Config::get('app.enum_project_status') as $status)
                                <option value="{{$status}}">{{ $status }}</option>
                                @endforeach 
                            </select>
                            @if ($errors->has('status'))
                                <span class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <input type="hidden" min="0" class="form-control" id="hourly_rate" name="budget" value="0">
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Add Project">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    
</div>
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
</script>
<script>
    ClassicEditor
        .create( document.querySelector( '#description' ) )
        .catch( error => {
            console.error( error );
        } );

    document.getElementById("project_type").addEventListener("change",function(event){
        if(event.target.value == "Fixed"){
            document.getElementById("milestone_element").style.display = "flex";
            document.getElementById("hourly_element").style.display = "none";
        }else if(event.target.value == "Hourly"){
            document.getElementById("milestone_element").style.display = "none";
            document.getElementById("hourly_element").style.display = "flex";
        }
    })

    function addMilestone(){
        let milestones_elements = document.querySelectorAll("#milestones [data-index]");

        let newElement = document.createElement('div');
        newElement.className = "input-group my-1";
        newElement.setAttribute('data-index', milestones_elements.length + 1);
        
        newElement.innerHTML = `
        <div class="input-group-prepend">
            <div class="input-group-text">Milestone ${milestones_elements.length + 1}</div>
        </div>
        <input type="number" min="0" class="form-control milestones_data @error('milestones_data') is-invalid @enderror" name="milestones_data[${milestones_elements.length + 1}]" placeholder="Price in number">
        <a href="javascript:void(0);" class="btn btn-danger" onClick="removeMilestone(${milestones_elements.length + 1});">Remove</a>
        `;

        document.getElementById("milestones").appendChild(newElement);
    }

    function removeMilestone(index){
        let remove_elements = document.querySelector(`#milestones [data-index="${index}"]`);
        remove_elements.remove();
    }

    document.addEventListener("keyup", function(event){
        let milestonesData = [];
        document.querySelectorAll('.milestones_data').forEach(function(input) {
            var price = input.value;
            var index = input.parentNode.getAttribute("data-index");
            milestonesData.push({milestone: index, price: price, status: 'unpaid'});
            document.getElementById('milestonesDataInput').value = JSON.stringify(milestonesData);
        });
    });
</script>
@endsection