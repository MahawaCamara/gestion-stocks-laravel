@extends('administration.base')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-gradient bg-primary text-white rounded-top-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-clipboard-data me-2"></i>Détails de l'approvisionnement
                        </h5>
                        <a href="{{ route('approvisionnements.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-arrow-left-circle"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body px-4 py-4 bg-light">
                    <div class="row mb-3">
                        <label class="col-sm-4 fw-semibold text-secondary">Produit :</label>
                        <div class="col-sm-8">{{ $approvisionnement->produit->designation }}</div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 fw-semibold text-secondary">Quantité :</label>
                        <div class="col-sm-8">{{ $approvisionnement->quantite }}</div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 fw-semibold text-secondary">Prix unitaire :</label>
                        <div class="col-sm-8">{{ number_format($approvisionnement->prix_unitaire, 2) }} GNF</div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 fw-semibold text-secondary">Total :</label>
                        <div class="col-sm-8 text-success fw-bold">{{ number_format($approvisionnement->total, 2) }} GNF</div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 fw-semibold text-secondary">Date d’approvisionnement:</label>
                        <div class="col-sm-8">{{ $approvisionnement->date }}</div>
                    </div>

                    <div class="row">
                        <label class="col-sm-4 fw-semibold text-secondary">Ajouté le :</label>
                        <div class="col-sm-8">{{ $approvisionnement->created_at->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
