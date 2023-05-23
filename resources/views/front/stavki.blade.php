@extends('layouts.main')
@section('content')
        <stavki-component :auth_user='@json($auth_user)'></stavki-component>
@endsection
