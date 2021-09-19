@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>List Groups</h3>

    <a href="/admin/groups/create">Create a new group</a>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="d-flex justify-content-between pagination-responsive">
                {{ $groups->appends(compact('rows'))->links() }}
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12"></div>
        <div class="col-lg-3 col-md-3 col-sm-6">
            <form class="form-inline" method="get" action="/admin/users">
                <div class="form-group">
                    <label for="rows">Rows:&nbsp;</label>
                    <select class="form-control" id="rows" name="rows">
                        <option value="5"
                                @if($rows == 5) selected @endif >5
                        </option>
                        <option value="10"
                                @if($rows == 10) selected @endif >10
                        </option>
                        <option value="25"
                                @if($rows == 25) selected @endif >25
                        </option>
                        <option value="50"
                                @if($rows == 50) selected @endif >50
                        </option>
                        <option value="100"
                                @if($rows == 100) selected @endif >100
                        </option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <table id="groupsTable" class="table table-striped">
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

    <!-- Script -->
    <script type="text/javascript">
        $(document).ready(function(){

            // DataTable
            $('#groupsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route('admin.getGroups')}}",
                columns: [
                    { data: 'name' },
                    { data: 'region' },
                    { data: 'country' },
                ]
            });

        });
    </script>

@endsection
