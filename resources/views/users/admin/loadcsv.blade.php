@extends('layouts.app')
@section('title', 'Hierarchy of Users')
@section('content')
    <h1>Upload CSV</h1>
    <form action="{{ route('loadcsv') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="csv_file">Select CSV File</label>
            <input type="file" name="csv_file" id="csv_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
@endsection