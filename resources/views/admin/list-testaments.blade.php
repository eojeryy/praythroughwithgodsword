@extends('layout.admin')
@section('title', 'Testaments Library')
@section('body_class', 'admin-workspace')
@section('content')
@php
  $filterFields = [
  ['name' => 'testament_name', 'label' => 'Testament name'],
  ['name' => 'summary', 'label' => 'Summary'],
  ['name' => 'book_count', 'label' => 'Book count', 'type' => 'number'],
];
@endphp
@include('admin.partials.library', ['records' => $testaments, 'type' => 'testament', 'plural' => 'testaments', 'total' => $totalTestaments, 'filterFields' => $filterFields])
@endsection
