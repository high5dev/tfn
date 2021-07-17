@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    @foreach($errors->all() as $message)
        <div class="alert alert-warning">{{ $message }}</div>
    @endforeach

    <h3>Scan Posts</h3>

    <div class="text-warning">
        If you are about to start scanning, please make sure you tick the "Scanning" checkbox
        so others can see you are scanning and we don't duplicate effort!
    </div>

    <div>Scan from midnight:</div>
    <form method="get" action="/posts/list" class="form-inline">
        @csrf()

        <div class="form-group">
            <select id="type" name="type" class="form-control">
                <option value="b">Both</option>
                <option value="o">OFFERs</option>
                <option value="w">WANTEDs</option>
            </select>
        </div>

        <div class="form-group">
            Scanning: <input type="checkbox" id="scanning" name="scanning" value="1">
        </div>

        <div class="form-group">
            <label class="sr-only" for="submit">&nbsp;</label>
            <input type="submit" id="submit" name="submit" value="Go">
        </div>

        <input type="hidden" name="posts" value="midnight">

    </form>

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
            Scanning: <input type="checkbox" id="scanning" name="scanning" value="1">
        </div>

        <div class="form-group">
            <label class="sr-only" for="submit">&nbsp;</label>
            <input type="submit" id="submit" name="submit" value="Go">
        </div>

        <input type="hidden" name="posts" value="bypostid">

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
            Scanning: <input type="checkbox" id="scanning" name="scanning" value="1">
        </div>

        <div class="form-group">
            <label class="sr-only" for="submit">&nbsp;</label>
            <input type="submit" id="submit" name="submit" value="Go">
        </div>

        <input type="hidden" name="posts" value="bydatetime">

    </form>

    <div class="jumbotron">
        If you are using this facility to carry out some scanning, then you must tick the "Scanning" checkbox.
        This will alert other team members that someone is scanning so we don't duplicate effort.
        Unlike the old SpamTool it doesn;t do any harm for multiple people to be accessing this at the same time,
        so if you just want to lookup a post or need to check something feel free to not tick the "Scanning"
        checkbox. It should only be used when you are performing a scan that will be entered into the database.
    </div>

@endsection
