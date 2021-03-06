<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../settings.php';

/**
 * Creates an auth object for a CredentialToken required in the SDK constructor for calling all endpoints. Passing through the CredentialToken constructor: the credential public key, handler public key and handler private key, so the auth token can be generated.
 */
$auth = new \idOS\Auth\CredentialToken(
    $credentials['credentialPublicKey'],
    $credentials['handlerPublicKey'],
    $credentials['handlerPrivKey']
);

/**
 * Calls the create method that instantiates the SDK passing the auth object trought the constructor.
 */
$sdk = \idOS\SDK::create($auth);

/**
 * Calling the Profile Class passing the username, and after that, the Processes Endpoint and the method listAll to get a random process id.
 *
 * @var [type]
 */
$response = $sdk
    ->Profile($credentials['username'])
    ->Processes
    ->listAll();

$processId = $response['data'][0]['id'];

/**
 * Calling the Profile Class passing the username, and after that, the Process Class passing the $processId trough the constructor. After that calls the Task Ednpoint and the method listAll.
 */
$response = $sdk
    ->Profile($credentials['username'])
    ->Process($processId)
    ->Tasks
    ->listAll();

/**
 * Prints the response.
 */
print_r($response);

/**
 * Creates a new task
 */
$response = $sdk
	->Profile($credentials['username'])
	->Process($processId)
	->Tasks
	->createNew('Testing', 'testing', true, true, '');

/**
 * Prints the response
 */
print_r($response);

/**
 * Updates a task
 */
$response = $sdk
	->Profile($credentials['username'])
	->Process($processId)
	->Tasks
	->updateOne($response['data']['id'], 'Test', 'test', false, false, 'dummy-message');

/**
 * Prints the response
 */
print_r($response);

/**
 * Gets one task
 */
$taskId = $response['data']['id'];

$response = $sdk
    ->Profile($credentials['username'])
    ->Process($processId)
    ->Tasks
    ->getOne($taskId);

/**
 * Prints the response
 */
print_r($response);
