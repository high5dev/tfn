@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>List Groups</h3>

    <a href="/admin/groups/create">Create a new group</a>

    <table id="groupsTable" class="table table-striped table-max-width">
        <thead class="table-light">
        <tr>
            <th>Name</th>
            <th>Region</th>
            <th>Country</th>
            <th>Actions</th>
        </tr>
        </thead>
    </table>

    <!-- Script -->
    <script type="text/javascript">
        $(document).ready(function(){

            // DataTable
            $('#groupsTable').DataTable({
                "autoWidth": false,
                "columns": [
                    { "width": "25%" },
                    { "width": "25%" },
                    { "width": "25%" },
                    { "width": "25%" }
                ],
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
