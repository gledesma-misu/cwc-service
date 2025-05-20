@extends('layouts.master')


@section('content')
    <div class="card">
        <div class="card-header bg-dark">
            @can('permissions-read')
                <h5 class="text-light">Create new Permissions</h5>
            @endcan
        </div>
        <div class="card-body">
            <form action="{{ route('permissionsStore') }}" method="POST">
                @csrf
                <permissions-create></permissions-create>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-success float-right">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
