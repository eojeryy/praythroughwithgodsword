@extends('layout.admin')
@section('title', 'Books Library')
@section('body_class', 'admin-workspace')
@section('content')
@php
  $filterFields = [
  ['name' => 'book_name', 'label' => 'Book name'],
  ['name' => 'testament_id', 'label' => 'Testament', 'options' => $testaments->pluck('name', 'id')],
  ['name' => 'chapter_count', 'label' => 'Chapter count', 'type' => 'number'],
];
@endphp
@include('admin.partials.library', ['records' => $books, 'type' => 'book', 'plural' => 'books', 'total' => $totalBooks, 'filterFields' => $filterFields])
@endsection
