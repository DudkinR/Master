@extends('layouts.app')
@section('title', 'Ієрархія користувачів')
@section('content')
    <h1>Завантажити CSV</h1>
    <form action="{{ route('loadcsv') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="csv_file">Виберіть CSV файл</label>
            <input type="file" name="csv_file" id="csv_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Завантажити</button>
    </form>
@endsection