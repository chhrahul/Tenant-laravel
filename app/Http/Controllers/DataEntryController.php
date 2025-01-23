<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataEntry;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

class DataEntryController extends Controller
{
    /**
     * Show the data entry form.
     *
     * @return \Illuminate\View\View
     */
    public function showForm()
    {
        return view('data-entry-form');
    }

    public function showReport()
    {
        return view('report');
    }

    /**
     * Handle the form submission and store the data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeData(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'building_name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'tower' => 'required|string|max:50',
                'tenant_name' => 'required|string|max:255',
                'suit' => 'required|numeric',
                'rent' => 'required|string|max:255',
                'square_feet' => 'required|numeric',
                'percentage_of_total' => 'required|numeric|between:0,100',
                'lease_expiration' => 'required|date',
            ]);

            DataEntry::create($validatedData);
            return redirect()->route('data.entry')->with('success', 'Data has been successfully saved!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Data Entry Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.')->withInput();
        }
    }

    public function getData(Request $request)
    {
        $data = DataEntry::all()->map(function (DataEntry $lease): DataEntry {
            $lease->lease_expiration = Carbon::parse($lease->lease_expiration)->format('Y-m-d');
            return $lease;
        });
        return DataTables::of($data)->make(mDataSupport: true);
    }
}
