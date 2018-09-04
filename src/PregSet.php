<?php
namespace Preproc;
require_once('vendor/autoload.php');

class PregSet extends PregSeq
{
  function match($data,$off=-1)
  {
    $this->preparePatterns();
    $this->startMatch=$this->endMatch=null;
    foreach($this->patterns as $p) {
      $ok=$p->match($data,$off);
      if ($ok) {
        $this->startMatch = $p->startMatch;
        $this->endMatch   = $p->endMatch;
        break;
      }
    }
    return $ok;
  }
}
