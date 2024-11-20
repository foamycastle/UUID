<?php

namespace Foamycastle\UUID\Provider;

use Foamycastle\UUID\Provider;
use Foamycastle\UUID\Provider\RandomProvider\RandomWord;
use Foamycastle\UUID\ProviderApi;
use Stringable;

class NodeProvider extends Provider implements ProvidesHex
{
    private const REGEX='/(?|([a-f0-9]++)(:|-)|[a-f0-9]++){6}/i';
    private const COMMANDS=[
        'DAR'=>'ifconfig 2>&1',
        'FRE'=>'netstat -i -f link 2&>1',
        'LIN'=>'netstat -ie 2>&1',
        'WIN'=>'ipconfig /all 2>&1',
    ];
    private string $command;
    private static array $nodes;
    private static array $disabled;
    private RandomWord $random;
    public function __construct()
    {
        $this->command=self::COMMANDS[strtoupper(substr(PHP_OS,0,3))];
        $this->random=new RandomWord(48);
        parent::__construct();
    }
    public function toHex(): string
    {
        return $this->data;
    }

    function refreshData(): Provider
    {
        if(empty(self::$disabled)){
            self::$disabled=array_filter(
                explode(',',ini_get('disable_functions')),
                function($value){
                    return in_array($value,['shell_exec','passthru','exec']);
                }
            );
        }
        if(empty(self::$nodes)){
            $commandOutput = $commandOutput ??= $this->doShellExec();
            $commandOutput = $commandOutput ?: $this->doPassthru();
            $commandOutput = $commandOutput ?: $this->doExec();
            if(empty($commandOutput)) {
                self::$nodes[]=$this->random->toHex();
                return $this;
            }
            if(preg_match_all(self::REGEX,$commandOutput,$match)!=0) {
                self::$nodes = str_replace([':','-'],"",$match[0]);
            }else{
                self::$nodes[]=$this->random->toHex();
                return $this;
            }
        }
        $this->data=self::$nodes[0];
        return $this;
    }
    private function doShellExec():string|false
    {
        return shell_exec($this->command);
    }
    private function doPassthru ():string|false
    {
        ob_start();
        passthru($this->command);
        return ob_get_flush();
    }
    private function doExec():string|false
    {
        $output=[];
        if(exec($this->command,$output)!==false){
            return implode("\n",$output);
        }
        return false;

    }
    function reset(): ProviderApi
    {
        return $this;
    }

}