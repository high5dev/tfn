@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Scan Summary</h3>

    <form method="post" action="/posts/summary" class="form">
        @csrf()

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="zaps">Zaps :</label>
            <div class="col-sm-10">
                <input type="text" id="zaps" name="zaps" class="form-control" value="{{ old('zaps') }}"
                       {{ $errors->has('zaps') ? ' autofocus' : '' }} required>
                @if ($errors->has('zaps'))
                    <div class="alert alert-warning">{{ $errors->first('zaps') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="notes">Notes :</label>
            <div class="col-sm-10">
                <textarea id="notes" name="notes" class="form-control"
                          {{ $errors->has('notes') ? ' autofocus' : '' }} required>{{ old('notes') }}</textarea>
                @if ($errors->has('notes'))
                    <div class="alert alert-warning">{{ $errors->first('notes') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group col-sm-2">
            <label class="sr-only" for="submit">&nbsp;</label>
            <input type="submit" id="submit" name="submit" value="Save">
        </div>

        <input type="hidden" name="id" value="{{ $id }}">
    </form>

@endsection
