@extends('layouts.app')

@section('extracss')

@endsection

@section('content')
<style>
  .select2-container *:focus {
      outline: none;
  }
</style>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Category Translat</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('categoryTranslat') }}">Category Translat</a></li>
              <li class="breadcrumb-item active">{{!empty($categoryTranslat) ? "Update" : "Create"}} Category Translat</li>
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
              <a href="{{route('categoryTranslat')}}" class="text-muted"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h5>{{!empty($categoryTranslat) ? "Update" : "Create"}} Category Translat</h5>
            </div>
            @if(isset($categoryTranslat))
              <form id="" class="form theme-form" method="POST" action="{{ route('categoryTranslat.update', $categoryTranslat->id) }}" enctype="multipart/form-data">
            @else
              <form id="translationForm" class="user-form form theme-form" method="POST" action="{{ route('categoryTranslat.store') }}" enctype="multipart/form-data">
            @endif
            @csrf
                <div class="form-row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body row ">

                        <div id="myModal" class="form-group col-md-12">
                          <label for="translation">Select Category</label>
                          
                            <select name="category" id="category" class="form-control select-category">
                                <option value="{{@$categoryTranslat->category_id}}" >Select Category</option>
                                @foreach (get_category() as $cate)
                                  @if(@$categoryTranslat->category_id != null && @$categoryTranslat->category_id == $cate->id)
                                  <option value="{{$cate->id}}" selected>{{$cate->name}}</option>
                                  @else
                                  <option value="{{$cate->id}}">{{$cate->name}}</option>
                                  @endif
                                @endforeach
                            </select>

                          @error('category')
                            <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>

                        <div class="form-group col-md-12">
                          <label for="translation">Select Language</label>
                            <select name="language" id="language" class="form-control select-language" >
                                <option value="{{@$categoryTranslat->language_id}}" >Select Language</option>
                                @foreach (get_languages() as $language)
                                  @if(@$categoryTranslat->language_id != null &&  @$categoryTranslat->language_id == $language->id)
                                  <option value="{{$language->id}}" selected>{{$language->name}}({{$language->name_in_language}})</option>
                                  @else
                                  <option value="{{$language->id}}">{{$language->name}}({{$language->name_in_language}})</option>
                                  @endif
                                @endforeach
                            </select>

                          @error('language')
                            <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>

                        <div class="form-group col-md-12">
                          <label for="translation">Translat In Language</label>
                          <input type="text" class="form-control" id="translation" name="translation" value="{{!empty($categoryTranslat->translation) ? $categoryTranslat->translation: ''}}" placeholder="Content Category Translat" />

                          @error('translation')
                            <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>

                      </div>
                    </div>
                    <div class="card">
                      <div class="card-body text-right">
                          <button class="btn btn-danger mr-2" type="submit"> {{ isset($categoryTranslat) ? 'Update' : 'Submit' }} </button>
                          <a href="{{ route('categoryTranslat') }}" class="btn btn-default">Cancel</a>
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

<link rel="stylesheet" href="{{asset('css/select2.min.css')}}"/>
<script src="{{asset('js/select2.min.js')}}"></script>

<script>
  $(document).ready(function() {
      
      $('.select-category').select2({
          dropdownParent: $('#myModal')
      });
      $('.select-language').select2();
    $("#translationForm").validate({
      rules: {
        category: {
            required: true
        },
        language: {
            required: true
        },
        translation: {
            required: true
        }
      },
      messages: {

      },
    })
  });

</script>
@endsection
