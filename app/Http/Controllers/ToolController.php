<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Jobs\ConvertCsvToJson;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ToolController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('tools.index');
    }

    /**
     * csvToJson convert CSV to JSON.
     */
    public function csvToJson(Request $request){

        $request->validate([
            'email' => 'required|string',
            'csvFile' => 'required|file|mimes:csv', // Adjust max file size as needed
        ]);

        $timestamp_filename = Carbon::now()->getTimestampMs();
        $csvFilePath = $request->file('csvFile')->storeAs('csv_files', $timestamp_filename.'_uploaded_file.csv', 'local');

        Log::info('CSV File Path: ' . storage_path('app/' . $csvFilePath));
        ConvertCsvToJson::dispatch(storage_path('app/' . $csvFilePath), $timestamp_filename, $request->input('email'))->onQueue('high');

        return redirect()->back()->with('success', 'Data being processed. You will be notified on your Email upon completion. Attachment: ' . $timestamp_filename);
    }


}
