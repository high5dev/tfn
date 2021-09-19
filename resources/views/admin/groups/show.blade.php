@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Edit a group</h3>

    <form method="post" action="/admin/groups">
        @csrf()
        @method('PATCH')

        <fieldset>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="name">Name:</label>
                <div class="col-sm-10">
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $group->name) }}"
                           {{ $errors->has('name') ? ' autofocus' : '' }} required>
                    @if ($errors->has('name'))
                        <div class="alert alert-warning">{{ $errors->first('name') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="link">Link:</label>
                <div class="col-sm-10">
                    <input type="text" id="link" name="link" class="form-control" placeholder="https://xxx"
                           value="{{ old('link', $group->link) }}" {{ $errors->has('link') ? ' autofocus' : '' }} required>
                    @if ($errors->has('link'))
                        <div class="alert alert-warning">{{ $errors->first('link') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="goa">GOA:</label>
                <div class="col-sm-10">
                    <input type="text" id="goa" name="goa" class="form-control" placeholder=""
                           value="{{ old('goa', $group->goa) }}" {{ $errors->has('goa') ? ' autofocus' : '' }} required>
                    @if ($errors->has('goa'))
                        <div class="alert alert-warning">{{ $errors->first('goa') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="region">Region:</label>
                <div class="col-sm-10">
                    <input type="text" id="region" name="region" class="form-control" placeholder=""
                           value="{{ old('region', $group->region) }}" {{ $errors->has('region') ? ' autofocus' : '' }} required>
                    @if ($errors->has('region'))
                        <div class="alert alert-warning">{{ $errors->first('region') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="country">Country:</label>
                <div class="col-sm-10">
                    <input type="text" id="country" name="country" class="form-control" placeholder=""
                           value="{{ old('country', $group->country) }}" {{ $errors->has('country') ? ' autofocus' : '' }} required>
                    @if ($errors->has('country'))
                        <div class="alert alert-warning">{{ $errors->first('country') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="url">URL:</label>
                <div class="col-sm-10">
                    <input type="text" id="url" name="url" class="form-control" placeholder=""
                           value="{{ old('url', $group->url) }}" {{ $errors->has('url') ? ' autofocus' : '' }} required>
                    @if ($errors->has('url'))
                        <div class="alert alert-warning">{{ $errors->first('url') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="contact">Contact:</label>
                <div class="col-sm-10">
                    <input type="text" id="contact" name="contact" class="form-control" placeholder=""
                           value="{{ old('contact', $group->contact) }}" {{ $errors->has('contact') ? ' autofocus' : '' }} required>
                    @if ($errors->has('contact'))
                        <div class="alert alert-warning">{{ $errors->first('contact') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="admin_password">Your Password:</label>
                <div class="col-sm-10">
                    <input type="password" id="admin_password" name="admin_password" class="form-control"
                           {{ $errors->has('admin_password') ? ' autofocus' : '' }} required>
                    @if ($errors->has('admin_password'))
                        <div class="alert alert-warning">{{ $errors->first('admin_password') }}</div>
                    @endif
                </div>
            </div>

        </fieldset>

        <input type="hidden" name="id" value="{{ $user->id }}">

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="submit">&nbsp;</label>
            <div class="col-sm-10">
                <input type="submit" id="submit" name="submit" value="Save">
            </div>
        </div>

    </form>

@endsection
