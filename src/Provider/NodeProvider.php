<?php

namespace Foamycastle\UUID\Provider;

defined("CACHE_PREFIX")||define("CACHE_PREFIX", "nodeCache_");
defined('CACHE_TTL')||define('CACHE_TTL', 86400);
defined("REGEX_PARSE")||define("REGEX_PARSE", "/(?:[a-f0-9]{2}[\-\:]){5}[a-f0-9]{2}/i");

use Foamycastle\UUID\Provider;
use Foamycastle\UUID\Provider\NodeProvider\DarwinNodeProvider;
use Foamycastle\UUID\Provider\NodeProvider\FreeBSDNodeProvider;
use Foamycastle\UUID\Provider\NodeProvider\LinuxNodeProvider;
use Foamycastle\UUID\Provider\NodeProvider\WinNodeProvider;
use Foamycastle\UUID\ProviderApi;

abstract class NodeProvider extends Provider implements NodeProviderApi
{
    /**
     * @var string[] Stores nodes retrieved from the system
     */
    protected array $nodes=[];
    protected bool $apcu=false;

    protected function __construct(ProviderKey $key)
    {
        $this->key = $key;
        $this->register();
        $this->apcu=apcu_enabled();
        $this->nodes=$this->apcu ? $this->getSystemNodes() : $this->parseCommandOutput();
    }

    public function getAllNodes(): array
    {
        return $this->nodes;
    }
    public function getFirstNode(): string
    {
        return reset($this->nodes) ?: '';
    }
    public function getLastNode(): string
    {
        return end($this->nodes) ?: '';
    }
    public function getAnyNode(): string
    {
        $count=count($this->nodes);
        $select=rand(0, $count-1);
        return $count>0 ? $this->nodes[$select] : '';
    }

    public function __invoke(...$args): static
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getData(): mixed
    {
        return $this->getAllNodes();
    }

    /**
     * @inheritDoc
     */
    public function refreshData(): static
    {
        apcu_clear_cache();
        $this->nodes=$this->getSystemNodes();
        return $this;
    }
    protected function getCache():array|false
    {
        if(!$this->apcu) return $this->parseCommandOutput();
        return apcu_entry(CACHE_PREFIX.'System',[$this,'parseCommandOutput'],CACHE_TTL);

    }
    protected function setCache($args):bool
    {
        if(!$this->apcu) return false;
        return apcu_add(CACHE_PREFIX.'System',$args,CACHE_TTL) ?: false;
    }
    protected function parseCommandOutput():array
    {
        $output=[];
        $count=preg_match_all(REGEX_PARSE, shell_exec($this->shellCommand()) ?: '', $output);
        return is_int($count) && $count > 0 ? $output[0] : [];
    }

    protected function getSystemNodes(): array
    {
        $cache=$this->getCache();
        if($cache===false)
        {
            $cache=$this->parseCommandOutput();
            $this->setCache($cache);
        }
        return $cache;
    }
    protected abstract function shellCommand(): string;

    public static function System():ProviderApi
    {
        $os=strtoupper(substr(PHP_OS,0,3));
        return match ($os) {
            'WIN' => new WinNodeProvider(),
            'LIN' => new LinuxNodeProvider(),
            'DAR' => new DarwinNodeProvider(),
            'FRE' => new FreeBSDNodeProvider(),
        };
    }

}