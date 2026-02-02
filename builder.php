<?php
// XLNZdev by Fsb, licence : MIT
declare(strict_types=1);
define('ABSPATH', __DIR__);
if (! \in_array(PHP_SAPI, ['cli', 'micro'])) {exit('CLI only');}

$copt = getopt("a:");
$a = $copt['a'] ?? 'help';
$binname = 'phpdeskapp';
$fphar = $binname.'.phar';
$dphar_ar = [dirname(__FILE__).'/assets'];

if ('phar' === $a) {
  if (\is_file($fphar)) {
    unlink($fphar);
  }
  $phar = new Phar($fphar);
  $phar->startBuffering();
  $phar->addFile(ABSPATH.'/launcher.php', 'launcher.php');
  $phar->addFile(ABSPATH.'/index.php', 'index.php');

  foreach ($dphar_ar as $dphar) {
    $phar->buildFromDirectory($dphar);
  }
  
  $phar->setStub($phar->createDefaultStub('launcher.php'));
  $phar->compressFiles(Phar::GZ);
  $phar->stopBuffering();
  chmod($fphar, 0770);
  echo '✅ PHAR created: '.$fphar.PHP_EOL;

} elseif ('exe' === $a) {
  if (\is_file($fphar)) {
    passthru(ABSPATH.'/vendor/bin/phpacker build all --src=./'.$fphar);

    if (\is_file(ABSPATH.'/build/windows/windows-x64.exe')) {
      rename(ABSPATH.'/build/windows/windows-x64.exe', ABSPATH.'/build/windows/'.$binname.'.exe');
    }

    if (\is_file(ABSPATH.'/build/mac/mac-arm')) {
      rename(ABSPATH.'/build/mac/mac-arm', ABSPATH.'/build/mac/'.$binname.'-arm');
    }

    if (\is_file(ABSPATH.'/build/mac/mac-x64')) {
      rename(ABSPATH.'/build/mac/mac-x64', ABSPATH.'/build/mac/'.$binname.'-x64');
    }

    if (\is_file(ABSPATH.'/build/linux/linux-arm')) {
      rename(ABSPATH.'/build/linux/linux-arm', ABSPATH.'/build/linux/'.$binname.'-arm');
    }

    if (\is_file(ABSPATH.'/build/linux/linux-x64')) {
      rename(ABSPATH.'/build/linux/linux-x64', ABSPATH.'/build/linux/'.$binname.'-x64');
    }

    echo 'Exe Files is on build folder'.PHP_EOL;

  } else {
    echo 'ERR: Phar file not founded'.PHP_EOL;
  }

} else {
  echo 'Console commands:'.PHP_EOL;
  echo '- "php builder.php -a phar" to build phar file'.PHP_EOL;
  echo '- "php builder.php -a exe" to build exe files'.PHP_EOL;
}