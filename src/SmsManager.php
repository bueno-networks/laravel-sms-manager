<?php

namespace Bnw\SmsManager;

use Bnw\SmsManager\Clients\Allcance;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Str;
use InvalidArgumentException;

class SmsManager {

    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The array of resolved SMS clients.
     *
     * @var array
     */
    protected $clients = [];

    /**
     * Create a new SMS manager instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

     /**
     * Get a SMS client instance.
     *
     * @param  string|null  $name
     * @return \Illuminate\Contracts\Cache\Repository
     */
    public function driver($name = null)
    {
        return $this->client($name);
    }

    /**
     * Get a SMS client instance.
     *
     * @param  string|null  $name
     * @return \Illuminate\Contracts\Cache\Repository
     */
    public function client($name = null)
    {
        $name = $name ?: $this->getDefaultDriver();

        if (! isset($this->clients[$name])) {
            $this->clients[$name] = $this->resolve($name);
        }

        return $this->clients[$name];
    }

     /**
     * Resolve the given client.
     *
     * @param  string  $name
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        $method = 'create'.Str::studly($name).'Driver';

        if (method_exists($this, $method)) {
            return $this->$method($config);
        }

        throw new InvalidArgumentException("Driver [$name] is not supported.");
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->app['config']['sms.default'];
    }

    /**
    * Get the sms connection configuration.
    *
    * @param  string  $name
    * @return array
    */
    protected function getConfig($name)
    {
        return $this->app['config']["sms.clients.{$name}"] ?: [];
    }

    /**
     * Create an instance of the Allcance SMS client.
     *
     * @param  mixed  $config
     * @return \Bnw\SmsManager\Clients\Allcance
     */
    protected function createAllcanceDriver($config) : Allcance
    {
        return new Allcance($this->getAuthentication($config['user'], $config['password']));
    }

    protected function getAuthentication($user, $password) : String
    {
        return base64_encode($user . ':' . $password);
    }
}
