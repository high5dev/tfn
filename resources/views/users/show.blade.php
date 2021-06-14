@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Edit a user</h3>

    <form method="post" action="/admin/users">
        @csrf()
        @method('PATCH')

        <fieldset>

            <legend>Personal Details</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="name">User's Name:</label>
                <div class="col-sm-10">
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                           {{ $errors->has('name') ? ' autofocus' : '' }} required>
                    @if ($errors->has('name'))
                        <div class="alert alert-warning">{{ $errors->first('name') }}</div>
                    @endif
                </div>
            </div>

            <legend>Security details</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="email">Email:</label>
                <div class="col-sm-10">
                    <input type="email" id="email" name="email" class="form-control" placeholder="e.g. fred@example.com"
                           value="{{ old('email', $user->email) }}"
                           {{ $errors->has('email') ? ' autofocus' : '' }} required>
                    @if ($errors->has('email'))
                        <div class="alert alert-warning">{{ $errors->first('email') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="password">Password:</label>
                <div class="col-sm-10">
                    <input type="password" id="password" name="password"
                           class="form-control" {{ $errors->has('password') ? ' autofocus' : '' }}>
                    @if ($errors->has('password'))
                        <div class="alert alert-warning">{{ $errors->first('password') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="password_confirmation">Confirm:</label>
                <div class="col-sm-10">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="form-control" {{ $errors->has('password_confirmation') ? ' autofocus' : '' }}>
                    <input type="checkbox"
                           onchange="document.getElementById('password').type = this.checked ? 'text' : 'password';document.getElementById('password_confirmation').type = this.checked ? 'text' : 'password'">
                    Show password
                    @if ($errors->has('password_confirmation'))
                        <div class="alert alert-warning">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="roles">Roles:</label>
                <div class="col-sm-10">
                    <select id="roles" name="roles[]" class="form-control" multiple>
                        @foreach($availableRoles as $role)
                            <option
                                value="{{ $role->name }}"{{ in_array($role->name, old('roles', $assignedRoles)) ? ' selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('roles'))
                        <div class="alert alert-warning">{{ $errors->first('roles') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="last_login_at">Last Login at:</label>
                <div class="col-sm-10">
                    <input type="text" id="last_login_at" name="last_login_at" class="form-control"
                           value="{{ $user->last_login_at }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="last_login_ip">Last Login IP:</label>
                <div class="col-sm-10">
                    <input type="text" id="last_login_ip" name="last_login_ip" class="form-control"
                           value="{{ $user->last_login_ip }}" readonly>
                </div>
            </div>

            <legend>Confirmation</legend>

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

    <script type="text/javascript">
        $(document).ready(function () {
            $("#roles").select2([]);
        });
    </script>

@endsection
