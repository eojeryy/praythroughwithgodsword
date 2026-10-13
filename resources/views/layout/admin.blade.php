@extends('layout.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/admin-workspace.css') }}?v={{ filemtime(public_path('css/admin-workspace.css')) }}" />
@endpush
