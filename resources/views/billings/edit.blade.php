@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Edit Billing
                </div>
                <div class="float-end">
                    <a href="{{ route('billings.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('billings.update', $billing->id) }}" method="post">
                    @csrf
                    @method("PUT")

                    <div class="mb-3 row">
                        <label for="project_id" class="col-md-4 col-form-label text-md-end text-start">Project</label>
                        <div class="col-md-6">
                            <select class="form-control @error('project_id') is-invalid @enderror" id="project_id" name="project_id" value="{{ old('project_id') }}">
                                @foreach ($Projects as $project)
                                <option value="{{$project->id}}" @if($project->id == $billing->project_id) {{ "selected" }} @endif>{{ $project->name }}</option>
                                @endforeach 
                            </select>
                          
                            @if ($errors->has('client_id'))
                                <span class="text-danger">{{ $errors->first('client_id') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="amount" class="col-md-4 col-form-label text-md-end text-start">Amount</label>
                        <div class="col-md-6">
                          <input type="number" min="0" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ $billing->amount }}">
                            @if ($errors->has('amount'))
                                <span class="text-danger">{{ $errors->first('amount') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="date" class="col-md-4 col-form-label text-md-end text-start">Date</label>
                        <div class="col-md-6">
                          <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ $billing->date }}">
                            @if ($errors->has('date'))
                                <span class="text-danger">{{ $errors->first('date') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="status" class="col-md-4 col-form-label text-md-end text-start">Status</label>
                        <div class="col-md-6">
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" value="{{ old('status') }}">
                                @foreach (Config::get('app.enum_billing_status') as $status)
                                <option value="{{$status}}" @if($status == $billing->status) {{ "selected" }} @endif>{{ $status }}</option>
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
    
@endsection