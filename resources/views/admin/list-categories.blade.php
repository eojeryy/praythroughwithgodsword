@extends('layout.admin')
@section('title', 'Categories Library')
@section('body_class', 'admin-workspace')
@section('content')
@php
  $filterFields = [
  ['name' => 'category_name', 'label' => 'Category name'],
  ['name' => 'description', 'label' => 'Description'],
];
@endphp
@include('admin.partials.library', ['records' => $categories, 'type' => 'category', 'plural' => 'categories', 'total' => $totalCategories, 'filterFields' => $filterFields])
@endsection
