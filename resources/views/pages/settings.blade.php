@extends('layouts.app')

@section('content')

<h3>Settings</h3>

<div class="card p-4 bg-white">
    <h5>Profile</h5>

    <p>Nama: {{ auth()->user()->name }}</p>
    <p>Email: {{ auth()->user()->email }}</p>

    <a href="/logout" class="btn btn-danger">Logout</a>
</div>

@endsection
