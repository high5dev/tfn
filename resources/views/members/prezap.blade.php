@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Zap Report</h3>

    <form method="post" action="/members/zap">
        @csrf()
        @method('delete')

        <fieldset>

            <legend>Member's Details</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="id">ID: </label>
                <div class="col-sm-10">
                    <input type="text" id="id" name="id" class="form-control" value="{{ $member->id }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="username">Username:</label>
                <div class="col-sm-10">
                    <input type="email" id="username" name="username" class="form-control"
                           value="{{ $member->username }}" readonly>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="email">Email:</label>
                <div class="col-sm-10">
                    <input type="text" id="email" name="email" class="form-control" value="{{ $member->email }}"
                           readonly>
                </div>
            </div>

            <legend>Posts</legend>

            <table class="table table-striped">
                @if(count($member->posts))
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Subject</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    @foreach($member->posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>{{ $post->type }}</td>
                            <td>{{ $post->subject }}</td>
                            <td>{{ $post->dated }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>This member has no posts in the local database</td>
                    </tr>
                @endif
            </table>

            <legend>Zap Report</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="found">Found:</label>
                <div class="col-sm-10">
                    <select id="found" name="found" class="form-control">
                        <option value="">Please select how the account was discovered</option>
                        <option value="HSGOA"{{ 'HSGOA'==old('found') ? ' selected' : '' }}>HS GOA Report</option>
                        <option value="HSMOD"{{ 'HSMOD'==old('found') ? ' selected' : '' }}>HS MOD Report</option>
                        <option value="HSMEM"{{ 'HSMEM'==old('found') ? ' selected' : '' }}>HS Member Report</option>
                        <option value="SEARCH"{{ 'SEARCH'==old('found') ? ' selected' : '' }}>App Search</option>
                        <option value="SCAN"{{ 'SCAN'==old('found') ? ' selected' : '' }}>App Scan</option>
                        <option value="WATCH"{{ 'WATCH'==old('found') ? ' selected' : '' }}>App Watchwords</option>
                        <option value="OTHER"{{ 'OTHER'==old('found') ? ' selected' : '' }}>Other</option>
                    </select>
                    @if ($errors->has('found'))
                        <div class="alert alert-warning">{{ $errors->first('found') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="justification">Justification:</label>
                <div class="col-sm-10">
                    <textarea id="justification" name="justification" class="form-control" required
                              {{ $errors->has('justification') ? ' autofocus' : '' }}>{{ old('justification') }}</textarea>
                    @if ($errors->has('justification'))
                        <div class="alert alert-warning">{{ $errors->first('justification') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="region">Region:</label>
                <div class="col-sm-10">
                    <input type="text" id="region" name="region" class="form-control" value="{{ old('region') }}"
                           {{ $errors->has('region') ? ' autofocus' : '' }} required>
                    @if ($errors->has('region'))
                        <div class="alert alert-warning">{{ $errors->first('found') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="warnings">Warnings:</label>
                <div class="col-sm-10">
                    <input type="text" id="warnings" name="warnings" class="form-control" value="{{ old('warnings') }}"
                           {{ $errors->has('warnings') ? ' autofocus' : '' }} required>
                    @if ($errors->has('warnings'))
                        <div class="alert alert-warning">{{ $errors->first('found') }}</div>
                    @endif
                </div>
            </div>

            <legend>Confirmation</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="password">Your Password:</label>
                <div class="col-sm-10">
                    <input type="password" id="password" name="password" class="form-control"
                           {{ $errors->has('password') ? ' autofocus' : '' }} required>
                    @if ($errors->has('password'))
                        <div class="alert alert-warning">{{ $errors->first('password') }}</div>
                    @endif
                </div>
            </div>

        </fieldset>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="submit">&nbsp;</label>
            <div class="col-sm-10">
                <input type="submit" id="submit" name="submit" value="Zap">
            </div>
        </div>

    </form>

    <ul>
        <li>Please fill out ALL the fields</li>
        <li>If no warnings are neccessary, put "None" in the warnings field.</li>
        <li>If you send warnings, please reference the HS ticket in the Warnings field.</li>
        <li>If you select "Other" for how the account was found, please expand in the Justification area.</li>
        <li>In the region field put the region of the post that triggered the zap.</li>
    </ul>

@endsection
