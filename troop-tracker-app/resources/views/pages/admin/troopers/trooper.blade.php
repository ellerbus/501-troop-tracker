@extends('layouts.base')

@section('content')

<x-transmission-bar :id="'trooper'" />

@include('pages.admin.troopers.profile',['trooper'=>$trooper])

@endsection