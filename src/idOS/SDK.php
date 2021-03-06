<?php

namespace idOS;

use GuzzleHttp\Client;
use idOS\Auth\AuthInterface;
use idOS\Section\Profile;
use idOS\Section\Profile\Process;

class SDK {
    /**
     * Authentication instance.
     */
    private $authentication;
    /**
     * GuzzleHttp\Client.
     */
    private $client;
    /**
     * boolean option to throw exception.
     */
    private $throwsExceptions;

    /**
     * Creates the SDK instance.
     *
     * @param AuthInterface $authentication
     *
     * @return SDK instance
     */
    public static function create(AuthInterface $authentication, bool $throwsExceptions = false) {
        return new static(
            $authentication,
            new Client(),
            $throwsExceptions
        );
    }

    /**
     * Constructor Class.
     *
     * @param AuthInterface $authentication
     * @param Client        $client
     * @param bool|bool     $throwsExceptions
     */
    public function __construct(AuthInterface $authentication, Client $client, bool $throwsExceptions = false) {
        $this->authentication   = $authentication;
        $this->client           = $client;
        $this->throwsExceptions = $throwsExceptions;
    }

    /**
     * setter Stores auth object.
     *
     * @param AuthInterface $authentication
     */
    public function setAuth(AuthInterface $authentication) : self {
        $this->authentication = $authentication;

        return $this;
    }

    /**
     * Returns auth object.
     *
     * @return AuthInterface auth
     */
    public function getAuth() : AuthInterface {
        return $this->authentication;
    }

    /**
     * Setter sets GuzzleHttp\Client instance.
     *
     * @param Client $client
     */
    public function setClient(Client $client) : self {
        $this->client = $client;

        return $this;
    }

    /**
     * Returns the GuzzleHttp\Client instance.
     *
     * @return GuzzeHttp\Client client
     */
    public function getClient() : Client {
        return $this->client;
    }

    /**
     * Sets the throws exception option.
     *
     * @param bool $throws
     */
    public function setThrowsExceptions(bool $throws) : self {
        $this->throwsExceptions = $throws;

        return $this;
    }

    /**
     * Returns boolean value of $throwsExceptions.
     *
     * @return bool throwsExceptions
     */
    public function getThrowsExceptions() : bool {
        return $this->throwsExceptions;
    }

    /**
     * Return new instance of Section\Profile.
     *
     * @param string $userName
     *
     * @return Section\Profile instance
     */
    public function profile(string $userName) : Profile {
        return new Profile(
            $userName,
            $this->authentication,
            $this->client,
            $this->throwsExceptions
        );
    }

    /**
     * Return new instance of Company Endpoint.
     *
     * @param string $companySlug
     *
     * @return Endpoint\Company instance
     */
    public function company(string $companySlug) : Company {
        return new Endpoint\Company(
            $companySlug,
            $this->authentication,
            $this->client,
            $this->throwsExceptions
        );
    }

    /**
     * Return new instance of SSO Endpoint.
     *
     * @return Endpoint\SSO instance
     */
    public function sso() : SSO {
        return new Endpoint\SSO(
            $this->authentication,
            $this->client,
            $this->throwsExceptions
        );
    }

    /**
     * Return new instance of Section\Profile\Process.
     *
     * @param int $processId
     *
     * @return Section\Profile\Process instance
     */
    public function process(int $processId) : Process {
        return new Process(
            $processId,
            $this->authentication,
            $this->client,
            $this->throwsExceptions
        );
    }

    /**
     * Gets the ClassName and instantiates it.
     *
     * @param string $name
     *
     * @return new instance of the given class
     */
    public function __get(string $name) {
        $className = $this->getEndpointClassName($name);

        return new $className(
            $this->authentication,
            $this->client
        );
    }

    /**
     * Returns the instance of endpoint given with params.
     *
     * @param string $name
     * @param array  $args
     *
     * @return new instance of the given class
     */
    public function __call(string $name, array $args) {
        $className = $this->getSectionClassName($name);
        $args[]    = $this->authentication;
        $args[]    = $this->client;

        return new $className(...$args);
    }

    /**
     * Returns the name of the endpoint class.
     *
     * @param string $name
     *
     * @return string className
     */
    protected function getEndpointClassName(string $name) : string {
        $className = sprintf(
            '%s\\%s\\%s',
            'idOS',
            'Endpoint',
            ucfirst($name)
        );

        if (! class_exists($className)) {
            throw new \RuntimeException(
                sprintf(
                    'Invalid endpoint name "%s" (%s)',
                    $name,
                    $className
                )
            );
        }

        return $className;
    }
}
