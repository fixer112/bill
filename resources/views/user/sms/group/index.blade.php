@extends('user.layout')
@section('title','SMS Groups')

@section('content')
<div id="row">
    <div id="col-12">
        @if(env('SMS_ALERT'))
        <div class="alert alert-danger rounded m-3">
            {!! env('SMS_ALERT') !!}
        </div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-12 mx-auto card p-5">
        <h4 class="text-6 mb-4">SMS Groups</h4>
        <div class="card">
            <div class="card-header">

                {{-- <div class="">
                    <form class="form-inline" action="{{url()->current()}}">

                <div class="input-group input-group-sm my-1 mr-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">From</span>
                    </div>
                    <input type="date" name="from" value="{{$from->format('Y-m-d')}}" class="form-control"
                        aria-label="Small">
                </div>
                <div class="input-group input-group-sm my-1 mr-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">To</span>
                    </div>
                    <input type="date" name="to" value="{{$to->format('Y-m-d')}}" class="form-control"
                        aria-label="Small">
                </div>

                <div class="input-group input-group-sm my-1 mr-1">
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                </div>
                </form>
            </div> --}}
        </div>
        <div class="card-block px-0 py-3">
            <div class="table-responsive">
                <table class="table table-hover ">
                    <thead>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                        @foreach ($groups as $group)
                        <tr>
                            <td>{{$group->id}}</td>
                            <td>{{$group->name}}</td>
                            <td>{{$group->created_at}}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" onclick="showNumbers('{{$group->numbers}}')">View
                                    Numbers</button>
                                <a href="/user/{{request()->user->id}}/sms/group/{{$group->id}}"><button
                                        class="btn btn-sm btn-warning">Edit</button></a>

                                <a href="/user/{{request()->user->id}}/sms/group/{{$group->id}}/delete"><button
                                        class="btn btn-sm btn-danger">Delete</button></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
@endsection