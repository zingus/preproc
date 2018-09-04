<?php
namespace Preproc;
require_once('vendor/autoload.php');

class PregPattern
{
  function __construct($pattern)
  {
    assert(is_string($pattern));
    $this->pattern=$pattern;
    $this->matches=array();
    $this->startMatch=null;
    $this->endMatch=null;
  }

  function match($data,$off=-1)
  {
    $f=($off<0)?'':'A';
    $ok=preg_match("/{$this->pattern}/$f",$data,$m,PREG_OFFSET_CAPTURE,($off<0)?0:$off);
    if($ok) {
      $this->match=$m[0];
      $this->startMatch=$m[0][1];
      $this->endMatch=$this->startMatch+strlen($m[0][0]);
      foreach($m as $k=>$v) {
        $this->matches[$k]=$v[0];
      }
    }
    return $ok;
  }

}
