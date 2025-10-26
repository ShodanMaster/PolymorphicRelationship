@extends('master')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Comments List</h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered" id="comments-table">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Body</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<!-- Modal Structure -->
<div class="modal fade" id="commentPostModal" tabindex="-1" aria-labelledby="commentPostModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="commentPostModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('custom-script')
<script>
$(document).ready(function() {
    $('#comments-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("get.comments") }}',
            type: 'POST',
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'body', name: 'body' },
        ]
    });

    $('#commentPostModal').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const commentId = button.data('comment-id');

        const modal = $(this);
        modal.find('.modal-body').html('<p>Loading...</p>');

        $.ajax({
            url: '/comments/' + commentId + '/post',
            type: 'GET',
            success: function (data) {
                let html = `<h5>${data.type}</h5>`;

                if (data.type === 'TextBlog') {
                    html += `
                        <h4>${data.post.title}</h4>
                        <p>${data.post.content}</p>
                    `;
                } else if (data.type === 'ImageBlog') {
                    html += `
                        <h4>${data.post.title}</h4>
                        <img src="/storage/${data.post.image_path}" class="img-fluid mb-2" />
                    `;
                }

                html += `<hr><p><strong>Comment:</strong> ${data.comment.body}</p>`;

                modal.find('.modal-body').html(html);
            },
            error: function () {
                modal.find('.modal-body').html('<p class="text-danger">Error loading post details.</p>');
            }
        });
    });

});
</script>
@endpush
