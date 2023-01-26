@extends('layouts.main')
@section('content')
    <create-orders-component :auth_user='@json($auth_user)'></create-orders-component>
@endsection
