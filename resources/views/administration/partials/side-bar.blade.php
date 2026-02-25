@php
    $isEmploye = auth()->user()->role === 'employe';
@endphp

<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
  <div class="sidebar-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
      <a href="{{ route('admin.dashboard') }}" class="logo">
        <span class="ml-0 fw-bold d-none d-md-inline" style="font-size: 24px; color:rgb(172, 200, 231); text-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);">
          Gestion De Stock
        </span>
      </a>

      <div class="nav-toggle">
        <button class="btn btn-toggle toggle-sidebar"><i class="gg-menu-right"></i></button>
        <button class="btn btn-toggle sidenav-toggler"><i class="gg-menu-left"></i></button>
      </div>

      <button class="topbar-toggler more"><i class="gg-more-vertical-alt"></i></button>
    </div>
  </div>

  <div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
      <ul class="nav nav-secondary">

        <!-- Tableau de bord -->
        <li class="nav-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
          <a href="{{ route('admin.dashboard') }}">
            <i class="fas fa-home"></i>
            <p>Tableau de bord</p>
          </a>
        </li>

        <!-- Section titre -->
        <li class="nav-section">
          <span class="sidebar-mini-icon"><i class="fa fa-ellipsis-h"></i></span>
          <h4 class="text-section">Menu</h4>
        </li>

        <!-- Produits -->
        <li class="nav-item {{ Route::is('produits.index') ? 'active' : '' }}">
          @if ($isEmploye)
            <a href="javascript:void(0)" class="disabled-link">
              <i class="fas fa-box-open"></i>
              <p>Produits</p>
            </a>
          @else
            <a href="{{ route('produits.index') }}">
              <i class="fas fa-box-open"></i>
              <p>Produits</p>
            </a>
          @endif
        </li>

        <!-- Approvisionnements -->
        <li class="nav-item {{ Route::is('approvisionnements.index') ? 'active' : '' }}">
          @if ($isEmploye)
            <a href="javascript:void(0)" class="disabled-link">
              <i class="fas fa-truck-loading"></i>
              <p>Approvisionnements</p>
            </a>
          @else
            <a href="{{ route('approvisionnements.index') }}">
              <i class="fas fa-truck-loading"></i>
              <p>Approvisionnements</p>
            </a>
          @endif
        </li>

        <!-- Ventes (toujours accessible) -->
        <li class="nav-item {{ Route::is('ventes.index') ? 'active' : '' }}">
          <a href="{{ route('ventes.index') }}">
            <i class="fas fa-shopping-cart"></i>
            <p>Ventes</p>
          </a>
        </li>

        <!-- État de stock -->
        <li class="nav-item {{ Route::is('stocks.index') ? 'active' : '' }}">
          <a href="{{ route('stocks.index') }}">
            <i class="fas fa-warehouse"></i>
            <p>État de Stock</p>
          </a>
        </li>

        <!-- Gestion des employés -->
        <li class="nav-item {{ Route::is('employes.index') ? 'active' : '' }}">
          @if ($isEmploye)
            <a href="javascript:void(0)" class="disabled-link">
              <i class="fas fa-users"></i>
              <p>Gestion des employés</p>
            </a>
          @else
            <a href="{{ route('employes.index') }}">
              <i class="fas fa-users"></i>
              <p>Gestion des employés</p>
            </a>
          @endif
        </li>

        <!-- Déconnexion -->
        <li class="nav-item mt-3">
          <form action="{{ route('logout') }}" method="POST" class="d-flex align-items-center">
            @csrf
            <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center">
              <i class="fas fa-sign-out-alt me-2"></i>
              Se déconnecter
            </button>
          </form>
        </li>

      </ul>
    </div>
  </div>
</div>

<!-- CSS pour désactiver les liens -->
<style>
  .disabled-link {
    pointer-events: none;
    opacity: 0.5;
    cursor: not-allowed;
  }
</style>
