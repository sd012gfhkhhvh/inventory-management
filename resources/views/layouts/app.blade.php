<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management - @yield('title', 'Home')</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <style>
        .pagination {
            justify-content: center;
        }
        .page-item.active .page-link {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .page-link {
            color: #4e73df;
        }
        .page-link:hover {
            color: #224abe;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('items.index') }}">Inventory Management</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('items.index') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('items.create') }}">Add New Item</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reports.index') }}">Reports</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div id="content-wrapper">
        <main role="main" class="container-fluid">
            @yield('content')
        </main>
    </div>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container-fluid text-center">
            <span class="text-muted">&copy; {{ date('Y') }} Inventory Management. All rights reserved.</span>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @yield('scripts')
</body>
</html>