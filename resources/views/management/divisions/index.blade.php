@extends('layouts.master')


@section('content')
<Divisions />
    {{-- <div class="row">
        <div class="col-md-6">
            <h1>Divisions/Units</h1>
        </div>
        <div class="col-md-6">
            <a href="{{ route('divisionsCreate') }}" class="btn btn-dark mt-2 float-right">
                Create Division
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($divisions as $division)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $division->name }}</td>
                                <td>
                                    <a href="{{ route('divisionsEdit', $division->id) }}" class="btn btn-success">
                                        <i class="fa fa-edit"></i>
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
            </div>
        </div>
    </div> --}}

@endsection
