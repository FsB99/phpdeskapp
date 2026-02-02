<?php
// XLNZdev by Fsb, licence : MIT
declare(strict_types=1);
define('ABSPATH', __DIR__);
if (! \in_array(PHP_SAPI, ['cli', 'micro'])) {exit('CLI only');}

$port = 8010;
start_browser();
start_server();

function check_os(): array {
  return [
    'os' => \strtolower(PHP_OS_FAMILY),
    'arc' => \strtolower(\php_uname('m'))
  ];
}

function start_browser(): void {
  global $port;
  $uri = 'http://localhost:'.(string) $port;
  $os = check_os()['os'];
  $brow = check_browser();
  $cmd = match($os) {
    'windows' => 'cmd /c start "" '.$brow.' --app="'.$uri.'"',
    'darwin' => 'open -na "'.$brow.'" --args --app="'.$uri.'"',
    default => 'xdg-open "'.$brow.'"',
  };
  echo 'Running browser on URL: '.$uri.PHP_EOL;
  if ($pso = popen($cmd, 'r')) {
    pclose($pso);
    unset($pso);
  }
}

function start_server(): void {
  global $port;
  echo 'Running server on port: '.$port.PHP_EOL;

  // WARNING, dumb TCP!!! 
  $server = stream_socket_server('tcp://127.0.0.1:'.$port, $err, $errstr);
  if (!$server) {
    fwrite(STDERR, "$errstr ($err)\n");
    exit(1);
  }

  while ($conn = stream_socket_accept($server)) {
    $req = fread($conn, 8192);
    [$head] = explode("\r\n\r\n", $req, 2);
    [$line] = explode("\r\n", $head, 2);
    $parts = explode(' ', trim($line), 3);

    if (count($parts) < 2) {
      fclose($conn);
      continue;
    }

    $method = $parts[0];
    $uri    = $parts[1];
    $_SERVER['REQUEST_METHOD'] = $method;
    $_SERVER['REQUEST_URI']    = $uri;
    ob_start();
    require __DIR__.'/index.php';
    $body = ob_get_clean();

    $res =
      "HTTP/1.1 200 OK\r\n" .
      "Content-Type: text/html\r\n" .
      "Content-Length: " . strlen($body) . "\r\n\r\n" .
      $body;

    fwrite($conn, $res);
    fclose($conn);
  }
}

function check_browser(): string {
  $rt = '';
  $lists = ['chrome', 'firefox', 'brave'];
  foreach ($lists as $list) {
    if (cprg($list)) {
      $rt = $list;
      break;
    }
  }
  return $rt;
}

function cprg(string $prg): bool {
  $rt = false;
  $rraw = ('windows' === check_os()['os']) ? cu_exec('cmd /C where '.$prg) : cu_exec('command -v '.$prg);
  if (\is_string($rraw)) {
    $rt = (false !== \stripos($rraw, 'not find files')) ? false : true;
  } else {
    $rt = $rt_raw ?? false;
  }
  return $rt;
}

function cu_exec(string $arg): bool|string|null {
  $rt = false;
  if (false !== \stripos($arg, 'php -S localhost:') || false !== \stripos($arg, 'cmd /C where') || false !== \stripos($arg, 'command -v')) {
    if (\function_exists('shell_exec')) {
      $rt = \shell_exec(escapeshellcmd($arg));
    } else {
      echo 'ERR: shell_exec is not exists or disabled!'.PHP_EOL;
    }
    
  } else {
    echo 'ERR: command are not allowed!'.PHP_EOL;
  }
  return $rt;
}