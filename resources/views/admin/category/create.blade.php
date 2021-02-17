@extends('layouts.app')

@section('extracss')
<style type="text/css">
    .category-image {
        height: 100px;
        width: 100px;
    }
    .edit-cat {
        display: none;
    }
</style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Category</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('category') }}">Category</a></li>
              <li class="breadcrumb-item active">{{!empty($category) ? "Update" : "Create"}} Category</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header">
              <a href="{{route('category')}}" class="text-muted"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h5>{{!empty($category) ? "Update" : "Create"}} Category</h5>
            </div>
            @if(isset($category))
              <form id="" class="form theme-form" method="POST" action="{{ route('category.update', $category->id) }}" enctype="multipart/form-data">
            @else
              <form id="categoryForm" class="user-form form theme-form" method="POST" action="{{ route('category.store') }}" enctype="multipart/form-data">
            @endif
            @csrf
                <div class="form-row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body row ">

                        <div class="form-group col-md-12">
                          <label for="category">Name</label>
                          <input type="text" class="form-control" id="name" name="name" value="{{!empty($category->name) ? $category->name: ''}}" placeholder="Enter Name" />

                          @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>
                        <div class="form-group col-md-12">
                          <label for="category">Type</label>
                          <div class="form-check">
                            <input type="radio" class="form-check-input" id="type" name="type" value="1" {{@$category->type=="1" || empty(@$category->type) ? 'checked': ''}}/>
                            <span for="type">Expense</span>
                          </div>
                          <div class="form-check">
                            <input type="radio" class="form-check-input" id="type" name="type" value="0" {{@$category->type=="0" ? 'checked': ''}}/>
                            <span for="type">Income</span>
                          </div>

                          @error('type')
                            <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>
                        <div class="form-group col-md-12">
                          <label for="category">Icon</label>
                          <input type="file" class="" id="icon" name="icon" onchange="preview_image(event)"/>

                          @error('icon')
                            <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                          <div class="mini-view">
                              
                               @if(@$category->id && $category->icon != "")
                                  <img src="{{asset($category->icon)}}" class="category-image" alt="Category Icon" id="category_image">
                               @else 
                                  <img class="category-image edit-cat" alt="Category Icon" id="category_image">
                               @endif
                          </div>
                        </div>
                        <div class="form-group col-md-12">
                          <div class="icheck-danger d-inline">
                            <input type="checkbox" id="status" name="status" value="1" {{ isset($category) ? (($category->status == 1) ? 'checked' : '') : '' }}>
                              <label for="status">
                              Status
                            </label>
                          </div>
                        </div>
                      </div>
                      
                    </div>
                    <div class="card">
                      <div class="card-body text-right">
                          <button class="btn btn-danger mr-2" type="submit"> {{ isset($category) ? 'Update' : 'Submit' }} </button>
                          <a href="{{ route('category') }}" class="btn btn-default">Cancel</a>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
          </div>
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

@endsection

@section('extrajs')
<script src="{{ asset('backend/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<script>
  $(document).ready(function() {
    $('#categoryForm').validate({
      errorPlacement: function(error, element) {
          error.insertAfter(element)
          error.addClass('text-danger');
      },
      rules:{
        name:{
          required:true,
          remote: {
              type: 'post',
              url: "{{ route('category.existcategory') }}",
              data: {
                name: function() {
                  return  $("input[id='name']").val();
                },
                id: "{{ isset($category) ? $category->id : '' }}",
                "_token": "{{ csrf_token() }}"
              },
            async: false
          }
        },
      }
    });
  });
    function preview_image(event) 
    {
        var reader = new FileReader();
        reader.onload = function()
        {
            var output = document.getElementById('category_image');
            output.src = reader.result;
            output.style.display = "block";
        }
        reader.readAsDataURL(event.target.files[0]);
    }

</script>
@endsection
