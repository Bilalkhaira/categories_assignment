@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row" style="padding: 20px 0px;">
        <div class="pull-left col-lg-6">
            <h2>Categories</h2>
        </div>
        <div class="pull-right mb-2 col-lg-6">
            <a style="float: right;" class="btn btn-success" onClick="add()" href="javascript:void(0)"> Create Category</a>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Category Name</th>
                    <th>Created at</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <th scope="row">{{ $category->id }}</th>
                    <td>{{$category->category_name}}</td>
                    <td>{{ $category->created_at}}</td>
                    <td>
                        <a href="javascript:void(0)" data-toggle="tooltip" data-id="{{ $category->id }}" data-original-title="Edit" class="edit editPost"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0)" data-toggle="tooltip" data-id="{{ $category->id }}" data-original-title="Delete" class="delete deletePost"><i class="fa fa-trash-o"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

<div class="container mt-2">
    <div class="row" style="padding: 20px 0px;">
        <div class="pull-left col-lg-6">
            <h2>Categories View</h2>
        </div>
    </div>

    <div class="card-body">
        <ul style="list-style-type:none;">
            @foreach($parentCategories as $category)
            <li value="{{ $category->id }}">
                <input type="checkbox">
                {{ $category->category_name }}
            </li>
            @if ($category->children)
            @foreach ($category->children as $child)
            <li value="{{ $child->id }}" style="padding-left: 20px">
                <input type="checkbox">
                {{ $child->category_name }}
            </li>
            @endforeach
            @endif
            @endforeach
        </ul>
    </div>
</div>
<!-- boostrap model -->
<div class="modal fade" id="category-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="categoryModal"></h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" id="categoryForm" name="categoryForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Category Name" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Parent Category</label>
                        <div class="col-sm-12">
                            <select class="form-control" name="parent_category" id="parent_category">
                                <option value="">None</option>
                                @foreach($parentCategories as $category)
                                <option value="{{ $category->id}}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="btn-save">Save changes
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<!-- end bootstrap model -->
<!-- </body> -->
<script type="text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    });


    function add() {
        $('#categoryForm').trigger("reset");
        $('#categoryModal').html("Add Category");
        $('#category-modal').modal('show');
        $('#id').val('');
    }

    $('body').on('click', '.edit', function() {
        var id = $(this).data('id');
        $.ajax({
            type: "GET",
            url: "{{ route('categories.index') }}" + '/' + id + '/edit',
            dataType: 'json',
            success: function(res) {
                $('#categoryModal').html("Edit Category");
                $('#category-modal').modal('show');
                $('#id').val(res.id);
                $('#name').val(res.category_name);
                $('#parent_category').val(res.parrent_id);
            }
        });
    })

    $('body').on('click', '.delete', function() {
        if (confirm("Delete Record?") == true) {
            var id = $(this).data('id');
            $.ajax({
                type: "DELETE",
                url: "{{ route('categories.index') }}" + '/' + id,
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(res) {
                    location.reload();
                }
            });
        }
    })
    $('#categoryForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: 'POST',
            url: "{{ route('categories.store')}}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (data) => {
                $("#category-modal").modal('hide');
                location.reload();
            },
            error: function(data) {
                console.log(data);
            }
        });
    });
</script>
@endsection