@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Edit Report
                </div>
                <div class="float-end">
                    <a href="{{ route('reports.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.update', $report->id) }}" method="post">
                    @csrf
                    @method("PUT")

                    <div class="mb-3 row">
                        <label for="client_id" class="col-md-4 col-form-label text-md-end text-start">Client Name</label>
                        <div class="col-md-6">
                            <select class="form-control @error('client_id') is-invalid @enderror" id="client_id" name="client_id">
                                @foreach ($clients as $client)
                                <option value="{{$client->id}}" @if($client->id ==  $report->client_id) {{'selected'}} @endif>{{ $client->name }}</option>
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
                            <select class="form-control @error('project_id') is-invalid @enderror" id="project_id" name="project_id"></select>
                            @if ($errors->has('project_id'))
                                <span class="text-danger">{{ $errors->first('project_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="user_id" class="col-md-4 col-form-label text-md-end text-start">Developer Name</label>
                        <div class="col-md-6">
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" value="{{ old('user_id') }}">
                                @foreach ($users as $user)
                                <option value="{{$user->id}}" @if($user->id ==  $report->user_id) {{'selected'}} @endif>{{ $user->name }}</option>
                                @endforeach 
                            </select>
                            @if ($errors->has('user_id'))
                                <span class="text-danger">{{ $errors->first('user_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="working_hours" class="col-md-4 col-form-label text-md-end text-start">Working Hours</label>
                        <div class="col-md-6">
                            <input type="number" class="form-control @error('working_hours') is-invalid @enderror" id="working_hours" name="working_hours" value="{{ $report->working_hours }}">
                            @if ($errors->has('working_hours'))
                                <span class="text-danger">{{ $errors->first('working_hours') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="project_type" class="col-md-4 col-form-label text-md-end text-start">Project Type</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="project_type" value="{{ $report->project->project_type }}" disabled>
                        </div>
                    </div>

                    @if($report->project->project_type == "Fixed")
                    <div class="mb-3 row" id="custom_element">
                        <label for="milestones_rate" class="col-md-4 col-form-label text-md-end text-start">Milestones Rate</label>
                        <div class="col-md-6">
                            <div id="milestones">
                                @php($is_step = false)
                                @foreach (json_decode($report->project->milestones_rate) as $milestones_rate)
                                <div class="input-group @if($milestones_rate->milestone != '1') my-1 @endif">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text input_{{ $milestones_rate->milestone }} @if($milestones_rate->status == 'paid') bg-success text-light @elseif($milestones_rate->status == 'unpaid') bg-warning text-dark @endif">Milestone {{ $milestones_rate->milestone }}</div>
                                    </div>
                                    <input type="number" min="0" class="form-control milestones_data @error('milestones_data') is-invalid @enderror" name="milestones_data[{{ $milestones_rate->milestone }}]" placeholder="Price in number" value="{{ $milestones_rate->price }}" data-index="{{ $milestones_rate->milestone }}" data-status="{{ $milestones_rate->status }}" disabled>
                                    @if($milestones_rate->status == "unpaid" && $report->total == 0 && $is_step == false)
                                    <a href="javascript:void(0);" class="btn btn-secondary" onClick="paidMilestone('{{ $milestones_rate->milestone }}', '{{ $milestones_rate->price }}', '{{ $milestones_rate->status }}');">Paid</a>
                                    @endif
                                </div>
                                @if($milestones_rate->status == "unpaid" && $is_step == false)
                                @php($is_step = true)
                                @endif
                                @endforeach
                            </div>
                            <input type="hidden" class="form-control @error('milestones_rate') is-invalid @enderror" name="milestones_rate" id="milestonesDataInput" value="{{ $report->project->milestones_rate }}">
                        </div>
                    </div>
                    @else
                    <div class="mb-3 row" id="custom_element">
                        <label for="hourly_rate" class="col-md-4 col-form-label text-md-end text-start">Hourly Rate</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" id="hourly_rate" value="{{ $report->project->hourly_rate }}" disabled>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="mb-3 row">
                        <label for="total" class="col-md-4 col-form-label text-md-end text-start">Total</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">$</span>
                                <input type="number" class="form-control @error('total') is-invalid @enderror" id="total" value="{{ $report->total }}" disabled>
                                <input type="hidden" class="form-control " id="total_real" name="total" value="{{ $report->total }}" style="display:none;">
                                <input type="number" min="0" class="form-control " id="milestone_number" name="milestone_number" style="display:none;">
                            </div>
                                @if ($errors->has('total'))
                                <span class="text-danger">{{ $errors->first('total') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="date" class="col-md-4 col-form-label text-md-end text-start">Date</label>
                        <div class="col-md-6">
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ $report->date }}">
                            @if ($errors->has('date'))
                                <span class="text-danger">{{ $errors->first('date') }}</span>
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
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('client_id').addEventListener('change', function() {
            var clientId = this.value;
            getProjects(clientId);
        });

        document.getElementById('working_hours').addEventListener('change', function() {
            var working_hours = this.value;
            var element = document.getElementById('project_id');
            var project_type = element.options[element.selectedIndex].getAttribute('data-project_type');

            if(project_type == 'Hourly'){
                var hourly_rate = element.options[element.selectedIndex].getAttribute('data-hourly_rate');
                if(working_hours && working_hours > 0){
                    document.getElementById('total').value = (parseInt(working_hours) * parseFloat(hourly_rate)).toFixed(0);
                    document.getElementById('total_real').value = (parseInt(working_hours) * parseFloat(hourly_rate)).toFixed(0);
                }
            }
        });
    });

    function paidMilestone(milestone_index, milestone_price, milestone_status){
        let milestonesData = [];
        document.querySelectorAll('.milestones_data').forEach(function(input) {
            var price = input.value;
            var index = input.getAttribute("data-index");
            var status = input.getAttribute("data-status");

            milestonesData.push({
                milestone: index, 
                price: price, 
                status: (milestone_index == index || status == 'paid') ? 'paid' : 'unpaid'
            });

            if(status == 'paid'){
                document.querySelector(`.input_${index}`).classList.add('bg-success', 'text-light');
                document.querySelector(`.input_${index}`).classList.remove('bg-warning', 'text-dark');
            }else if(milestone_index == index){
                document.querySelector(`.input_${index}`).classList.add('bg-success', 'text-light');
                document.querySelector(`.input_${index}`).classList.remove('bg-warning', 'text-dark');
            }else{
                document.querySelector(`.input_${index}`).classList.remove('bg-success', 'text-light');
                document.querySelector(`.input_${index}`).classList.add('bg-warning', 'text-dark');
            }
        });
        document.getElementById('milestonesDataInput').value = JSON.stringify(milestonesData);
        document.getElementById('milestone_number').value = milestone_index;
        document.getElementById('total').value = milestone_price;
        document.getElementById('total_real').value = milestone_price;
        console.log(milestone_price);
    }

    var projectIdToSelect = '{{$report->project_id}}';

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

                    if(project.project_type){
                        option.setAttribute('data-project_type', project.project_type);
                    }
                    if(project.hourly_rate){
                        option.setAttribute('data-hourly_rate', project.hourly_rate);
                    }
                    
                    // Set 'selected' property if projectId matches
                    if (project.id == parseInt(projectIdToSelect)) {
                        option.selected = true;
                    }

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