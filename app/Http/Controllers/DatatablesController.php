<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use App\B2c;
use Yajra\Datatables\Datatables;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class DatatablesController extends Controller
{
    /**
     * Displays datatables front end view
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::where('role','=','basic')->get();
        $hits = B2c::where('status','=','hit')->get();
        $misses = B2c::where('status','=','miss')->get();
        $addition = $hits->count() + $misses->count();
        return view('datatables.index')->with('users', $users)->with('hits', $hits)->with('misses', $misses)->with('addition', $addition);
    }

    public function getUsage($id)
    {
        $user = User::where('role','=','basic')->where('id','=',$id)->first();
        $hits = B2c::where('status','=','hit')->where('id','=',$id)->get();
        $misses = B2c::where('status','=','miss')->where('id','=',$id)->get();
        $addition = $hits->count() + $misses->count();
        return view('datatables.index')->with('user', $user)->with('hits', $hits)->with('misses', $misses)->with('addition', $addition);
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        return Datatables::of(User::query())->make(true);
    }

    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
