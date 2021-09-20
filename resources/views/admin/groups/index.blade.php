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
                autoWidth: false,
                fixedHeader: {
                    header: true,
                    footer: true
                },
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{route('admin.getGroups')}}",
                columns: [
                    { width: "auto", data: 'name' },
                    { width: "auto", data: 'region' },
                    { width: "auto", data: 'country' },
                    { width: "auto", data: null,
                        render: function ( data, type, row, meta ) {
                            return '<a href="/admin/groups/'+data['id']+'">View Detail</a>'; }
                    },
                ]
            });

        });
    </script>

@endsection
