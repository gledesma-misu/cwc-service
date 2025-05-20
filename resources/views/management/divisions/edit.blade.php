@extends('layouts.master')


@section('content')

        <h1>Update division/unit</h1>
        <form action="{{ route('divisionsUpdate', $divisions->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" value="{{ $divisions->name }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="Select">Select ferson</label>
                        <select name="select_test" id="select_test" class="form-control">
                            <option value="0">Test 1</option>
                            <option value="1">Test 2</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-success float-right">Save</button>
                    </div>
                </div>
            </div>
        </form>

@endsection
