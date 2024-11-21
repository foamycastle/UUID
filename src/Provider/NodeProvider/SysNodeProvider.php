<?php

namespace Foamycastle\UUID\Provider\NodeProvider;

use Foamycastle\UUID\Provider\NodeProvider;
use Foamycastle\UUID\Provider\ProvidesHex;
use Foamycastle\UUID\Provider\RandomProvider\RandomWord;

/**
 * Seeks node values on the current system for use in a UUID node field
 * @author Aaron Sollman <unclepong@gmail.com>
 */
class SysNodeProvider extends NodeProvider implements ProvidesHex
{
    /**
     * Regex for matching node addresses in the output of system commands
     */
    private const string REGEX='/(?|([a-f0-9]++)(:|-)|[a-f0-9]++){6}/i';

    /**
     * An array of commands on all supported systems
     */
    private const array COMMANDS=[
        'DAR'=>'ifconfig 2>&1',
        'FRE'=>'netstat -i -f link 2&>1',
        'LIN'=>'netstat -ie 2>&1',
        'WIN'=>'ipconfig /all 2>&1',
    ];

    /**
     * The command matched to the current system
     */
    private string $command;

    /**
     * An array of commands that cannot be executed on the current system
     */
    private static array $disabled;


    public function __construct()
    {
        $this->command=self::COMMANDS[strtoupper(substr(PHP_OS,0,3))];
        $this->random=new RandomWord(48);
    }
    public function toHex(): string
    {
        return $this->data ?? $this->refreshData()->data;
    }

    function refreshData(): static
    {
        //use a random value first, if needed before seeking system nodes
        if($this->useRandom){
            self::$nodes[0]=$this->random->refreshData()->toHex();
        }

        //retrieve a list of disabled functions. find the functions
        //associated with the seek strategies
        if(empty(self::$disabled)){
            self::$disabled=array_filter(
                explode(',',ini_get('disable_functions')),
                function($value){
                    return in_array($value,['shell_exec','passthru','exec']);
                }
            );
        }

        //if no nodes have been read from the system
        if(empty(self::$nodes)){
            
            //choose the best seek strategy for the current system 
            $commandOutput = $commandOutput ??= $this->doShellExec();
            $commandOutput = $commandOutput ??= $this->doPassthru();
            $commandOutput = $commandOutput ??= $this->doExec();

            //if that search yeilded no result, use a random value
            if(empty($commandOutput)) {
                self::$nodes[]=$this->random->refreshData()->toHex();
                $this->useRandom=true;
                return $this;
            }

            //match all the node addresses contained in the command output
            if(preg_match_all(self::REGEX,$commandOutput,$match)!=0) {
                self::$nodes = str_replace([':','-'],"",$match[0]);
            }else{

                //if there are no matches, use a random value
                self::$nodes[]=$this->random->toHex();
                $this->useRandom=true;
                return $this;
            }
        }
        $this->data=self::$nodes[0];
        return $this;
    }

    /**
     * shell_exec seek strategy
     * @return string|false 
     */
    private function doShellExec():string|false
    {
        return shell_exec($this->command);
    }

    /**
     * passthru seek strategy.
     * @return string|false
     */
    private function doPassthru ():string|false
    {
        ob_start();
        passthru($this->command);
        return ob_get_flush();
    }

    /**
     * exec seek strategy
     * @return string|false
     */
    private function doExec():string|false
    {
        //exec returns the command output in an array of lines of output
        $output=[];
        if(exec($this->command,$output)!==false){
            return implode("\n",$output);
        }
        return false;

    }
    function reset(): static
    {
        return $this;
    }
}