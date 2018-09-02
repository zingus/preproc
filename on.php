<?php
#on C4-C5 map to C1-C2
require('vendor/autoload.php');

use Preproc\Parser;


$p=new Parser();

/*
$on=$pp->addDirective('#on');

$map=$on->x('[ABCDEFG][1-4]');
  $map->add('from');
$map->add('to');

$p->pattern('on',)
  $p->pattern('midinote','\d+');
$p->pattern('([A-G]-?\d)-([A-G]-?\d)');



$a=array(
  'on',
  p('midinote (\d+)','note'),
  p('([A-G]-?\d)'),
  p('([A-G]-?\d)-([A-G]-?\d)'),
  p()
);

'^#on $event $command'
  $event='([A-G]-?\d)|([A-G]-?\d)-([A-G]-?\d)|midinote (\d+)';
$command

*/

$data="

#on C4-C5 
#

";


/*$o=0;
foreach(array('/#on\s+/m','/[A-G](-?\d)/A','/-/A','/([A-G]-?\d)/A') as $p) {
  $ok=preg_match($p,$data,$m,PREG_OFFSET_CAPTURE,$o);
  echo "$ok\n";
  if($ok) {
    echo var_export($m); echo "\n";
    $o=$m[0][1]+strlen($m[0][0]);
  }
}*/

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

class PregSeq
{
  function __construct()
  {
    $this->patterns=func_get_args();
    $this->matches=array();
    $this->startMatch=null;
    $this->endMatch=null;
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
}

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

class PregOptSeq extends PregSeq
{
  function match($data,$off=-1)
  {
    $ok=PregSeq::match($data,$off);
    return true;
  }
}

$notespec=new PregPattern('(?P<pch>[A-G])(?P<oct>-?\d)');
$p=new PregSeq('(?m)^#(on)\s+',$notespec,new PregOptSeq('-',$notespec));
$p=new PregSeq('(?m)^#(on)\s+',$notespec,'-',$notespec);
$p->match($data);
var_export($p->patterns); echo "\n";
