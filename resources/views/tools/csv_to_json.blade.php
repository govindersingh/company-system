@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">CSV to JSON Converter</h1>

    <div class="row">
    <form action="{{ route('tools.csv_to_json_process') }}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{Auth::user()->email}}" required>
    </div>
    <div class="mb-3">
        <label for="csvFile" class="form-label">Choose CSV file</label>
        <input type="file" class="form-control" id="csvFile" name="csvFile" accept=".csv" required>
    </div>
    <button type="submit" class="btn btn-primary">Process CSV</button>
</form>
</div>
@endsection
