<form method="GET" action="{{ route('ventes.filtrer') }}" class="row g-3">
    <div class="col-md-4">
        <label>Date</label>
        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
    </div>
    <div class="col-md-4">
        <label>Employé</label>
        <select name="employe" class="form-control">
            <option value="">-- Tous --</option>
            @foreach($employes as $employe)
                <option value="{{ $employe->id }}" {{ request('employe') == $employe->id ? 'selected' : '' }}>
                    {{ $employe->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4 align-self-end">
        <button class="btn btn-primary">🔍 Filtrer</button>
    </div>
</form>

@if($ventes->count())
    <table class="table mt-4">
        <thead><tr><th>Produit</th><th>Employé</th><th>Date</th><th>Total</th></tr></thead>
        <tbody>
            @foreach($ventes as $vente)
                <tr>
                    <td>{{ $vente->produit->designation }}</td>
                    <td>{{ $vente->user->name ?? 'N/A' }}</td>
                    <td>{{ $vente->date->format('d/m/Y') }}</td>
                    <td>{{ number_format($vente->total, 2) }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="text-muted mt-4">Aucune vente trouvée.</p>
@endif
