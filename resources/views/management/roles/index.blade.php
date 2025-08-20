@extends('layouts.master')


@section('content')
    <div class="card">
        <div class="card-header bg-dark">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-light">Roles / Type of Accounts</h5>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('rolesCreate') }}" class="btn btn-success mt-2 float-right">
                        Create New
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row my-2">
                <div class="col-md-12">
                    <form action="{{ route('rolesSearch') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="search_type">Search Type</label>
                                    <select name="search_type" class="form-control">
                                        <option value="name" selected>Name</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="search_value">Search Value</label>
                                    <input type="text" name="search_value" class="form-control"
                                        value="{{ isset($search_value) ? $search_value : old('search_value') }}">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-center">
                                <button type="submit" class="btn btn-success mt-3 mx-1">
                                    <i class="fa fa-search"></i>
                                </button>
                                @if (Request::is('roles/search'))
                                    <a href="{{ route('rolesIndex') }}" class="btn btn-danger mt-3 mx-1">
                                        <i class="fa fa-times"></i></a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Display Name</th>
                                    <th>Description</th>
                                    @canany(['roles-update', 'roles-delete'])
                                        <th>Action</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->display_name }}</td>
                                        <td>{{ $role->description }}</td>
                                        @canany(['roles-update', 'roles-delete'])
                                            <td>
                                                @can('roles-update')
                                                    <div class="float-left mx-1">
                                                        <a href="{{ route('rolesEdit', $role->id) }}" class="btn btn-success mr-2">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    </div>
                                                @endcan
                                                @can('roles-delete')
                                                    <div class="float-left mx-1">
                                                        @include('management.roles.delete')
                                                        {{-- <form action="{{ route('rolesDelete', $role->id) }}" method="POST">
                                                            @csrf
                                                            <button class="btn btn-danger"> <i class="fa fa-trash"></i></button>
                                                        </form> --}}
                                                    </div>
                                                @endcan

                                            </td>
                                        @endcanany
                                    </tr>
                                @endforeach
                            </tbody>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    {!! $roles->links('pagination::bootstrap-5') !!}
                </div>
            </div>
        </div>
    </div>
@endsection
