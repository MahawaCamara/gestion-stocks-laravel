@include('administration.partials.head')

  <body>
    <div class="wrapper">

        @include('administration.partials.side-bar')

        <div class="main-panel">
            @include('administration.partials.header')

        <div class="container">
          @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: '{{ session('success')}}',
                timer: 2500,
                showConfirmButton: false
            });
        });
    </script>

@endif

          @yield('content')
        </div>

        @include('administration.partials.footer')
      </div>

      @include('administration.partials.theme')
    </div>
    
    @include('administration.partials.javascript_section')
  </body>
</html>
