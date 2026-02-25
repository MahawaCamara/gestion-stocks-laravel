@extends('administration.base')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Liste des Employés</h2>
    <a href="{{ route('employes.create') }}" class="btn btn-success mb-3">Ajouter un Employé</a>

    <!-- Formulaire de recherche -->
    <form method="GET" action="{{ route('employes.index') }}" class="mb-3 d-flex gap-2">
        <input type="text" name="search" class="form-control" placeholder="Recherche par nom ou email" value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Rechercher</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>
                    <a href="{{ route('employes.index', ['sort_field' => 'name', 'sort_direction' => ($sortField === 'name' && $sortDirection === 'asc') ? 'desc' : 'asc'] + request()->except('page')) }}">
                        Nom
                        @if($sortField === 'name')
                            @if($sortDirection === 'asc') &#9650; @else &#9660; @endif
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('employes.index', ['sort_field' => 'email', 'sort_direction' => ($sortField === 'email' && $sortDirection === 'asc') ? 'desc' : 'asc'] + request()->except('page')) }}">
                        Email
                        @if($sortField === 'email')
                            @if($sortDirection === 'asc') &#9650; @else &#9660; @endif
                        @endif
                    </a>
                </th>
                <th>
                    <a href="{{ route('employes.index', ['sort_field' => 'is_active', 'sort_direction' => ($sortField === 'is_active' && $sortDirection === 'asc') ? 'desc' : 'asc'] + request()->except('page')) }}">
                        Statut
                        @if($sortField === 'is_active')
                            @if($sortDirection === 'asc') &#9650; @else &#9660; @endif
                        @endif
                    </a>
                </th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employes as $employe)
            <tr>
                <td>{{ $employe->name }}</td>
                <td>{{ $employe->email }}</td>
                <td>
                    @if($employe->is_active)
                        <span class="badge bg-success">Actif</span>
                    @else
                        <span class="badge bg-danger">Inactif</span>
                    @endif
                </td>
                <td class="d-flex gap-1">
                    <!-- Activer/Désactiver -->
                    <form action="{{ route('employes.toggle', $employe) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $employe->is_active ? 'btn-warning' : 'btn-success' }}">
                            {{  $employe->is_active ? 'Désactiver' : 'Activer' }}
                        </button>
                    </form>

                    <!-- Supprimer -->
                    <button class="btn btn-danger btn-sm" onclick="confirmerSuppression('{{ $employe->id }}')">Supprimer</button>
                    <form id="form-supprimer-{{ $employe->id }}" action="{{ route('employes.destroy', $employe) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Aucun employé trouvé.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination et nombre total -->
    <div class="d-flex justify-content-between align-items-center">
        <p class="text-muted">Nombre total : {{ $employes->total() }}</p>
        {{ $employes->appends(request()->query())->links() }}
    </div>
</div>
@endsection


