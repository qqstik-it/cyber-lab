(function () {
    try {
        localStorage.setItem('cyberlab_draft', 'flag{offline_draft}');
        document.cookie = 'cyberlab_hint=flag{cookie_trail}; path=/; max-age=86400; SameSite=Lax';
    } catch (e) {
        /* offline lab */
    }
})();
