@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>List Members</h3>

    <table id="membersTable" class="table table-striped table-max-width">
        <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>IP</th>
            <th>Actions</th>
        </tr>
        </thead>
    </table>

    <!-- Script -->
    <script type="text/javascript">
        $(document).ready(function(){

            // DataTable
            $('#membersTable').DataTable({
                autoWidth: false,
                fixedHeader: {
                    header: true,
                    footer: true
                },
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{route('getMembers')}}",
                columns: [
                    { width: "auto", data: 'id' },
                    { width: "auto", data: 'username' },
                    { width: "auto", data: 'email' },
                    { width: "auto", data: 'firstip' },
                    { width: "auto", data: null,
                        render: function ( data, type, row, meta ) {
                            return '<a href="/members/'+data['id']+'">View</a>'; }
                    },
                ]
            });

        });
    </script>

@endsection
