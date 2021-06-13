<nav class="navbar navbar-expand-lg navbar-light fixed-top navbar-custom">
    <a class="navbar-brand" href="/"><img src="/images/logo.png" alt="The Communication Gateway Logo"></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            @if (Auth::check())
                <li class="nav-item">
                    <a class="nav-link" href="/logs">Logs</a>
                </li>
                @role('admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        Admin
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="/admin/users">Users</a>
                        <a class="dropdown-item" href="/admin/logs">Logs</a>
                    </div>
                </li>
                @endrole
            @endif
        </ul>
        <ul class="navbar-nav">
            @if (Auth::check())
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        {{ Auth::user()->given_name }} {{ Auth::user()->family_name }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="/profile"><span class="fa fa-user" aria-hidden="true"></span>&nbsp;Profile</a>
                        <a class="dropdown-item" href="/logout"><span class="fa fa-sign-out-alt"
                                                                      aria-hidden="true"></span>&nbsp;Logout</a>
                    </div>
                </li>
            @endif
        </ul>
    </div>
</nav>
