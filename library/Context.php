<?php 

namespace library;

class Context
{
  // server protocol
  private $protocol;

  // domain name
  private $domain;
    
  // server port
  private $port;
  private $disp_port;


  public function __construct()
  {
    $this->protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
    $this->domain = $_SERVER['SERVER_NAME'];
    $this->port = $_SERVER['SERVER_PORT'];
    $this->disp_port = ($this->protocol == 'http' && $this->port == 80 || $this->protocol == 'https' && $this->port == 443) ? '' : ":$this->port";
  }

  /**
   *
   */
  private function GetWebContext(): string 
  {
    // put em all together to get the complete base URL
    return $this->protocol . "://" . $this->domain . $this->disp_port;
  }

  /**
   * 
   */
  public function BuildEmailContent(string $path, array|null $data=[]): string
  {
    if (!empty($data))
    return file_get_contents($this->GetWebContext() . $path . ".php?" . http_build_query($data));

    return file_get_contents($this->GetWebContext() . $path . ".php");
  }
}