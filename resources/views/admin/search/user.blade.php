@extends('admin.layout')
@section('title','Search Users')
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card theme-bg bitcoin-wallet">
            <div class="card-block">
                <h5 class="text-white mb-2">Total Admins</h5>
                <h2 class="text-white mb-2 f-w-300">{{$admins->count()}}</h2>
                {{--  <span class="text-white d-block">Ratings by Market Capitalization</span> --}}
                <i class="fa fa-user f-70 text-white"></i>
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="card theme-bg bitcoin-wallet">
            <div class="card-block">
                <h5 class="text-white mb-2">Total Users</h5>
                <h2 class="text-white mb-2 f-w-300">{{$nonAdmins->count()}}</h2>
                {{--  <span class="text-white d-block">Ratings by Market Capitalization</span> --}}
                <i class="fa fa-user f-70 text-white"></i>
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="">
                    <h5>History</h5>
                </div>
                <div class="">
                    <form class="form-inline" action="{{url()->current()}}">

                        <div class="input-group input-group-sm my-1 mr-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Ref</span>
                            </div>
                            <input type="text" name="search" placeholder="Search by username, name, email"
                                value="{{$search}}" class="form-control" aria-label="Small">
                        </div>
                        <div class="input-group input-group-sm my-1 mr-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text">User Types</span>
                            </div>
                            <select class="custom-select" name="sub_type" aria-label="Small">
                                <option value="">All</option>
                                @foreach ($sub_types as $sub)
                                <option value="{{$sub}}" {{$sub == $sub_type ? 'selected': ''}}>{{ucfirst($sub)}}
                                </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="input-group input-group-sm my-1 mr-1">
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-block px-0 py-3">
                <div class="table-responsive">
                    <table class="table table-hover ">
                        <thead>
                            <th>Id</th>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>User Type</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                            <th>Created At</th>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td class="font-weight-bold">

                                    @if ($user->is_admin)
                                    {{$user->login}}
                                    @else
                                    <a href="/user/{{$user->id}}">{{$user->login}}</a>
                                    @endif
                                </td>
                                <td>{{$user->full_name}}</td>
                                <td><a
                                        class="label rounded-pill text-white theme-bg">{{ucfirst($user->userPackage())}}</a>
                                </td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->number}}</td>
                                <td>{{$user->created_at}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mx-auto">{{$pagination->appends(request()->except('page'))->links()}}
            </div>
        </div>
    </div>

</div>
@endsection