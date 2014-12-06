<?php
namespace JsonRpc\Base;

class Request extends Rpc
{

  protected $method = '';
  protected $params = null;
  protected $notification = false;

  protected $request = null;


  public function __construct($struct)
  {

    $ok = is_array($struct) || is_object($struct);

    if ($ok)
    {
      $this->init($struct, is_array($struct));
    }
    else
    {
      $this->fault = $this->getErrorMsg('');
    }

  }


  public function getRequest()
  {
    return $this->request;
  }


  public function toJson()
  {
    return static::encode($this->request);
  }


  private function init($struct, $new)
  {

    try
    {

      if ($this->get($struct, 'id', static::MODE_EXISTS))
      {
        $this->id = $this->get($struct, 'id');
      }
      else
      {
        $this->notification = true;
      }

      $this->setVersion($struct, $new);

      $this->method = $this->get($struct, 'method');

      if ($this->get($struct, 'params', static::MODE_EXISTS))
      {
        $this->params = $this->get($struct, 'params');
      }

      // Assemble the request
      $this->request['jsonrpc'] = $this->jsonrpc;
      $this->request['method'] = $this->method;

      if (is_array($this->params))
      {
        $this->request['params'] = $this->params;
      }

      if (!$this->notification)
      {
        $this->request['id'] = $this->id;
      }

      return true;

    }
    catch (\Exception $e)
    {
      $this->fault = $e->getMessage();
    }

  }


}
