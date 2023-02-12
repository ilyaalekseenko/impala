@extends('layouts.main')
@section('content')
        <perevozchiki-component :auth_user='@json($auth_user)'></perevozchiki-component>
@endsection
