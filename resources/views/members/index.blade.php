@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>List Users</h3>

    <a href="/members/create">Create a new member</a>

    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th scope="col">Username</th>
            <th scope="col">Email</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($members as $member)
            <tr>
                <td>{{ $member->username }}</td>
                <td>{{ $member->email }}</td>
                <td>
                    <div class="row">
                        <div class="span6">
                            <form method="get" action="/members/{{ $member->id }}">
                                @csrf
                                <button class='btn btn-default' type="submit" alt="Edit">
                                    <span class="fa fa-edit" title="Edit member" aria-hidden="true"></span>
                                </button>
                            </form>
                        </div>
                        <div class="span6">
                            <form method="post" action="/members/{{ $member->id }}">
                                @csrf
                                @method("DELETE")
                                <button class='btn btn-default' type="submit" alt="Delete"
                                        onclick="return okCancel('Are you sure you want to delete this member?')">
                                    <span class="fa fa-trash" title="Delete member" aria-hidden="true"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
