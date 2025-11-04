<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ResolveCompanyFromUri
{
    public function handle(Request $request, Closure $next): Response
    {
        $companyParam = $request->route('company');

        if (! $companyParam instanceof Company) {
            $company = Company::query()
                ->where('uri', $companyParam)
                ->first();

            if (! $company) {
                abort(404, 'Empresa não encontrada para esta URI.');
            }

            // Atualiza o parâmetro da rota para a instância resolvida.
            $request->route()->setParameter('company', $company);
        } else {
            $company = $companyParam;
        }

        $request->attributes->set('company', $company);
        View::share('company', $company);

        app()->setLocale($company->locale ?? config('app.locale'));

        return $next($request);
    }
}
