@extends('user.layout')
@section('title','Upgrade to a new package')
@section('content')
<x-subscribe :user="request()->user" message="Upgrade to a new package" />
@endsection