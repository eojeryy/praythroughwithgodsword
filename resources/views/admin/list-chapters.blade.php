@extends('layout.admin')
@section('title', 'Chapters Library')
@section('body_class', 'admin-workspace')
@section('content')
@php
  $filterFields = [
  ['name' => 'testament_id', 'label' => 'Testament', 'options' => $testaments->pluck('name', 'id')],
  ['name' => 'book_id', 'label' => 'Book', 'options' => $books->pluck('name', 'id')],
  ['name' => 'chapter_number', 'label' => 'Chapter number', 'type' => 'number', 'min' => 1],
];
@endphp
@include('admin.partials.library', ['records' => $chapters, 'type' => 'chapter', 'plural' => 'chapters', 'total' => $totalChapters, 'filterFields' => $filterFields])
@endsection
