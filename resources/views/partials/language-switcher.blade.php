{{-- Language switcher, shared by the admin and storefront headers.

     Links rather than a form so it works without JavaScript and on any page;
     the current language is marked and not clickable. --}}
@php($current = app()->getLocale())

<span class="language-switcher">
    @foreach (\App\Http\Middleware\SetLocale::SUPPORTED as $code => $label)
        @if ($code === $current)
            <span class="language-switcher__item is-active" aria-current="true">{{ strtoupper($code) }}</span>
        @else
            <a class="language-switcher__item" href="/locale/{{ $code }}" title="{{ $label }}">{{ strtoupper($code) }}</a>
        @endif
    @endforeach
</span>
