@extends('layouts.main')
@section('content')
        <orders-component :auth_user='@json($auth_user)'></orders-component>
@endsection
