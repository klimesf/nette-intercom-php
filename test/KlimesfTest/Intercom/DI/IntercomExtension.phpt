<?php

use Klimesf\Intercom\DI\IntercomExtension;
use Nette\DI;
use Tester\Assert;

require __DIR__ . '/../../../bootstrap.php';

$compiler = new DI\Compiler;
$compiler->addExtension('intercom', new IntercomExtension());

$appId = 123;
$apiKey = 'abc123';

eval($code = $compiler->compile(
	[
		'intercom' => [
			'appId'  => $appId,
			'apiKey' => $apiKey,
		],
	],
	'Container'
));
$container = new Container();

/** @var \Intercom\IntercomBasicAuthClient $client */
$client = $container->getService('intercom.basicAuthClient');
Assert::type(\Intercom\IntercomBasicAuthClient::class, $client);
Assert::same($appId, $client->getConfig('request.options')['auth'][0]);
Assert::same($apiKey, $client->getConfig('request.options')['auth'][1]);
