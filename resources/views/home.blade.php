@extends('layouts.app')

@section('content')
<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}

                    <p>This is your application dashboard.</p>
                    @canany(['create-role', 'edit-role', 'delete-role'])
                        <a class="btn btn-primary" href="{{ route('roles.index') }}">
                            <i class="bi bi-person-fill-gear"></i> Manage Roles
                        </a>
                    @endcanany

                    @canany(['show-user', 'create-user', 'edit-user', 'delete-user'])
                        <a class="btn btn-success" href="{{ route('users.index') }}">
                            <i class="bi bi-people"></i> Manage Users
                        </a>
                    @endcanany

                    @canany(['show-client', 'create-client', 'edit-client', 'delete-client'])
                        <a class="btn btn-warning" href="{{ route('clients.index') }}">
                            <i class="bi bi-bag"></i> Manage Clients
                        </a>
                    @endcanany

                    @canany(['show-project', 'create-project', 'edit-project', 'delete-project'])
                        <a class="btn btn-secondary" href="{{ route('projects.index') }}">
                            <i class="bi bi-cast"></i> Manage Projects
                        </a>
                    @endcanany

                    @canany(['show-billing', 'create-billing', 'edit-billing', 'delete-billing'])
                        <a class="btn btn-info" href="{{ route('billings.index') }}">
                            <i class="bi bi-cash-stack"></i> Manage Billings
                        </a>
                    @endcanany
                    
                    @canany(['show-scrum', 'create-scrum', 'edit-scrum', 'delete-scrum'])
                        <a class="btn btn-info" href="{{ route('scrums.index') }}">
                            <i class="bi bi-code-slash"></i> Scrum
                        </a>
                    @endcanany

                    @canany(['show-report', 'create-report', 'edit-report', 'delete-report'])
                        <a class="btn btn-secondary" href="{{ route('reports.index') }}">
                            <i class="bi bi-flag"></i> Report
                        </a>
                    @endcanany

                    <p>&nbsp;</p>
                </div>
            </div>
        </div>
    </div>
</div>
@include('tools.index')
@endsection