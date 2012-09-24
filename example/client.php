<?php

  chdir(__DIR__);
  ini_set('default_charset', 'UTF-8');
  ini_set('display_errors', '1');

  # autoload for the example directory
  require('autoload.php');

  # get the url of the server script
  $url = getServerUrl();

  # create our client object, passing it the server url
  $Client = new JsonRpc\Client($url);

  # set up our rpc call with a method and params
  $method = 'divide';
  $params1 = array(42, 6);

  $params2 = new stdClass();
  $params2->dividend = 42;
  $params2->divisor = 6;

  $params3 = new stdClass();
  $params3->divisor = 6;
  $params3->dividend = 42;

  $params4 = new stdClass();
  $params4->int = true;
  $params4->dividend = 23;
  $params4->divisor = 6;

  //$param1 = 'Hello';

  //$param2 = new \stdClass();
  //$param2->name = 'Client';

  $res = $Client->call($method, $params4);

  /*
  # notify
  $res = $Client->notify($method);
  */

  /*
  # batch sending
  $Client->batchOpen();
  $Client->call($method, array($param1, $param2));
  $Client->notify($method, array($param1));
  $Client->call($method, array($param1, $param2));
  $Client->notify($method, array($param1, $param2));
  $Client->call($method, $param2);
  $res = $Client->batchSend();
  */

  echo '<form method="GET">';
  echo '<input type="submit" value="Run Example">';
  echo '</form>';
  echo '<pre>';

  echo '<b>return:</b> ';
  echo $res ? 'true' : 'false';
  echo '<br /><br />';

  if (!$res)
  {
    echo '<b>fault:</b> ', $Client->fault;
    echo '<br /><br />';
  }

  if ($Client->error)
  {
    echo '<b>error:</b> ', print_r($Client->error, 1);
    echo '<br /><br />';
  }
  elseif ($Client->batch)
  {
    echo '<b>batch:</b> ', print_r($Client->batch, 1);
    echo '<br /><br />';
  }
  else
  {
    echo '<b>result:</b> ', print_r($Client->result, 1);
    echo '<br /><br />';
  }

  echo '<b>output:</b> ', $Client->output;


function getServerUrl()
{

  $path = dirname($_SERVER['PHP_SELF']) . '/server.php';
  $scheme = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') ? 'https' : 'http';
  return $scheme . '://' . $_SERVER['HTTP_HOST'] . $path;

}