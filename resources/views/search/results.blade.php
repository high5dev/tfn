@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Results</h3>
    <h4>Search on {{ $search }}</h4>

    {{ $posts->links() }}

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
            <th scope="col">Actions</th>
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
                        <form method="post" action="/post/{{ $post->id }}"
                              class="user-delete-btn"
                                  onsubmit="return confirm('Are you sure you want to remove this post?');">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-sm btn-outline-secondary" type="submit">
                                <i class="fas fa-trash-alt" title="Remove this post"></i></button>
                        </form>
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

    {{ $posts->links() }}

@endsection
