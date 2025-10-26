<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Polymorphic Relationship</title>
    <link rel="stylesheet" href="{{ asset('asset/bootstrap.min.css') }}">

</head>
<body>
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">PolyBlog</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Dark offcanvas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li>
                    </ul>
                    <form class="d-flex mt-3" role="search">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            Launch demo modal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Content</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Toggle buttons -->
                    <div class="mb-3 text-center">
                        <button id="formTextBtn" class="btn btn-outline-primary active">Text Form</button>
                        <button id="formImageBtn" class="btn btn-outline-secondary">Image Form</button>
                    </div>

                    <!-- Form 1: Title + Textarea -->
                    <form id="textForm">
                        <div class="mb-3">
                            <label for="textTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="textTitle" placeholder="Enter title">
                        </div>
                        <div class="mb-3">
                            <label for="textContent" class="form-label">Content</label>
                            <textarea class="form-control" id="textContent" rows="4" placeholder="Enter text..."></textarea>
                        </div>
                    </form>

                    <!-- Form 2: Title + Image Upload + Preview -->
                    <form id="imageForm" class="d-none">
                        <div class="mb-3">
                            <label for="imageTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" id="imageTitle" placeholder="Enter title">
                        </div>
                        <div class="mb-3">
                            <label for="imageUpload" class="form-label">Upload Image</label>
                            <input class="form-control" type="file" id="imageUpload" accept="image/*">
                        </div>
                        <div class="text-center">
                            <img id="imagePreview" src="" alt="Preview" class="img-fluid rounded d-none" style="max-height: 250px;">
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="form-submit">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        @yield('content')
    </div>

    <script src="{{ asset('asset/jquery.min.js') }}"></script>
    <script src="{{ asset('asset/bootstrap.bundle.min.js') }}"></script>
    <script>
        const formTextBtn = document.getElementById('formTextBtn');
        const formImageBtn = document.getElementById('formImageBtn');
        const textForm = document.getElementById('textForm');
        const imageForm = document.getElementById('imageForm');
        const imageUpload = document.getElementById('imageUpload');
        const imagePreview = document.getElementById('imagePreview');

        // Toggle between forms
        formTextBtn.addEventListener('click', () => {
            textForm.classList.remove('d-none');
            imageForm.classList.add('d-none');
            formTextBtn.classList.add('active');
            formImageBtn.classList.remove('active');
        });

        formImageBtn.addEventListener('click', () => {
            imageForm.classList.remove('d-none');
            textForm.classList.add('d-none');
            formImageBtn.classList.add('active');
            formTextBtn.classList.remove('active');
        });

        // Image preview functionality
        imageUpload.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
            const reader = new FileReader();
                reader.onload = (e) => {
                    imagePreview.src = e.target.result;
                    imagePreview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '';
                imagePreview.classList.add('d-none');
            }
        });

        $(".btn-primary").click(function (e) {
            e.preventDefault();

            if ($("#formTextBtn").hasClass("active")) {

                var title = $("#textTitle").val();
                var content = $("#textContent").val();

                $.ajax({
                    url: '/your-endpoint-for-text',
                    method: 'POST',
                    data: {
                        title: title,
                        content: content
                    },
                    success: function(response) {
                        console.log(response);

                        $('#exampleModal').modal('hide');
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });

            } else if ($("#formImageBtn").hasClass("active")) {

                var imageTitle = $("#imageTitle").val();
                var imageData = $("#imageUpload")[0].files[0];
                
                var formData = new FormData();
                formData.append("title", imageTitle);
                formData.append("image", imageData);

                $.ajax({
                    url: '/your-endpoint-for-image',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log(response);

                        $('#exampleModal').modal('hide');
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }
        })
    </script>
</body>
</html>
