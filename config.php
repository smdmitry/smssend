<?

$config = [
    'salt' => 'salt',
    'type' => 'type',
    'coords' => 'MCC:000,000,000,000
MNC:00,00,00,00
LAC:0000,0000,0000,0000
CID:0000,0000,0000,0000',
];

if (file_exists('prod.config.override.php')) {
    include 'prod.config.override.php';
}