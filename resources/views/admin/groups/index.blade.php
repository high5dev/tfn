@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>List Groups</h3>

    <a href="/admin/groups/create">Create a new group</a>

    <table class="table table-striped">
        <thead class="thead-light">
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Region</th>
            <th scope="col">Country</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($groups as $group)
            <tr>
                <td>
                    {{ $group->name }}
                </td>
                <td>
                    {{ $group->region }}
                </td>
                <td>
                    {{ $group->country }}
                </td>
                <td>
                    <div class="row">
                        <div class="span6">
                            <form method="get" action="/admin/groups/{{ $group->id }}">
                                @csrf
                                <button class='btn btn-default' type="submit" alt="Edit">
                                    <span class="fa fa-edit" aria-hidden="true"></span>
                                </button>
                            </form>
                        </div>
                        <div class="span6">
                            <form method="post" action="/admin/groups/{{ $group->id }}">
                                @csrf
                                @method("DELETE")
                                <button class='btn btn-default' type="submit" alt="Delete"
                                        onclick="return okCancel('Are you sure you want to delete this group?')">
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
