@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>View a zap report</h3>

    <form method="post" action="/reports">
        @csrf()
        @method('PATCH')

        <fieldset>

            <legend>Header</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="user">Zapped by :</label>
                <div class="col-sm-10">
                    <input type="text" id="user" name="user" class="form-control" readonly
                           value="{{ $report->user->name }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="member_id">Member ID :</label>
                <div class="col-sm-10">
                    <input type="text" id="started" name="member_id" class="form-control" readonly
                           value="{{ $report->member_id }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="created_at">Zapped :</label>
                <div class="col-sm-10">
                    <input type="text" id="created_at" name="created_at" class="form-control" readonly
                           value="{{ $report->created_at }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="updated_at">Updated :</label>
                <div class="col-sm-10">
                    <input type="text" id="updated_at" name="updated_at" class="form-control" readonly
                           value="{{ $report->updated_at }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="warning_emails">Warning Emails:</label>
                <div class="col-sm-10">
                    <textarea id="warning_emails" name="warning_emails" class="form-control"
                              readonly>@foreach(json_decode($report->warning_emails) as $email){{ $email }}
                        @endforeach
                    </textarea>
                </div>
            </div>

            <legend>Details</legend>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="title">Title:</label>
                <div class="col-sm-10">
                    <textarea id="justification" name="title" class="form-control" required
                              {{ $errors->has('title') ? ' autofocus' : '' }}>{{ old('title', $report->title) }}</textarea>
                    @if ($errors->has('title'))
                        <div class="alert alert-warning">{{ $errors->first('title') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="found">Found:</label>
                <div class="col-sm-10">
                    <select id="found" name="found" class="form-control">
                        <option value="">Please select how the account was discovered</option>
                        <option value="HSGOA"{{ 'HSGOA'==old('found', $report->found) ? ' selected' : '' }}>HS GOA
                            Report
                        </option>
                        <option value="HSMOD"{{ 'HSMOD'==old('found', $report->found) ? ' selected' : '' }}>HS MOD
                            Report
                        </option>
                        <option value="HSMEM"{{ 'HSMEM'==old('found', $report->found) ? ' selected' : '' }}>HS Member
                            Report
                        </option>
                        <option value="SEARCH"{{ 'SEARCH'==old('found', $report->found) ? ' selected' : '' }}>App
                            Search
                        </option>
                        <option value="SCAN"{{ 'SCAN'==old('found', $report->found) ? ' selected' : '' }}>App Scan
                        </option>
                        <option value="WATCH"{{ 'WATCH'==old('found', $report->found) ? ' selected' : '' }}>App
                            Watchwords
                        </option>
                        <option value="OTHER"{{ 'OTHER'==old('found', $report->found) ? ' selected' : '' }}>Other
                        </option>
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
                              {{ $errors->has('justification') ? ' autofocus' : '' }}>{{ old('justification', $report->justification) }}</textarea>
                    @if ($errors->has('justification'))
                        <div class="alert alert-warning">{{ $errors->first('justification') }}</div>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="warnings">Warnings:</label>
                <div class="col-sm-10">
                    <input type="text" id="warnings" name="warnings" class="form-control" value="{{ old('warnings', $report->warnings) }}"
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

        <input type="hidden" name="id" value="{{ $report->id }}">

        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="submit">&nbsp;</label>
            <div class="col-sm-10">
                <input type="submit" id="submit" name="submit" value="Save">
            </div>
        </div>

    </form>

    <a href="/reports">Back to list</a>

@endsection
