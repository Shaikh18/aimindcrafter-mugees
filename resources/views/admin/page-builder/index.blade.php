@extends('layouts.app')

@section('page-header')
    <!-- PAGE HEADER-->
    <div class="page-header mt-5-7">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('Page Builder') }}</h4>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                            class="fa-solid fa-chart-tree-map mr-2 fs-12"></i>{{ __('Admin') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#"> {{ __('Page Builder') }}</a></li>
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
                    <div class="row">
                        <div class="col-md-12 text-right mb-5">
                            <a href="{{route('admin.page-builder.create')}}" class="btn btn-success">Add Page
                                Builder</a>
                        </div>
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Page Title</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($page_builders) > 0)
                                    @foreach($page_builders as $key => $page_builder)
                                        <tr>
                                            <th scope="row">{{++$key}}</th>
                                            <td>{{$page_builder->title}}</td>
                                            <td>{{$page_builder->position}}</td>
                                            <td>
                                                <a href="{{route('admin.page-builder.edit', $page_builder->id)}}">
                                                    <i class="fa fa-pencil text-info" aria-hidden="true"></i>
                                                </a>
                                                <span>|</span>
                                                <button type="button" class="border-0 bg-transparent"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#exampleModal-{{$page_builder->id}}">
                                                    <i class="fa fa-trash text-danger" aria-hidden="true"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal-{{$page_builder->id}}" tabindex="-1"
                                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete Page</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                    </div>
                                                    <form
                                                        action="{{route('admin.page-builder.destroy', $page_builder->id)}}"
                                                        method="GET">
                                                        @csrf
                                                        <div class="modal-body">
                                                            Are You sure you want to delete this page ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Yes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4" class="text-center font-weight-bold">No Record Found</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
    </script>
@endsection
