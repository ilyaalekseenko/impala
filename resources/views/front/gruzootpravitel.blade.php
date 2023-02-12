@extends('layouts.main')
@section('content')
        <gruzootpravitel-component :auth_user='@json($auth_user)'></gruzootpravitel-component>
@endsection
