@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard 
                    @if (Auth::user()->role=='admin' || Auth::user()->type=='super-admin')
                    &nbsp;&nbsp;&nbsp;
                    >>
                    &nbsp;&nbsp;&nbsp;
                        <a href="{{ url('datatables') }}">Datatables</a>
                    @endif
                </div>

                <div class="card-body">
                    @if (Auth::user()->role=='admin' || Auth::user()->type=='super-admin')
                            <admin-component></admin-component>
                            <usage-component></usage-component>
                        @elseif (Auth::user()->role=='basic')
                            <passport-clients></passport-clients>
                            <passport-authorized-clients></passport-authorized-clients>
                            <passport-personal-access-tokens></passport-personal-access-tokens>
                        @elseif (Auth::user()->role=='super_admin')
                            Super Admin
                        @else
                            There is a problem determining your Access Level Kindly Contact the Administrator
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
