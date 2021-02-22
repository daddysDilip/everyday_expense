@extends('layouts.app')

@section('extracss')

@endsection

@section('content')
<style>
    .select2-container *:focus {
        outline: none;
    }
</style>
<link rel="stylesheet" href="{{asset('backend/plugins/select2/css/select2.min.css')}}"/>
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Translation</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin')}}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('translation') }}">Translation</a></li>
              <li class="breadcrumb-item active">{{!empty($translation) ? "Update" : "Create"}} Translation</li>
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
              <a href="{{route('translation')}}" class="text-muted"><i class="fa fa-arrow-left"></i> Back</a>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h5>{{!empty($translation) ? "Update" : "Create"}} Translation</h5>
            </div>
            @if(isset($translation))
              <form id="" class="form theme-form" method="POST" action="{{ route('translation.update', $translation->id) }}" enctype="multipart/form-data">
            @else
              <form id="translationForm" class="user-form form theme-form" method="POST" action="{{ route('translation.store') }}" enctype="multipart/form-data">
            @endif
            @csrf
                <div class="form-row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body row ">

                        <div class="form-group col-md-12">
                          <label for="translation">Select Content 1</label>
                            <select name="content" id="content" class="form-control select2">
                                <option value="{{@$translation->content_id}}" >Select Content</option>
                                @foreach (get_contents() as $content)
                                @if(@$translation->string_id != null &&  @$translation->string_id == $content->id)
                                <option value="{{$content->id}}" selected>{{$content->english_string}}</option>
                                @else
                                <option value="{{$content->id}}">{{$content->english_string}}</option>
                                @endif
                                @endforeach
                            </select>

                          @error('content')
                            <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>

                        <div class="form-group col-md-12">
                          <label for="translation">Select Language</label>
                            <select name="language" id="language" class="form-control select2" >
                                <option value="{{@$translation->language_id}}" >Select Language</option>
                                @foreach (get_languages() as $language)
                                  @if(@$translation->language_id != null &&  @$translation->language_id == $language->id)
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
                          <label for="translation">Translation In Language</label>
                          <input type="text" class="form-control" id="translation" name="translation" value="{{!empty($translation->translation) ? $translation->translation: ''}}" placeholder="Content Translation" />

                          @error('translation')
                            <div class="alert alert-danger">{{ $message }}</div>
                          @enderror
                        </div>

                      </div>
                    </div>
                    <div class="card">
                      <div class="card-body text-right">
                          <button class="btn btn-danger mr-2" type="submit"> {{ isset($translation) ? 'Update' : 'Submit' }} </button>
                          <a href="{{ route('translation') }}" class="btn btn-default">Cancel</a>
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



<script src="{{ asset('backend/plugins/select2/js/select2.min.js') }}"></script>


<script>
  $(document).ready(function() {
      $('.select2').select2();

    $("#translationForm").validate({
      rules: {
        content: {
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
