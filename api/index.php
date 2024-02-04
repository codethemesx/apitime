<?php
function setCorsHeaders() {
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type");
}

function doGet($e) {
  setCorsHeaders();

  date_default_timezone_set('America/Recife');
  
  $tmzParam = isset($e['parameter']['tmz']) ? $e['parameter']['tmz'] : "America/Recife";
  
  $response = file_get_contents('https://api64.ipify.org?format=json');
  $ipData = json_decode($response, true);
  $clientIP = $ipData['ip'];

  function formatWithLeadingZero($number) {
    return str_pad($number, 2, '0', STR_PAD_LEFT);
  }

  $now = time();
  $formattedDate = date('d/m/Y', $now);
  $formattedHour = date('H:i:s', $now);

  $data = array(
    'unixTime' => $now,
    'date' => array(
      'day' => formatWithLeadingZero(date('d', $now)),
      'month' => formatWithLeadingZero(date('m', $now)),
      'year' => date('Y', $now)
    ),
    'time' => array(
      'hour' => date('H', $now),
      'minutes' => date('i', $now),
      'seconds' => date('s', $now),
      'ampm' => date('a', $now)
    ),
    'formattedDate' => $formattedDate,
    'formattedHour' => $formattedHour,
    'tmz' => $tmzParam,
    'IP' => $clientIP
  );

  $jsonString = json_encode($data);
  
  header('Content-Type: application/json');
  echo $jsonString;
}

// Exemplo de uso:
$example_e = array(
  'parameter' => array(
    'tmz' => 'America/Recife'
  )
);
doGet($example_e);
?>
