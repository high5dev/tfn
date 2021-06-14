@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    @foreach($errors->all() as $message)
        <div class="alert alert-warning">{{ $message }}</div>
    @endforeach

    <h3>Scan Posts</h3>

    <div>Scan by post ID:</div>
    <form method="get" action="/posts/list" class="form-inline">
        @csrf()

        <div class="form-group">
            <input type="text" id="postid" name="postid" class="form-control" value="{{ old('postid') }}"
                   placeholder="Post ID" {{ $errors->has('postid') ? ' autofocus' : '' }} required>
        </div>

        <div class="form-group">
            <select id="type" name="type" class="form-control">
                <option value="b">Both</option>
                <option value="o">OFFERs</option>
                <option value="w">WANTEDs</option>
            </select>
        </div>

        <div class="form-group">
            <label class="sr-only" for="submit">&nbsp;</label>
            <input type="submit" id="submit" name="submit" value="Go">
        </div>

    </form>

    <div>Scan by date & time:</div>
    <form method="get" action="/posts/list" class="form-inline">
        @csrf()

        <div class="form-group">
            <input type="text" id="date" name="date" class="form-control" value="{{ old('date') }}"
                   placeholder="YYYY-MM-DD" {{ $errors->has('date') ? ' autofocus' : '' }} required>
        </div>

        <div class="form-group">
            <input type="text" id="time" name="time" class="form-control" value="{{ old('time') }}"
                   placeholder="HH:MM" {{ $errors->has('time') ? ' autofocus' : '' }} required>
        </div>

        <div class="form-group">
            <select id="type" name="type" class="form-control">
                <option value="b">Both</option>
                <option value="o">OFFERs</option>
                <option value="w">WANTEDs</option>
            </select>
        </div>

        <div class="form-group">
            <label class="sr-only" for="submit">&nbsp;</label>
            <input type="submit" id="submit" name="submit" value="Go">
        </div>

    </form>

@endsection
