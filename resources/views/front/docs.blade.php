@extends('layouts.main')
@section('content')

    <main-settings-component :transfer='{{ json_encode($transfer) }}'></main-settings-component>
@endsection
