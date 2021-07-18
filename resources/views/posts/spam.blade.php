@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Scan Potential Spam Posts</h3>

    {{ $posts->links() }}

    <form method="post" action="/posts/notspam">
        @csrf()

        <table class="table table-striped">
            <thead class="thead-light">
            <tr>
                <th scope="col">Post ID</th>
                <th scope="col">Dated</th>
                <th scope="col">Subject</th>
                <th scope="col">Type</th>
                <th scope="col">User ID</th>
                <th scope="col">Email</th>
                <th scope="col">Flags</th>
                <th scope="col">Unmark</th>
            </tr>
            </thead>
            <tbody>
            @if(count($posts))
                @foreach($posts as $post)
                    <tr>
                        <td>
                            <a href="{{ $sturl }}/view_post?post_id={{ $post->id }}" target="_blank">{{ $post->id }}</a>
                        </td>
                        <td>
                            {{ $post->dated }}
                        </td>
                        <td>
                            {{ $post->subject }}
                        </td>
                        <td>
                            {{ $post->type }}
                        </td>
                        <td>
                            <a href="{{ $sturl }}/view_member?user_id={{ $post->userid }}"
                               target="_blank">{{ $post->userid }}</a>
                        </td>
                        <td>
                            {{ $post->email }}
                        </td>
                        <td>
                            @if($post->status == 'Active')
                                A
                            @else
                                P
                            @endif
                            @if($post->usernew)
                                N
                            @endif
                        </td>
                        <td>
                            <input type="checkbox" name="notspam[]" value="{{ $post->id }}">
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8">No results for your search criteria</td>
                </tr>
            @endif
            </tbody>
        </table>

        <input type="submit" value="Unmark Spam">

    </form>

    {{ $posts->links() }}

    <div class="jumbotron">
        Posts marked as potential spam will appear in this list. Please check them and, if they are not spam/scam
        then please tick the checkbox and click the "Unmark Spam" button to unmark them as potential spam.
    </div>

@endsection
