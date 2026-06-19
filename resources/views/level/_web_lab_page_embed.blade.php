@if($embedSlot === 'sidebar' && $webLabEmbed === 'data_secret_link')
    <div class="mt-4 pt-3 border-top border-secondary-subtle">
        <a href="https://ccyberlabs.ru/login" class="text-white-50 small text-decoration-none">
            <span data-secret="flag{check_href}">Вход</span>
        </a>
    </div>
@endif

@if($embedSlot === 'main')
    @switch($webLabEmbed)
        @case('semantic_heading')
            <div class="visually-hidden" aria-hidden="true">
                <article>
                    <header>
                        <h1>Кибер-Лаб</h1>
                    </header>
                </article>
            </div>
            @break

        @case('img_alt')
            <img
                src="{{ asset('img/logo_letter.png') }}"
                alt="flag{alt_text}"
                width="1"
                height="1"
                class="visually-hidden"
                aria-hidden="true"
            >
            @break

        @case('html_entities')
            <!-- payload: &#102;&#108;&#97;&#103;&#123;&#101;&#110;&#116;&#105;&#116;&#105;&#101;&#115;&#125; -->
            @break

        @case('hidden_form_field')
            <form class="visually-hidden" aria-hidden="true" action="#" method="post" tabindex="-1">
                <input type="email" name="email" value="">
                <input type="password" name="password" value="">
                <input type="hidden" name="token" value="flag{hidden_field}">
                <button type="submit">Войти</button>
            </form>
            @break
    @endswitch
@endif

@if($embedSlot === 'form' && $webLabEmbed === 'comment_near_form')
<!-- audit-log: flag{view_source} -->
@endif
