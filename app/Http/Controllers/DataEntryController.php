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

            $validatedData['user_id'] = auth()->id();

            DataEntry::create($validatedData);
            return redirect()->route('data.entry')->with('success', 'Data has been successfully saved!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Data Entry Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred. Please try again.')->withInput();
        }
    }
    public function updateDataChanges(Request $request, $id)
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

            if(auth()->user()->role !== 'admin'){
                $data = DataEntry::find($id);
                if($data->user_id !== auth()->id()){
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not authorized to update this data'
                    ], 403);
                }
            }

            DataEntry::where('id', $id)->update($validatedData);
            return response()->json(['success' => true, 'message' => 'Data has been successfully Updated!']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => $e->validator]);
        } catch (\Exception $e) {
            \Log::error('Data Entry Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An unexpected error occurred. Please try again.']);
        }
    }

    public function getData(Request $request)
    {
        $data = DataEntry::when(
            auth()->user()->role !== 'admin',
            function ($query) {
                $query->where('user_id', auth()->id());
            }
        )->get()
          ->map(function (DataEntry $lease) {
              $lease->lease_expiration = Carbon::parse($lease->lease_expiration)->format('Y-m-d');
              return $lease;
          });
        return DataTables::of($data)->make(mDataSupport: true);
    }

    public function getDataById($id)
    {
        $data = DataEntry::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
