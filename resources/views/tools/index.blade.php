<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Tools') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a class="btn btn-secondary" href="{{ route('tools.csv_to_json') }}">
                        <i class="bi bi-dark"></i> CSV To JSON
                    </a>

                    <p>&nbsp;</p>
                </div>
            </div>
        </div>
    </div>
</div>