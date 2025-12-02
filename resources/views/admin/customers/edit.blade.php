@extends('admin.layouts.app')

@section('header', 'Editar cliente')

@section('content')
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Atualize os dados do cliente <strong>{{ $customer->name }}</strong>.</p>

    <form action="{{ route('admin.customers.update', ['company' => $company, 'customer' => $customer]) }}" method="POST" class="grid gap-6">
        @include('admin.customers._form', ['company' => $company, 'customer' => $customer, 'submitLabel' => 'Salvar alterações'])
    </form>
@endsection
