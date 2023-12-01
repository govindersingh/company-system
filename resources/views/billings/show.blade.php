@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Billing Information
                </div>
                <div class="float-end">
                    <a href="{{ route('billings.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    <label for="client" class="col-md-4 col-form-label text-md-end text-start"><strong>Project:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $billing->project->name }}
                    </div>
                </div>

                <div class="row">
                    <label for="amount" class="col-md-4 col-form-label text-md-end text-start"><strong>Amount:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $billing->amount }}
                    </div>
                </div>

                <div class="row">
                    <label for="date" class="col-md-4 col-form-label text-md-end text-start"><strong>Date:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $billing->date }}
                    </div>
                </div>

                <div class="row">
                    <label for="status" class="col-md-4 col-form-label text-md-end text-start"><strong>Status:</strong></label>
                    <div class="col-md-6" style="line-height: 35px;">
                        {{ $billing->status }}
                    </div>
                </div>
        
            </div>
        </div>
    </div>    
</div>
    
@endsection