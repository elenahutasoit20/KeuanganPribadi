@extends('layouts.app')

@section('content')

<h3>Dashboard</h3>

<div class="row">
    <div class="col-md-3">
        <div class="card p-3 card-box">Balance <h4>$2,123,490</h4></div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 card-box">Sales <h4>1,230</h4></div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 card-box">Expenses <h4>$321</h4></div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 card-box">Users <h4>852</h4></div>
    </div>
</div>

<div class="card mt-4 p-4 card-box" style="height:300px;">
    <h5>Activity Chart</h5>
    <p>Tempat grafik nanti</p>
</div>

@endsection
