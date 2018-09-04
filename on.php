<?php
namespace Preproc;
#on C4-C5 map to C1-C2
require_once('vendor/autoload.php');

use Preproc\Parser;

$p=new Parser();

$data="

#on C4-C5 
#

";


class PregSeqPlus extends PregSeq
{
  function match($data,$off=-1)
  {
    return PregSeq::matchPlus($data,$off);
  }
}

class PregSeqStar extends PregSeq
{
  function match($data,$off=-1)
  {
    return PregSeq::matchStar($data,$off);
  }
}

class PregSeqMaybe extends PregSeq
{
  function match($data,$off=-1)
  {
    return PregSeq::matchMaybe($data,$off);
  }
}

class PregSetPlus extends PregSet
{
  function match($data,$off=-1)
  {
    return PregSet::matchPlus($data,$off);
  }
}

class PregSetStar extends PregSet
{
  function match($data,$off=-1)
  {
    return PregSet::matchStar($data,$off);
  }
}

class PregSetMaybe extends PregSet
{
  function match($data,$off=-1)
  {
    return PregSet::matchMaybe($data,$off);
  }
}

$notespec=new PregPattern('(?P<pch>[A-G])(?P<oct>-?\d)');
$p=new PregSeq('(?m)^#(on)\s+',$notespec,new PregSeqMaybe('-',$notespec));
$p=new PregSeq('(?m)^#(on)\s+',clone $notespec,'-',clone $notespec);
$p->match($data);

$commands=array(
  new PregSeq('map\s*',new PregSet('from\s+()','to\s+')),
);
var_export($p->patterns); echo "\n";
