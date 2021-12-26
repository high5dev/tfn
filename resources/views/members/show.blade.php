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
                <label class="col-sm-2 col-form-label" for="joined_recently">: </label>
                <div class="col-sm-10">
                    <input type="text" id="joined_recently" name="joined_recently" class="form-control"
                           value="{{ $member->joined_recently ? 'YES' : 'NO' }}" readonly>
                </div>
            </div>

        </fieldset>

    </form>

@endsection
