<?php

function send_html_mail(string $to, string $subject, string $html): bool
{
    $headers = [];
    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=UTF-8';
    $headers[] = 'From: QUICK\'EVENTS <noreply@quicks-events.local>';

    return mail($to, $subject, $html, implode("\r\n", $headers));
}
