<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Instantiate a new clientController instance.
     */
    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:show-client|create-client|edit-client|delete-client', ['only' => ['index','show']]);
       $this->middleware('permission:create-client', ['only' => ['create','store']]);
       $this->middleware('permission:edit-client', ['only' => ['edit','update']]);
       $this->middleware('permission:delete-client', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Client::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
        }
        
        $clients = $query->latest()->paginate(10);

        return view('clients.index', [
            'clients' => $clients,
            'search' => $search ?? ''
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request): RedirectResponse
    {
        Client::create($request->all());
        return redirect()->route('clients.index')
                ->withSuccess('New Client is added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client): View
    {
        // Load the related projects along with the client
        $client->load('projects');

        return view('clients.show', [
            'client' => $client,
            'projects' => $client->projects
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client): View
    {
        return view('clients.edit', [
            'client' => $client
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->all());
        return redirect()->back()
                ->withSuccess('Client is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();
        return redirect()->route('clients.index')
                ->withSuccess('Client is deleted successfully.');
    }
}