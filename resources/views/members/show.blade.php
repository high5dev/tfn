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
                    <input type="email" id="username" name="ussername" class="form-control"
                           value="{{ $member->username }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="email">Email: </label>
                <div class="col-sm-10">
                    <input type="text" id="email" name="email"
                           value="{{ $member->email }}" readonly>
                </div>
            </div>

        </fieldset>

    </form>

@endsection