<?php

// Add rewrite rule for /username/portfolio_user
add_action('init', function () {
    add_rewrite_rule('^([^/]+)/?$', 'index.php?portfolio_user=$matches[1]', 'top');

    /**
     * Rewrite tag, secretly does 2 things:
     * 1. Registers the query var (portfolio_user) — just like add_filter('query_vars') would do.
     * 2. Allows %portfolio_user% to be used in future add_rewrite_rule() definitions.
     */
    add_rewrite_tag('%portfolio_user%', '([^&]+)');
});

/* 
// Optional: if using rewrite tag doesn't need/make any diff
// Register Query Vars (dynamic slug)
add_filter('query_vars', function ($vars) {
    $vars[] = 'portfolio_user';
    return $vars;
}); */