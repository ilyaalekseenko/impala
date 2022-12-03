@extends('layouts.main')
@section('content')
    <div class="container">
        <div class="row doc_header">
    <a href="{{ url('/orders') }}" class="offset-1 col-2 btn btn-primary ">На главную</a>
    <a href="{{ url()->previous() }}" class="offset-1 col-2 btn btn-secondary">Назад</a>
        </div>
        </div>

    <docs-settings-component></docs-settings-component>
@endsection
