<?php


namespace Klimesf\Intercom\DI;

use Intercom\IntercomBasicAuthClient;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;
use Nette\Utils\Validators;

/**
 * @package   Klimesf\Intercom\DI
 * @author    Filip Klimes <filip@filipklimes.cz>
 */
class IntercomExtension extends CompilerExtension
{

	private $defaults = [
		'appId'  => null,
		'apiKey' => null,
	];

	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		$config = $this->validateConfig($this->defaults);
		$container = $this->getContainerBuilder();

		Validators::assertField($config, 'appId', 'string|number');
		Validators::assertField($config, 'apiKey', 'string|number');

		$container->addDefinition($this->prefix('basicAuthClient'))
			->setClass(IntercomBasicAuthClient::class)
			->setFactory(
				new Statement(
					IntercomBasicAuthClient::class . "::factory", [
						[
							'app_id'  => $config['appId'],
							'api_key' => $config['apiKey'],
						],
					]
				)
			);
	}

}
