<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Asset\ListAsset;
use App\Livewire\Asset\CreateAsset;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| testing
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/newDesign', function () {

//     $hrEmployee = HrEmployee::find(20);
//     $user = User::where('id', $hrEmployee->user_id)->first();
//     $data = $user->getAllPermissions();
//     return  $data;
// });







Route::get('/asset_livewire', ListAsset::class);
