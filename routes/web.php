<?php

use Illuminate\Http\Request;
use App\User;
use App\Product;
use App\B2c;
use Yajra\Datatables\Datatables;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

//product routes

Route::get('/admin/products', function () {
    return \App\Product::all();
});

Route::delete('/admin/products/delete/{id}', function ($id) {
    $product = \App\Product::find($id);
    $product->delete();
});

Route::post('/admin/products/create', function (Request $request) {
    $input = $request->all();
    Product::create($input);
});

//Usage Routes

Route::get('/admin/users', function () {
    return \App\User::where('role', '=','basic')->get();
});

Route::put('/admin/user/update/{id}', function (Request $request) {
    $user = \App\User::find($request->id);
    $user->activate = $request->activate;
    $user->response_type = $request->response_type;
    $user->product_type = $request->report_type;
    $user->live_request_username = $request->live_request_username;
    $user->live_request_password = $request->live_request_password;
    $user->save();
});

Route::get('/admin/view/usage/{id}', 'DatatablesController@getUsage');


Route::resource('datatables', 'DatatablesController', [
    'anyData'  => 'datatables.data',
    'getIndex' => 'datatables',
])->middleware('admin');

Route::get('/datatables-data', function () {
    return Datatables::of(User::where('role', '=','basic'))
    ->addColumn('action', function ($users) {
        return '<a href="/view-org" class="btn btn-primary"> View</a>';
    })
    ->make(true);
})->name('datatables-data');


Route::get('/download-report', 'DatatablesController@export')->name('dld');

Route::get('view-org/{id}', function () {
    $user = User::where('id','=','19')->firstOrFail();
    return Datatables::of(Fico::where('email', '=', $user->email)->get())
    ->make(true);
})->name('view-org');
