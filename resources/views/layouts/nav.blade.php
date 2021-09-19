<nav
    class="navbar navbar-expand-lg navbar-light fixed-top @if(session()->has('scanning')) navbar-custom-scanning @else navbar-custom @endif">
    <div class="container-fluid">
        <a class="navbar-brand" href="/"><img src="/images/logo.png" alt="The Communication Gateway Logo"></a>
        @if(session()->has('scanning'))
            <strong>{{ session('scanning') }} is scanning !</strong>
        @endif
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                @if (Auth::check())
                    <li class="nav-item">
                        <a class="nav-link" href="/posts">Scan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/search">Search</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/watchwords">Watchwords</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/scans">Scans</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logs">Logs</a>
                    </li>
                    @role('admin')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Admin
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="/admin/users">Users</a></li>
                            <li><a class="dropdown-item" href="/admin/groups">Groups</a></li>
                            <li><a class="dropdown-item" href="/admin/scans">Scans</a></li>
                            <li><a class="dropdown-item" href="/admin/logs">Logs</a></li>
                        </ul>
                    </li>
                    @endrole
                @endif
            </ul>
            <ul class="d-flex">
                @if (Auth::check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                           data-bs-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="/profile"><span class="fa fa-user"
                                                                               aria-hidden="true"></span>&nbsp;Profile</a>
                            </li>
                            <li><a class="dropdown-item" href="/logout"><span class="fa fa-sign-out-alt"
                                                                              aria-hidden="true"></span>&nbsp;Logout</a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </ul>
    </div>
</nav>
