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
    $.ajax({
        url: "{{ route('get.blogs') }}",
        type: "POST",
        dataType: "json",
        success: function(blogs) {
            let container = $('#blog-container');
            container.empty();

            $.each(blogs, function(index, blog) {
                let card = `
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            ${blog.type === 'image'
                                ? `<img src="{{ asset('storage/${blog.image_path}') }}" class="card-img-top" alt="${blog.title}" style="height:200px;object-fit:cover;">`
                                : ''
                            }
                            <div class="card-body">
                                <h5 class="card-title">${blog.title}</h5>
                                ${
                                    blog.type === 'text'
                                    ? `<p class="card-text">${blog.content.substring(0, 100)}...</p>`
                                    : ''
                                }
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
});
</script>
@endpush
