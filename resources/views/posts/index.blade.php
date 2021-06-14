@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Scan Posts</h3>

    <div>Scan by post ID:</div>
    <form method="post" action="/posts/byid" class="form-inline">
        @csrf()

        <div class="form-group">
            <input type="text" id="postid" name="postid" class="form-control" value="{{ old('postid') }}"
                   placeholder="Post ID" {{ $errors->has('postid') ? ' autofocus' : '' }} required>
            @if ($errors->has('postid'))
                <div class="alert alert-warning">{{ $errors->first('postid') }}</div>
            @endif
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
    <form method="post" action="/posts/bytime" class="form-inline">
        @csrf()

        <div class="form-group">
            <input type="text" id="date" name="date" class="form-control" value="{{ old('date') }}"
                   placeholder="YYYY-MM-DD" {{ $errors->has('date') ? ' autofocus' : '' }} required>
            @if ($errors->has('date'))
                <div class="alert alert-warning">{{ $errors->first('date') }}</div>
            @endif
        </div>

        <div class="form-group">
            <input type="text" id="time" name="time" class="form-control" value="{{ old('time') }}"
                   placeholder="HH:MM" {{ $errors->has('time') ? ' autofocus' : '' }} required>
            @if ($errors->has('time'))
                <div class="alert alert-warning">{{ $errors->first('time') }}</div>
            @endif
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
