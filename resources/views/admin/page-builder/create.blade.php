@extends('layouts.app')

@section('page-header')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <!-- PAGE HEADER-->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('Page Builder') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                            class="fa-solid fa-chart-tree-map mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="{{route('admin.page-builder.index')}}"> {{ __('Page Builder') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="#"> {{ __('Add Page Builder') }}</a>
                </li>
            </ol>
        </div>
    </div>
    <!--END PAGE HEADER -->
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card overflow-hidden border-0">
                <div class="card-body">
                    <form action="{{route('admin.page-builder.store')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">Page Title <span class="text-danger font-weight-bold">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       name="title">
                                @error('title')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mt-5">
                                <label for="">Select Position <span
                                        class="text-danger font-weight-bold">*</span></label>
                                <select class="form-control @error('position') is-invalid @enderror" name="position">
                                    <option selected disabled>Open this select menu</option>
                                    <option value="header">Header</option>
                                    <option value="footer">Footer</option>
                                </select>
                                @error('position')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mt-5">
                                <label for="">Description <span class="text-danger font-weight-bold">*</span></label>
                                <textarea id="summernote" name="description"></textarea>
                                @error('description')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 mt-5">
                                <button class="btn btn-success" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#summernote').summernote({
                tabsize: 2,
                height: 500
            });
        });
    </script>
@endsection
