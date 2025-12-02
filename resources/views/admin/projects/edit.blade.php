@extends('admin.layouts.app')

@section('header', 'Editar projeto / cliente')

@section('content')
    <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Atualize os dados do projeto <strong>{{ $project->name }}</strong>.</p>

    <form action="{{ route('admin.projects.update', ['company' => $company, 'project' => $project]) }}" method="POST" class="grid gap-6 md:max-w-4xl">
        @include('admin.projects._form', ['project' => $project, 'plans' => $plans, 'customers' => $customers, 'company' => $company, 'submitLabel' => 'Salvar alterações'])
    </form>
@endsection
