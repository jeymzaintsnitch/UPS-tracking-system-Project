@extends('template.main')
@section('content') 
    
    <h1>Sum: {{$sum}}</h1>
    <h1>Difference: {{ $diff}}</h1>
    <h1>Product: {{ $multiply }}</h1>
    <h1>Quotient: {{$divide}}</h1>

@endsection