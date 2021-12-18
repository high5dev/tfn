@extends('layouts.master')

@section('content')

    @include('layouts.flash_message')

    <h3>Results</h3>
    <h4>Search on {{ $search }}</h4>

    {{ $members->links() }}

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
        @if(count($members))
            @foreach($members as $member)
                <tr>
                    <td colspan="8">
                        Posts for: <a href="{{ $sturl }}{{ $stmember }}{{ $member->email }}"
                                      target="_blank">{{ $member->username }} &lt;{{ $member->email }}&gt;</a>
                    </td>
                </tr>
                @if(count($member->posts))
                    @foreach($member->posts as $post)
                        <tr>
                            <td>
                                <a href="{{ $sturl }}/view_post?post_id={{ $post->id }}"
                                   target="_blank">{{ $post->id }}</a>
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
                                <div class="row">
                                    <div class="span4">
                                        <a href="{{ $imgurl . $post->id }}" target="_blank">
                                            <button class="btn btn-sm btn-outline-secondary" type="submit">
                                                <i class="far fa-image" title="View image"></i>
                                            </button>
                                        </a>
                                    </div>
                                    <div class="span4">
                                        <form method="post" action="/posts/{{ $post->id }}"
                                              class="user-delete-btn"
                                              onsubmit="return confirm('Are you sure you want to remove this post?');">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-sm btn-outline-secondary" type="submit">
                                                <i class="fas fa-trash-alt" title="Remove this post"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8">No posts found</td>
                    </tr>
                @endif
            @endforeach
        @else
            <tr>
                <td colspan="8">No members found with specified email address</td>
            </tr>
        @endif
        </tbody>
    </table>

    {{ $members->links() }}

    <ul>
        <li>
            In the Flags columns:
            <ul>
                <li>A - Post is Active</li>
                <li>P - Post is in pending</li>
                <li>N - User has recently joined</li>
            </ul>
        </li>
    </ul>

@endsection
