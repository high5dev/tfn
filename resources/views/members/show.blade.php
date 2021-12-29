@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Show a member</h3>

    <form method="post" action="/admin/users">
        @csrf()

        <fieldset>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="id">ID: </label>
                <div class="col-sm-10">
                    <input type="text" id="id" name="id" class="form-control"
                           value="{{ $member->id }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="username">Username: </label>
                <div class="col-sm-10">
                    <input type="text" id="username" name="ussername" class="form-control"
                           value="{{ $member->username }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="email">Email: </label>
                <div class="col-sm-10">
                    <input type="text" id="email" name="email" class="form-control"
                           value="{{ $member->email }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="firstip">First IP: </label>
                <div class="col-sm-10">
                    <input type="text" id="firstip" name="firstip" class="form-control"
                           value="{{ $member->firstip }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="joined_recently">New Member: </label>
                <div class="col-sm-10">
                    <input type="text" id="joined_recently" name="joined_recently" class="form-control"
                           value="{{ $member->joined_recently ? 'YES' : 'NO' }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="updated_at">Last checked: </label>
                <div class="col-sm-10">
                    <input type="text" id="updated_at" name="updated_at" class="form-control"
                           value="{{ $member->updated_at }}" readonly>
                </div>
            </div>

        </fieldset>

    </form>

    <form method="post" action="/members/zap/{{ $member->id }}">
        @csrf
        @method("GET")
        <button class='btn btn-default' type="submit" alt="Zap Account" value="!!! ZAP THIS ACCOUNT !!!"
                onclick="return okCancel('Are you sure you want to zap this member?')">
        </button>
    </form>

    <a href="/members">Back to list of members</a>

@endsection
