@extends(request()->user->is_admin ?'admin.layout':'user.layout')
@section('title','Edit Profile')
@section('head')
<script src="{{ asset('js/vue.js')}}"></script>
<script src="{{ asset('js/axios.js')}}"></script>
<script src="/js/notify.js"></script>

@endsection

@php
$admin = request()->user->hasRole('super admin');
@endphp

@section('content')
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <img class="profile-userpic" src="{{request()->user->profilePic()}}" />
        </div>
        <form method="POST" action="{{url()->current()}}" enctype="multipart/form-data" id="role">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Username</label>
                        <div class="input-group">

                            <input type="text" class="form-control" disabled value="{{request()->user->login}}">

                        </div>

                    </div>

                    <div class="form-group">
                        <label>First Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                name="first_name" value="{{request()->user->first_name}}" required>
                            @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>

                    <div class="form-group">
                        <label>Last Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                name="last_name" value="{{request()->user->last_name}}" required>
                            @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-group">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{request()->user->email}}" {{!$admin ? 'required' :''}}>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                    </div>

                    @if (!request()->user->is_admin)
                    <div class="form-group">
                        <label>SMS Notification <span class="text-danger text-small">(You will be charged
                                N{{env('SMS_CHARGE',3)}} per sms
                                notification) </span></label>
                        <div class="input-group">
                            <select class="custom-select @error('sms_notify') is-invalid @enderror" name="sms_notify">
                                <option value="1" {{request()->user->sms_notify ? 'selected' :'' }}>Enabled
                                </option>
                                <option value="0" {{request()->user->sms_notify ? '': 'selected' }}>Disabled
                                </option>
                            </select>
                            @error('sms_notify')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    @can('manageRoles', App\User::class)
                    @if( request()->user->is_admin)


                    <label class="col-form-label text-sm">Roles</label>
                    @foreach (Spatie\Permission\Models\Role::all() as $role)

                    <div class="custom-control custom-switch">
                        <input :disabled="disable" type="checkbox" value="{{$role->name}}" class="custom-control-input"
                            id="role{{$role->id}}" {{request()->user->hasRole($role->id) ? 'checked':''}}
                            {{$role->name =='super admin' ? 'disabled' :''}} @click="roles(' {{$role->name}}')"
                            data-toggle="toggle">
                        <label class="custom-control-label" for="role{{$role->id}}">{{$role->name}}</label>
                    </div>
                    @endforeach
                    <span class="text-danger" role="alert">
                        <strong>Please reload page after role change to update permissions</strong>
                    </span>



                    <label class="col-form-label text-sm">Permissions</label>
                    @foreach (Spatie\Permission\Models\Permission::all()->chunk(8) as $permissionss)
                    <div class="row">
                        @foreach($permissionss->chunk(4) as $permissions)
                        <div class="col-lg-6">
                            <div class="form-group">
                                @foreach($permissions as $permission)
                                @php
                                $isDefault
                                =request()->user->getPermissionsViaRoles()->pluck('id')->contains($permission->id);
                                @endphp
                                <div class="custom-control custom-switch">
                                    <input :disabled="disable" type="checkbox" value="{{$permission->name}}"
                                        class="custom-control-input" id="permission{{$permission->id}}"
                                        {{request()->user->hasPermissionTo($permission->id) ? 'checked':''}}
                                        {{request()->user->hasRole('super admin') ? 'disabled' :''}}
                                        {{$isDefault ? 'disabled' :''}} @click="permissions(' {{$permission->name}}')">
                                    <label class="custom-control-label"
                                        for="permission{{$permission->id}}">{{$permission->name}} {{$isDefault ?
                                        '(Default)':''}}</label>

                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                    @endif
                    @endcan
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Mobile Number</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">+234</span>
                            </div>
                            <input type="text" class="form-control @error('number') is-invalid @enderror" name="number"
                                value="{{request()->user->formatted_number}}" {{!$admin ? 'required' :''}}>
                            @error('number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Profile Picture</label>
                        <div class="input-group">
                            <input type="file" accept="image/*" class="form-control @error('pic') is-invalid @enderror"
                                name="pic">
                            @error('number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    @can('suspend', request()->user)
                    @if( !request()->user->hasRole('super admin'))
                    <div class="form-group">
                        <label>Status</label>
                        <div class="input-group">
                            <select class="custom-select @error('is_active') is-invalid @enderror" name="is_active">
                                <option value="1" {{request()->user->is_active ? 'selected' :'' }}>Activate
                                </option>
                                <option value="0" {{request()->user->is_active ? '': 'selected' }}>Suspend
                                </option>
                            </select>
                            @error('is_active')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    @endcan

                    @if (!Auth::user()->is_admin)


                    <div class="form-group">
                        <label>Old Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('old_password') is-invalid @enderror"
                                name="old_password">
                            @error('old_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label>New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password_confirmation">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
<script>
    new Vue({
    el: '#role',
    data: function() {
    return {
       disable:false,
        
    }
  }, 
       methods:{
        roles(name){
            this.disable = true;
            axios.get('/admin/assign_role/{{request()->user->id}}?role='+name)
            .then(response => {
            console.log(response.data);
            this.disable = false;
            $.notify(response.data.success, "success");
            //location.reload();
            })
            .catch((error)=>{
            console.log(error.response.data);
            this.disable = false;
            var error = error.response.data.message ? error.response.data.message : 'An error occured';
            $.notify(error, "error");
            })
        },
        permissions(name){
        this.disable = true;
        axios.get('/admin/assign_permission/{{request()->user->id}}?permission='+name)
        .then(response => {
        console.log(response.data);
        this.disable = false;
        $.notify(response.data.success, "success");
        //location.reload();
        })
        .catch((error)=>{
        console.log(error.response.data);
        this.disable = false;
        var error = error.response.data.message ? error.response.data.message : 'An error occured';
        $.notify(error, "error");
        })
        }
        },
        watch:{
            
        },
        created(){
           
        }
});
</script>
@endsection