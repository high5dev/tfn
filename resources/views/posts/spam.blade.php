@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Scan Potential Spam Posts</h3>

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
                        <a href="{{ $sturl }}/view_member?user_id={{ $post->member_id }}"
                           target="_blank">{{ $post->member_id }}</a>
                    </td>
                    <td>
                        @if(isset($post->member->email))
                            {{ $post->member->email }}
                        @endif
                    </td>
                    <td>
                        @if($post->status == 'Active')
                            A
                        @else
                            P
                        @endif
                        @if($post->member->joined_recently)
                            N
                        @endif
                    </td>
                    <td>
                        <div class="row">
                            <div class="span4">
                                <a href="{{ $imgurl . $post->id }}" target="_blank">
                                    <button class="btn btn-sm btn-outline-secondary" type="submit">
                                        <i class="far fa-image" title="View image"></i>
                                    </button>
                                </a>
                            </div>
                            <div class="span4">
                                <form method="post" action="/posts/notspam/{{ $post->id }}" class="form-inline">
                                    @csrf()
                                    <button class="btn btn-sm btn-outline-secondary" type="submit">
                                        <i class="fas fa-toilet-paper-slash" title="Mark as not spam"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="span4">
                                <form method="post" action="/posts/{{ $post->id }}" class="form-inline"
                                      onsubmit="return confirm('Are you sure you want to remove this post?');">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-sm btn-outline-secondary" type="submit">
                                        <i class="fas fa-trash-alt" title="Remove this post"></i>
                                    </button>
                                </form>
                            </div>
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

    <div class="jumbotron">
        <ul>
            <li>
                Posts marked as potential spam will appear in this list.
            </li>
            @if(count($posts))
                <li>
                    Please check each post listed and, if they are not spam/scam then please click on the
                    <i class="fas fa-toilet-paper-slash"></i> icon to unmark them as potential spam.
                </li>
            @endif
            <li>
                In the Flags columns:
                <ul>
                    <li>A - Post is Active</li>
                    <li>P - Post is in pending</li>
                    <li>N - User has recently joined</li>
                </ul>
            </li>
        </ul>
    </div>

@endsection
