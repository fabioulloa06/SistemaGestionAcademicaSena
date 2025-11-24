<?php
$path = '.env';
if (!file_exists($path)) {
    copy('.env.example', '.env');
}
$content = file_get_contents($path);
$config = [
    'MAIL_MAILER' => 'smtp',
    'MAIL_HOST' => 'smtp.gmail.com',
    'MAIL_PORT' => '587',
    'MAIL_USERNAME' => 'fabio.ulloa06@gmail.com',
    'MAIL_PASSWORD' => 'bddv fnnk vujw etmr',
    'MAIL_ENCRYPTION' => 'tls',
    'MAIL_FROM_ADDRESS' => 'fabio.ulloa06@gmail.com',
];

foreach ($config as $key => $value) {
    if (preg_match("/^{$key}=/m", $content)) {
        $content = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $content);
    } else {
        $content .= PHP_EOL . "{$key}=\"{$value}\"";
    }
}

file_put_contents($path, $content);
echo "Updated .env successfully.";
