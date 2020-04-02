<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" dir="{{ config('backpack.base.html_direction') }}">

<head>
  @include(backpack_view('inc.head'))
    @yield('head_style')
</head>

<body class="{{ config('backpack.base.body_class') }}">

  @include(backpack_view('inc.main_header'))

  <div class="app-body">

    @include(backpack_view('inc.sidebar'))

    <main class="main pt-2">

       @includeWhen(isset($breadcrumbs), backpack_view('inc.breadcrumbs'))

       @yield('header')

        <div class="container-fluid animated fadeIn">

          @if (isset($widgets['before_content']))
            @include(backpack_view('inc.widgets'), [ 'widgets' => $widgets['before_content'] ])
          @endif

          @yield('content')

          @if (isset($widgets['after_content']))
            @include(backpack_view('inc.widgets'), [ 'widgets' => $widgets['after_content'] ])
          @endif

        </div>

    </main>

  </div><!-- ./app-body -->

  <footer class="{{ config('backpack.base.footer_class') }}">
    @include(backpack_view('inc.footer'))
  </footer>

  @yield('before_scripts')
  @stack('before_scripts')

  @include(backpack_view('inc.scripts'))

  @if(session()->has('success_message'))
  <script>
      $(document).ready(function () {
          swal({
              title: "Success!",
              text: '{{ session()->get('success_message') }}',
              icon: "success",
              timer: 4000,
              buttons: false,
          });
      });
  </script>
  @endif
  @if(session()->has('error_message'))
      <script>
          $(document).ready(function () {
              swal({
                  title: "Error!",
                  text: '{{ session()->get('error_message') }}',
                  icon: "error",
                  timer: 4000,
                  buttons: false,
              });
          });
      </script>
  @endif

  @yield('after_scripts')
  @stack('after_scripts')
</body>
</html>
