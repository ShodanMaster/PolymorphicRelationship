@extends('master')

@section('content')
<div class="container mt-10">
    <div class="row" id="blog-container">
        <!-- Blog cards will appear here -->
    </div>
</div>
@endsection

@push('custom-script')
<script>
$(document).ready(function() {
    function loadBlogs() {
        $.ajax({
            url: "{{ route('get.blogs') }}",
            type: "GET",
            dataType: "json",
            success: function(blogs) {
                let container = $('#blog-container');
                container.empty();

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
                                    <button class="btn btn-danger btn-sm mt-2 delete-blog" data-id="${blog.id}" data-type="${blog.type}">Delete</button>
                                </div>
                            </div>
                        </div>
                    `;
                    container.append(card);
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching blogs:", error);
            }
        });
    }

    loadBlogs();

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
});

</script>
@endpush
