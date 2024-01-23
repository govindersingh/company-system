@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-10">

      <div class="card">
        <div class="card-header">
            <div class="float-start">
                Export Report
            </div>
            <div class="float-end">
                <a href="{{ route('reports.index') }}" class="btn btn-primary btn-sm">&larr; Back</a>
            </div>
        </div>

        
        <div class="card-body">
          @if (session('status'))
            <div class="alert alert-success" role="alert">
              {{ session('status') }}
            </div>
          @endif
          <form action="{{ route('reports.exportout') }}" id="exportform" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="json_data" id="json_data">
            <div class="row col-md-12">
              <div class="col-md-6">
                <div class="input-group">
                  <span class="input-group-text" id="basic-addon1"><i class="bi bi-calendar"></i></span>
                  <input type="date" class="form-control" id="exportdate1" placeholder="Export from" aria-label="Export from" aria-describedby="basic-addon1">
                </div>
              </div>
              <div class="col-md-6">
                <div class="input-group">
                  <span class="input-group-text" id="basic-addon2"><i class="bi bi-calendar"></i></span>
                  <input type="date" class="form-control" id="exportdate2" placeholder="Export to" aria-label="Export to" aria-describedby="basic-addon2">
                </div>
              </div>
            </div>
          </form>
        </div>
          
        <div class="card-footer">
            <input type="button" class="offset-md-5 btn btn-primary" id="exportReport" value="Export Report">
        </div>
      </div>
    </div>
</div>
<script>
  document.addEventListener("change", function(event){
    let exportdate = event.target;
    if(exportdate.id == "exportdate1"){
      document.getElementById("exportdate2").min = exportdate.value;
    }else if(exportdate.id == "exportdate2"){
      document.getElementById("exportdate1").max = exportdate.value;
    }
  });

  document.getElementById("exportReport").addEventListener("click", function(event){
    event.preventDefault();

    let exportdate1 = document.getElementById("exportdate1").value;
    let exportdate2 = document.getElementById("exportdate2").value;
    if(exportdate1 == "" || exportdate2 == ""){
      alert('Please select correct date.');
      return false;
    }

    var date1 = new Date(exportdate1);
    var date2 = new Date(exportdate2);
    if (date1.getTime() > date2.getTime()) {
      alert(`"Date from" should earlier than "Date to"`);
      return false;
    }

    document.getElementById('json_data').value = JSON.stringify({date1: exportdate1, date2: exportdate2});

    document.getElementById("exportform").submit();
  });
</script>
@endsection