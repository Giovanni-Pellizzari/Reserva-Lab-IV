@extends('layouts.app') <!-- Assuming you have a layout file -->

@section('content')
    <div class="container">
        <h1>Create Service</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('services.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Service Name</label>
                <input type="text" name="name" id="name" required class="form-control">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" required class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image URL</label>
                <input type="text" name="image" id="image" required class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Create Service</button>
        </form>
    </div>
@endsection
