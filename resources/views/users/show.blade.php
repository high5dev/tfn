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
                <label class="col-sm-2 col-form-label" for="company">Company:</label>
                <div class="col-sm-10">
                    <input type="text" id="company" name="company" class="form-control" value="{{ old('company', $user->company) }}"{{ $errors->has('company') ? ' autofocus' : '' }}>
                    @if ($errors->has('company'))
                        <div class="alert alert-warning">{{ $errors->first('company') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="given_name">Given Name:</label>
                <div class="col-sm-10">
                    <input type="text" id="given_name" name="given_name" class="form-control" value="{{ old('given_name', $user->given_name) }}"{{ $errors->has('given_name') ? ' autofocus' : '' }} required>
                @if ($errors->has('given_name'))
                    <div class="alert alert-warning">{{ $errors->first('given_name') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="family_name">Family Name:</label>
                <div class="col-sm-10">
                    <input type="text" id="family_name" name="family_name" class="form-control" value="{{ old('family_name', $user->family_name) }}"{{ $errors->has('family_name') ? ' autofocus' : '' }} required>
                @if ($errors->has('family_name'))
                    <div class="alert alert-warning">{{ $errors->first('family_name') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="phone">Phone:</label>
                <div class="col-sm-10">
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}"{{ $errors->has('phone') ? ' autofocus' : '' }}>
                @if ($errors->has('phone'))
                    <div class="alert alert-warning">{{ $errors->first('phone') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="mobile">Mobile:</label>
                <div class="col-sm-10">
                    <input type="text" id="mobile" name="mobile" class="form-control" value="{{ old('mobile', $user->mobile) }}"{{ $errors->has('mobile') ? ' autofocus' : '' }}>
                @if ($errors->has('mobile'))
                    <div class="alert alert-warning">{{ $errors->first('mobile') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="address">Address:</label>
                <div class="col-sm-10">
                    <textarea id="address" name="address" class="form-control"{{ $errors->has('address') ? ' autofocus' : '' }} required>{{ old('address', $user->address) }}</textarea>
                @if ($errors->has('address'))
                    <div class="alert alert-warning">{{ $errors->first('address') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="town">Town:</label>
                <div class="col-sm-10">
                    <input type="text" id="town" name="town" class="form-control" value="{{ old('town', $user->town) }}"{{ $errors->has('town') ? ' autofocus' : '' }} required>
                @if ($errors->has('town'))
                    <div class="alert alert-warning">{{ $errors->first('town') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="county">County:</label>
                <div class="col-sm-10">
                    <input type="text" id="county" name="county" class="form-control" value="{{ old('county', $user->county) }}"{{ $errors->has('county') ? ' autofocus' : '' }}>
                @if ($errors->has('county'))
                    <div class="alert alert-warning">{{ $errors->first('county') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="postcode">Postcode:</label>
                <div class="col-sm-10">
                    <input type="text" id="postcode" name="postcode" class="form-control" value="{{ old('postcode', $user->postcode) }}"{{ $errors->has('postcode') ? ' autofocus' : '' }} required>
                @if ($errors->has('postcode'))
                    <div class="alert alert-warning">{{ $errors->first('postcode') }}</div>
                @endif
                </div>
            </div>

            <legend>Setup</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="adapter_posted">Adapter posted:</label>
                <div class="col-sm-10">
                    <input type="text" id="adapter_posted" name="adapter_posted" class="form-control" value="{{ old('adapter_posted', $user->adapter_posted) }}"{{ $errors->has('adapter_posted') ? ' autofocus' : '' }}>
                    @if ($errors->has('adapter_posted'))
                        <div class="alert alert-warning">{{ $errors->first('adapter_posted') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="setup_completed">Setup completed:</label>
                <div class="col-sm-10">
                    <input type="text" id="setup_completed" name="setup_completed" class="form-control" value="{{ old('setup_completed', $user->setup_completed) }}"{{ $errors->has('setup_completed') ? ' autofocus' : '' }}>
                    @if ($errors->has('setup_completed'))
                        <div class="alert alert-warning">{{ $errors->first('setup_completed') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="direct_debit">Direct Debit setup:</label>
                <div class="col-sm-10">
                    <input type="text" id="direct_debit" name="direct_debit" class="form-control" value="{{ old('direct_debit', $user->direct_debit) }}"{{ $errors->has('direct_debit') ? ' autofocus' : '' }}>
                    @if ($errors->has('direct_debit'))
                        <div class="alert alert-warning">{{ $errors->first('direct_debit') }}</div>
                    @endif
                </div>
            </div>

			<legend>Security details</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="email">Email:</label>
                <div class="col-sm-10">
                    <input type="email" id="email" name="email" class="form-control" placeholder="e.g. fred@example.com" value="{{ old('email', $user->email) }}"{{ $errors->has('email') ? ' autofocus' : '' }} required>
                @if ($errors->has('email'))
                    <div class="alert alert-warning">{{ $errors->first('email') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="password">Password:</label>
                <div class="col-sm-10">
                    <input type="password" id="password" name="password" class="form-control" {{ $errors->has('password') ? ' autofocus' : '' }}>
                @if ($errors->has('password'))
                    <div class="alert alert-warning">{{ $errors->first('password') }}</div>
                @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="password_confirmation">Confirm:</label>
                <div class="col-sm-10">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" {{ $errors->has('password_confirmation') ? ' autofocus' : '' }}>
                    <input type="checkbox" onchange="document.getElementById('password').type = this.checked ? 'text' : 'password';document.getElementById('password_confirmation').type = this.checked ? 'text' : 'password'"> Show password
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
                <option value="{{ $role->name }}"{{ in_array($role->name, old('roles', $assignedRoles)) ? ' selected' : '' }}>{{ $role->name }}</option>
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
                  <input type="text" id="last_login_at" name="last_login_at" class="form-control" value="{{ $user->last_login_at }}" readonly>
              </div>
          </div>

          <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="last_login_ip">Last Login IP:</label>
              <div class="col-sm-10">
                  <input type="text" id="last_login_ip" name="last_login_ip" class="form-control" value="{{ $user->last_login_ip }}" readonly>
              </div>
          </div>

          <legend>Confirmation</legend>

          <div class="form-group row">
              <label class="col-sm-2 col-form-label" for="admin_password">Your Password:</label>
              <div class="col-sm-10">
                  <input type="password" id="admin_password" name="admin_password" class="form-control" {{ $errors->has('admin_password') ? ' autofocus' : '' }} required>
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
            <input type="submit" id="submit" name="submit" value="Save" >
          </div>
        </div>

      </form>

      <hr>

      <h3>Linked Resources</h3>

      <div class="row">
        <div class="col-sm-6">
          <table class="table">
            <thead>
              <th scope="col">Notes</th>
              <th scope="col"><a href="/admin/notes/create/{{ $user->id }}">Add a new note</a></th>
            </thead>
            <tbody>
            @foreach($user->notes as $note)
              <tr>
                <td>{{ $note->created_at }}</td>
                <td><a href="/admin/notes/{{ $note->id }}">{{ $note->title }}</a></td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
        <div class="col-sm-3">
          <table class="table">
            <thead>
              <th scope="col">Numbers</th>
            </thead>
            <tbody>
            @foreach($user->numbers as $number)
              <tr><td><a href="/admin/numbers/{{ $number->id }}">{{ $number->number }}</a></td></tr>
            @endforeach
            </tbody>
          </table>
        </div>
        <div class="col-sm-3">
          <table class="table">
            <thead>
              <th scope="col">Routers</th>
            </thead>
            <tbody>
            @foreach($user->endpoints as $endpoint)
              <tr><td><a href="/admin/routers/{{ $endpoint->id }}">{{ $endpoint->id }}</a></td></tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>

<script type="text/javascript">
$(document).ready(function() {
    $("#roles").select2([]);
});
</script>

@endsection
