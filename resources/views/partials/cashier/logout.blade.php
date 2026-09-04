{{-- Sign-out control for the register.

     Most cashier views are standalone HTML documents rather than children of
     layouts.cashier, so this is included per view instead of living in one
     layout. Left out of the print views (returnPrint, returnPreview), where a
     button would end up on the paper. --}}
@auth
    <a href="/cashier/logout" class="cashier-logout" title="{{ trans('messages.Logout') }}">
        <i class="material-icons">power_settings_new</i>
        <span>{{ trans('messages.Logout') }}</span>
    </a>
@endauth
