<?php

namespace Records\Model;

class HtmlTags extends \Zend\Validator\AbstractValidator
{
    const NOT_CLOSED = 'notClosed';
    const NOT_PAIRS = 'notPairs';
    
    protected $messageTemplates = array(
        self::NOT_CLOSED   => "The input must contain only closed tags",
        self::NOT_PAIRS   => "The input must contain only <tag1></tag1> tags pairs"
    );
    
    public function isValid($value)
    {
        preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $value, $result_open);
        $opened = $result_open[1];

        preg_match_all('#</([a-z]+)>#iU', $value, $result_close);
        $closed = $result_close[1];
        $len_opened = count($opened);

        $len_closed = count($closed);
        
        if ($len_closed != $len_opened) {
            $this->error(self::NOT_CLOSED);
            return false;
        }
        else {
            for($i=0; $i<$len_opened; $i++)
            {
                $len_closed = count($closed);
                if(!$len_closed) {
                    $this->error(self::NOT_CLOSED);
                    return false;
                }
                for($j=0; $j<$len_closed; $j++)
                {
                    if(@$result_open[1][$i] == @$result_close[1][$j])
                    {
                        unset($result_close[1][$j]);
                        break;
                    }
                }
            }
            if($result_close[1]) {
                
                $this->error(self::NOT_PAIRS);
                return false;
            }
        }
        return true;
    }
}