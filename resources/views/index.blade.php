@extends('master')

@section('content')
<div class="container mt-10">
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal">
            Launch demo modal
        </button>
    </div>
    <div class="row" id="blog-container">
        <!-- Blog cards will appear here -->
    </div>
    <div id="loading" class="text-center my-3" style="display: none;">
        <span class="text-muted">Loading...</span>
    </div>
</div>
<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title fs-5" id="formModalLabel">Add Content</h1>
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
                <button type="button" class="btn btn-primary" id="submit-button">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Blog Modal -->
<div class="modal fade" id="blogModal" tabindex="-1" aria-labelledby="blogModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="blogModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div id="blogModalContent" class="mb-3"></div>
        <hr>

        <!-- Comments Section -->
        <h6>Comments</h6>
        <div id="commentsContainer"></div>
        <button id="loadMoreComments" class="btn btn-link">Show more comments</button>

        <hr>
        <div class="mb-3">
          <textarea id="newCommentText" class="form-control" rows="2" placeholder="Write a comment..."></textarea>
          <button id="addCommentBtn" class="btn btn-primary btn-sm mt-2">Post Comment</button>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('custom-script')
<script>
$(document).ready(function() {
    let offset = 0;
    const limit = 10;
    let loading = false;

    function loadBlogs() {
        if (loading) return;
        loading = true;

        $('#loading').show();

        $.ajax({
            url: "{{ route('get.blogs') }}",
            type: "GET",
            data: { offset: offset },
            dataType: "json",
            success: function(blogs) {
                $('#loading').hide();

                if (blogs.length === 0) return;

                let container = $('#blog-container');

                $.each(blogs, function(index, blog) {
                    let imageHtml = '';
                    if (blog.type === 'image' && blog.image_path) {
                        imageHtml = `<img src="/storage/${blog.image_path}" class="card-img-top" alt="${blog.title}" style="height:200px;object-fit:cover;">`;
                    }

                    let textHtml = '';
                    if (blog.type === 'text' && blog.content) {
                        textHtml = `<p class="card-text">${blog.content.substring(0, 100)}...</p>`;
                    }

                    let card = `
                        <div class="col-md-4 mb-4" id="blog-${blog.id}">
                            <div class="card h-100 shadow-sm">
                                ${imageHtml}
                                <div class="card-body">
                                    <h5 class="card-title">${blog.title}</h5>
                                    ${textHtml}
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-info btn-sm mt-2 view-blog"
                                                data-id="${blog.id}"
                                                data-type="${blog.type}"
                                                data-title="${blog.title}"
                                                data-content="${blog.content || ''}"
                                                data-image="${blog.image_path || ''}">
                                            View Post
                                        </button>

                                        <button class="btn btn-danger btn-sm mt-2 delete-blog" data-id="${blog.id}" data-type="${blog.type}">Delete</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    `;
                    container.append(card);
                });

                offset += blogs.length;
                loading = false;
            },
            error: function(xhr, status, error) {
                $('#loading').hide();
                console.error("Error fetching blogs:", error);
                loading = false;
            }
        });
    }

    loadBlogs();

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            loadBlogs();
        }
    });

    $(document).on('click', '.delete-blog', function() {
        let blogId = $(this).data('id');
        let blogType = $(this).data('type');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/blogs/${blogId}`,
                    type: 'DELETE',
                    data: {
                        type: blogType
                    },
                    success: function(response) {
                        $(`#blog-${blogId}`).remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Blog has been deleted.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to delete blog.'
                        });
                    }
                });
            }
        });
    });

    let commentOffset = 0;
    const commentLimit = 10;
    let currentBlogId = null;
    let currentBlogType = null;

    $(document).on('click', '.view-blog', function() {
        currentBlogId = $(this).data('id');
        currentBlogType = $(this).data('type');
        commentOffset = 0;
        $('#commentsContainer').empty();
        $('#newCommentText').val('');
        $('#loadMoreComments').show();

        const title = $(this).data('title');
        const type = $(this).data('type');
        const content = $(this).data('content');
        const image = $(this).data('image');

        $('#blogModalLabel').text(title);

        if (type === 'image') {
            $('#blogModalContent').html(`<img src="/storage/${image}" class="img-fluid" alt="${title}">`);
        } else {
            $('#blogModalContent').html(`<p>${content}</p>`);
        }

        // Load first 10 comments
        loadComments();

        $('#blogModal').modal('show');
    });

    function loadComments() {
        $.ajax({
            url: `/blogs/${currentBlogId}/comments`,
            type: 'GET',
            data: { offset: commentOffset, limit: commentLimit, type: currentBlogType },
            dataType: 'json',
            success: function(comments) {

                if (comments.length === 0 && commentOffset === 0) {
                    $('#commentsContainer').html('<p class="text-muted">No comments yet.</p>');
                    $('#loadMoreComments').hide();
                    return;
                }

                comments.forEach(comment => {
                    $('#commentsContainer').append(`
                        <div class="border rounded p-2 mb-2 bg-light">
                            <span>${comment}</span>
                        </div>
                    `);
                });

                commentOffset += comments.length;

                if (comments.length < commentLimit) {
                    $('#loadMoreComments').hide();
                } else {
                    $('#loadMoreComments').show();
                }
            },
            error: function(err) {
                console.error(err);
            }
        });
    }


    $('#loadMoreComments').click(function() {
        loadComments();
    });

    $('#addCommentBtn').click(function() {
        let text = $('#newCommentText').val().trim();
        if (!text) return;

        $.ajax({
            url: `/blogs/${currentBlogId}/comments`,
            type: 'POST',
            data: {
                text: text,
                type: currentBlogType
            },
            success: function(comment) {
                $('#commentsContainer').prepend(`
                    <div class="border rounded p-2 mb-2 bg-light">
                        <span>${comment.body}</span>
                    </div>
                `);
                $('#newCommentText').val('');
            },
            error: function(err) {
                console.error(err);
            }
        });
    });



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

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#submit-button").click(function (e) {
        e.preventDefault();
        console.log('clicked');

        if ($("#formTextBtn").hasClass("active")) {
            console.log("Text Clicked!");

            var title = $("#textTitle").val();
            var content = $("#textContent").val();

            if (!title || !content) {
                Swal.fire(
                    'Error!',
                    'Please fill in all fields.',
                    'error'
                );
                return;
            }

            $.ajax({
                url: '{{ route("text-blogs.store") }}',
                method: 'POST',
                data: {
                    title: title,
                    content: content
                },
                success: function(response) {
                    console.log(response);

                    $('#formModal').modal('hide');

                    // Clear form fields
                    $("#textTitle").val('');
                    $("#textContent").val('');

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Your text blog has been saved successfully.',
                        showConfirmButton: false,
                        timer: 2000
                    });
                },
                error: function(error) {
                    console.error(error);

                    Swal.fire(
                        'Error!',
                        'Something went wrong while saving your blog.',
                        'error'
                    );
                }
            });

        } else if ($("#formImageBtn").hasClass("active")) {

            var imageTitle = $("#imageTitle").val();
            var imageData = $("#imageUpload")[0].files[0];

            if (!imageTitle || !imageData) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing Data!',
                    text: 'Please provide both a title and an image.',
                });
                return;
            }

            var formData = new FormData();
            formData.append("title", imageTitle);
            formData.append("image", imageData);

            $.ajax({
                url: '{{ route("image-blogs.store") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log(response);

                    $('#formModal').modal('hide');

                    $("#imageTitle").val('');
                    $("#imageUpload").val('');

                    imagePreview.src = '';
                    imagePreview.classList.add('d-none');

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Your image has been uploaded successfully.',
                        showConfirmButton: false,
                        timer: 2000
                    });

                },
                error: function(error) {
                    console.error(error);

                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong while uploading your image.',
                    });

                    imagePreview.src = '';
                    imagePreview.classList.add('d-none');
                }
            });

        }

        loadBlogs();
    })
});

</script>
@endpush
