@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Create a new watchword</h3>

    <form method="post" action="/watchwords">
        @csrf()

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="type">Type:</label>
            <div class="col-sm-10">
                <select id="type" name="type" class="form-control"{{ $errors->has('type') ? ' autofocus' : '' }}>
                    <option value="">Select The Type</option>
                    @foreach(['Email', 'Subject'] as $type)
                        <option
                            value="{{ $type }}"{{ $type == old('type') ? ' selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                @if ($errors->has('type'))
                    <div class="alert alert-warning">Please select a type</div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="theword">Watchword:</label>
            <div class="col-sm-10">
                <input type="text" id="theword" name="theword" class="form-control"
                       {{ $errors->has('theword') ? ' autofocus' : '' }} value="{{ old('theword') }}" required>
                @if ($errors->has('theword'))
                    <div class="alert alert-warning">{{ $errors->first('theword') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="submit">&nbsp;</label>
            <div class="col-sm-10">
                <input type="submit" id="submit" name="submit" value="Create">
            </div>
        </div>

    </form>

    <div class="jumbotron">
        <ul>
            <li>Please select what to search on, i.e. the post's "Email" or "Subject"</li>
            <li>You can enter up to 254 characters in the watchword</li>
            <li>The sorter the watchword, the more posts it will catch (less specific)</li>
            <li>The longer the watchword, the less posts it will catch (more specific)</li>
            <li>Try to get a sensible balance between the two!</li>
        </ul>
    </div>

@endsection
