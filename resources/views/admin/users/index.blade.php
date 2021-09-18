@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>List Users</h3>

    <a href="/admin/users/create">Create a new user</a>

    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Last Login</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>
                    {{ $user->name }}
                </td>
                <td>
                    {{ $user->email }}
                </td>
                <td>
                    {{ $user->last_login_at }}
                </td>
                <td>
                    <div class="row">
                        <div class="span6">
                            <form method="get" action="/admin/users/{{ $user->id }}">
                                @csrf
                                <button class='btn btn-default' type="submit" alt="Edit">
                                    <span class="fa fa-edit" aria-hidden="true"></span>
                                </button>
                            </form>
                        </div>
                        <div class="span6">
                            <form method="post" action="/admin/users/{{ $user->id }}">
                                @csrf
                                @method("DELETE")
                                <button class='btn btn-default' type="submit" alt="Delete"
                                        onclick="return okCancel('Are you sure you want to delete this user?')">
                                    <span class="fa fa-trash" aria-hidden="true"></span>
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
