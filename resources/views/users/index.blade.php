@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>List Users</h3>

    <a href="/admin/users/create">Create a new user</a>

    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Adapter</th>
            <th scope="col">Setup</th>
            <th scope="col">DD</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>
                    @if(strlen($user->company))
                        {{ $user->company }}
                    @else
                        {{ $user->full_name }}
                    @endif
                </td>
                <td>
                    @if (is_null($user->adapter_posted))
                        <i class="fas fa-times"></i>
                    @else
                        <i class="fas fa-check"></i>
                    @endif
                </td>
                <td>
                    @if (is_null($user->setup_completed))
                        <i class="fas fa-times"></i>
                    @else
                        <i class="fas fa-check"></i>
                    @endif
                </td>
                <td>
                    @if (is_null($user->direct_debit))
                        <i class="fas fa-times"></i>
                    @else
                        <i class="fas fa-check"></i>
                    @endif
                </td>
                <td>
                    <div class="row">
                        <div class="span4">
                            <form method="get" action="/admin/calls/{{ $user->id }}">
                                @csrf
                                <button class='btn btn-default' type="submit" alt="Calls">
                                    <span class="fas fa-phone" aria-hidden="true"></span>
                                </button>
                            </form>
                        </div>
                        <div class="span4">
                            <form method="get" action="/admin/users/{{ $user->id }}">
                                @csrf
                                <button class='btn btn-default' type="submit" alt="Edit">
                                    <span class="fa fa-edit" aria-hidden="true"></span>
                                </button>
                            </form>
                        </div>
                        <div class="span4">
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
