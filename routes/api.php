<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/testingPluck', function (): void {

    // $rent = DB::table('data_entries')->pluck('rent','address')->toArray();

    // DB::table('test_table')->orderBy('id')->chunkById(100, function (Collection $results) {
    //     foreach ($results as $result) {
    //         // DB::table('test_table')
    //         //     ->where( 'id', $result->id)
    //         //     ->update(['city' => 'Phoenix']);
    //         echo "<pre>";
    //         print_r($result);
    //     }
    // });

    // DB::table('test_table')->orderBy('id')->chunkById(100,function(Collection $data){
    //     echo "<pre>";
    //     print_r($data);
    //     die;
    // });

    // DB::table('test_table')->orderBy('id')->lazy()->each(function (object $test_table) {
    //     echo "<pre>";
    //     print_r($test_table);
        // die;
    // });

    // $result = DB::table('test_table')->max('age');
    // $result = DB::table('test_table')->groupBy('age')->limit(100);
    $result = DB::table('test_table')
    ->select('age', DB::raw('COUNT(*) as total'))
    ->groupBy('age')
    ->limit(100)
    ->get();
    echo "<pre>";
    print_r($result);
    die;
});
