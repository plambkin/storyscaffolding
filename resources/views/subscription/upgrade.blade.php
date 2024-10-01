@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Upgrade to Premium</h1>
    <p>To access the custom features, please upgrade to a premium subscription.</p>
    <!-- Include a link to the payment page or further instructions -->
    <a href="{{ route('payment') }}" class="btn btn-primary">Upgrade Now</a>
</div>
@endsection
