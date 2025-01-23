<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Master Layout')</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
        }

        header nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        header nav ul li {
            display: inline;
            margin-right: 20px;
        }

        header nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        header nav ul li a:hover {
            text-decoration: underline;
        }

        aside {
            width: 250px;
            height: 100vh;
            background-color: #f4f4f4;
            padding: 20px;
            position: fixed;
            left: 0;
            top: 0;
        }

        aside ul {
            list-style: none;
            padding: 0;
        }

        aside ul li {
            margin-bottom: 15px;
        }

        aside ul li a {
            color: #333;
            text-decoration: none;
            font-size: 16px;
        }

        aside ul li a:hover {
            color: #007bff;
        }

        main {
            padding: 20px;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        .nav_sec {
            display: flex;
            justify-content: end;
            align-items: center;
        }

        @media (max-width: 768px) {
            aside {
                width: 100%;
                height: auto;
                position: relative;
            }

            main {
                margin-left: 0;
            }

            header nav ul li {
                display: block;
                margin-bottom: 10px;
            }

            header nav ul li a {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    @auth
    <header>
        <nav>
            <ul class="nav_sec">
                <!-- <li><a href="{{ url('/home') }}">Home</a></li> -->
                <div>
                    <!-- <li><a href="{{ url('auth/register') }}">Registration</a></li> -->
                    <!-- <li><a href="{{ url('auth/login') }}">Login</a></li> -->
                    @if (auth()->user()->role === 'user')
                    <li><a href="{{ url('/data-entry') }}">Data Entry</a></li>
                    @elseif (auth()->user()->role === 'admin')
                    <li><a href="{{ url('/report') }}">Report</a></li>
                    @endif
                </div>
                <div>
                    <li>
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                    </li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </ul>
        </nav>
    </header>
    @endauth

    <main>
        @yield('content')
    </main>
</body>

</html>
