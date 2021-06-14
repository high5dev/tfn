@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Scan Posts</h3>

    <form method="post" action="/posts/list" class="form-inline">
        @csrf()

        <div class="form-group">
            <label class="sr-only" for="postid">Start Post ID:</label>
            <input type="text" id="postid" name="postid" class="form-control" value="{{ old('postid') }}"
                   {{ $errors->has('postid') ? ' autofocus' : '' }} required>
            @if ($errors->has('postid'))
                <div class="alert alert-warning">{{ $errors->first('postid') }}</div>
            @endif
        </div>

        <div class="form-group">
            <label class="sr-only" for="postid">Type:</label>
            <select id="type" name="type" class="form-control">
                <option value="b">Both</option>
                <option value="o">OFFERs</option>
                <option value="w">WANTEDs</option>
            </select>
        </div>

        <div class="form-group">
            <label class="sr-only" for="submit">&nbsp;</label>
            <input type="submit" id="submit" name="submit" value="Create">
        </div>

    </form>

@endsection
