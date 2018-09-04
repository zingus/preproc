<?php
namespace Preproc;
require_once('vendor/autoload.php');

class PregSeq
{
  function __construct()
  {
    $this->patterns=func_get_args();
    $this->matches=array();

    $this->startMatch=null;
    $this->endMatch=null;
    $this->repeatNum=0;
  }

  function preparePatterns()
  {
    foreach($this->patterns as $k=>$v) {
      if(is_string($v)) $this->patterns[$k]=new PregPattern($v);
    }
  }

  function match($data,$off=-1)
  {
    $o=-1;
    $this->preparePatterns();
    $this->startMatch=$this->endMatch=null;
    foreach($this->patterns as $p) {
      $ok=$p->match($data,$o);
      if($ok) {
        if (is_null($this->startMatch))
          $this->startMatch=$p->startMatch;
        //$this->matches[]=$p->matches;
        $o=$p->endMatch;
      }
      $this->endMatch=$p->endMatch;
    }
    return $ok;
  }

  function matchMaybe($data,$off=-1)
  {
    $this->match($data,$off);
    return true;
  }

  function matchPlus($data,$off=-1)
  {
    $ret=false;
    while($this->match($data,$off)) {
      $ret=true;
    }
    return $ret;
  }
  
  function matchStar($data,$off=-1)
  {
    $this->matchPlus($data,$off=-1);
    return true;
  }
}
