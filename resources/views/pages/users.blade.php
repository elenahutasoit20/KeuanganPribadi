@extends('layouts.app')

@section('content')

<h3>Users</h3>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>ID</th><th>Name</th><th>Email</th>
        </tr>
    </thead>

    <tbody>
        <tr><td>1</td><td>User 1</td><td>user1@mail.com</td></tr>
        <tr><td>2</td><td>User 2</td><td>user2@mail.com</td></tr>
    </tbody>
</table>

@endsection
