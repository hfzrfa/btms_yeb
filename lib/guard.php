<?php
require_once __DIR__ . '/auth.php';

function require_role(string ...$roles): void {
    if (!user_has_role(...$roles)) {
        redirect('index.php?page=dashboard');
    }
}
