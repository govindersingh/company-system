@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Add New Report
                </div>
                <div class="float-end">
                    <a href="{{ route('reports.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.store') }}" method="post">
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
                            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" value="{{ old('user_id') }}">
                                @foreach ($users as $user)
                                <option value="{{$user->id}}">{{ $user->name }}</option>
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
                          <input type="number" min="0" class="form-control @error('working_hours') is-invalid @enderror" id="working_hours" name="working_hours" value="0">
                            @if ($errors->has('working_hours'))
                                <span class="text-danger">{{ $errors->first('working_hours') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="project_type" class="col-md-4 col-form-label text-md-end text-start">Project Type</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control" id="project_type" disabled>
                        </div>
                    </div>

                    <div class="mb-3 row" id="custom_element"></div>

                    <div class="mb-3 row">
                        <label for="total" class="col-md-4 col-form-label text-md-end text-start">Total</label>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon2">$</span>
                                <input type="number" class="form-control @error('total') is-invalid @enderror" id="total" value="0" disabled>
                                <input type="hidden" min="0" class="form-control " id="total_real" name="total" value="0">
                            </div>
                            @if ($errors->has('total'))
                                <span class="text-danger">{{ $errors->first('total') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="date" class="col-md-4 col-form-label text-md-end text-start">Date</label>
                        <div class="col-md-6">
                          <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ $matchDate ? $matchDate->format('Y-m-d') : '' }}">
                            @if ($errors->has('date'))
                                <span class="text-danger">{{ $errors->first('date') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Add Report">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    
</div>
<script>
    let all_projects = [];
    let selected_product = null;

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('client_id').addEventListener('change', function() {
            var clientId = this.value;
            getProjects(clientId);
        });

        document.getElementById('project_id').addEventListener('change', function() {
            var projectId = this.value;
            makeTypeOptions(projectId);
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
        console.log(milestonesData);
        document.getElementById('total').value = milestone_price;
        document.getElementById('total_real').value = milestone_price;
    }

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

                    document.getElementById('project_id').add(option);
                });

                if(data?.[0]){
                    all_projects = data;
                    makeTypeOptions(data[0].id);
                }
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

    function makeTypeOptions(project_id){
        let project = all_projects.find(obj => obj.id == project_id);
        let fixed_html = '';
        let hourly_html = '';
        let parser = new DOMParser();
        selected_product = project;
        document.getElementById("project_type").value = project.project_type;

        if(project.project_type == "Fixed"){
            let types = JSON.parse(project.milestones_rate);
            fixed_html = `<label for="milestones_rate" class="col-md-4 col-form-label text-md-end text-start">Milestones Rate</label>
            <div class="col-md-6">
                <div id="milestones">
                    ${types.map(milestones_rate => {
                        return `<div class="input-group ${(milestones_rate.milestone != 1) ? 'my-1' : ''}">
                            <div class="input-group-prepend">
                                <div class="input-group-text input_${milestones_rate.milestone} ${(milestones_rate.status == 'paid') ? 'bg-success text-light' : 'bg-warning text-dark'}">Milestone ${milestones_rate.milestone}</div>
                            </div>
                            <input type="number" min="0" class="form-control milestones_data" name="milestones_data[${milestones_rate.milestone}]" placeholder="Price in number" value="${milestones_rate.price}" data-index="${milestones_rate.milestone}" data-status="${milestones_rate.status}" disabled>
                            ${(milestones_rate.status == "unpaid") ? `<a href="javascript:void(0);" class="btn btn-secondary" onClick="paidMilestone('${milestones_rate.milestone}', '${milestones_rate.price}', '${milestones_rate.status}');">Paid</a>` : ''}
                        </div>`;
                    }).join('')}
                </div>
                <input type="hidden" class="form-control" name="milestones_rate" id="milestonesDataInput" value="${project.milestones_rate}">
            </div>`;

            const html = parser.parseFromString(fixed_html, 'text/html');
            document.getElementById('custom_element').innerHTML = html.body.innerHTML;

        }else{
            hourly_html = `<label for="hourly_rate" class="col-md-4 col-form-label text-md-end text-start">Hourly Rate</label>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="text" class="form-control" id="hourly_rate" value="${project.hourly_rate}" disabled>
                </div>
            </div>`;

            const html = parser.parseFromString(hourly_html, 'text/html');
            document.getElementById('custom_element').innerHTML = html.body.innerHTML;
        }
    }
    
</script>
@endsection