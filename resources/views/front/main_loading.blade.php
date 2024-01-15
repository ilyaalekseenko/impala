@extends('layouts.main')
@section('content')
    <main-loading-component :auth_user='@json($auth_user)'></main-loading-component>
@endsection
