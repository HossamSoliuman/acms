@section('styles')
    <style>
        /* sidebar.css */
        .nav-link-custom {
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: flex-start;
            /* Align items to the top */
        }

        .nav-link-custom i {
            margin-top: 4px;
            /* Adjust the margin as needed */
            margin-right: 8px;
            /* Adjust the margin as needed */
        }
    </style>
@endsection
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky">
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link btn btn-light btn-block mb-2 nav-link-custom text-left" href="">
                    <i class="bi bi-house-door"></i> Dashboard
                </a>
                <a class="nav-link btn btn-light btn-block mb-2 nav-link-custom text-left"
                    href="{{ route('plants.index') }}">
                    <i class="bi bi-shop"></i> Plants
                </a>

                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="nav-link btn btn-danger btn-block mb-2 nav-link-custom ">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
