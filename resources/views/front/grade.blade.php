@extends('layouts.main')
@section('content')
        <grade-component :auth_user='@json($auth_user)'></grade-component>
@endsection
