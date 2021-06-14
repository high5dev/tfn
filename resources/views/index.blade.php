@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">The Secret Portal</div>

                    <div class="card-body">
                        In order to continue, please <a href="/login">click here to login</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
