@extends('layout.admin')
@section('title', 'Verses Library')
@section('body_class', 'admin-workspace')
@section('content')
@php
  $filterFields = [
  ['name' => 'search', 'label' => 'Scripture text'],
  ['name' => 'prayer_topic', 'label' => 'Prayer topic'],
  ['name' => 'verse_number', 'label' => 'Verse number or range'],
  ['name' => 'testament_id', 'label' => 'Testament', 'options' => $testaments->pluck('name', 'id')],
  ['name' => 'book_id', 'label' => 'Book', 'options' => $books->pluck('name', 'id')],
  ['name' => 'chapter_id', 'label' => 'Chapter', 'options' => $chapters->mapWithKeys(fn ($chapter) => [$chapter->id => ($chapter->book?->name ?? $chapter->book_name) . ' ' . $chapter->chapter_number])],
  ['name' => 'category_id', 'label' => 'Category', 'options' => $categories->pluck('name', 'id')],
];
@endphp
@include('admin.partials.library', ['records' => $verses, 'type' => 'verse', 'plural' => 'verses', 'total' => $totalVerses, 'filterFields' => $filterFields])
@endsection
