<?php

namespace AppBundle\Block\Service;

use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\TwigBundle\TwigEngine;

class StatisticChartBlockService extends AbstractBlockService
{
	private $entityManager;

	public function __construct(string $serviceId, TwigEngine $templating, EntityManagerInterface $entityManager) {
		$this->entityManager = $entityManager;
		parent::__construct($serviceId, $templating);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'Stats Block';
	}

	/**
	 * {@inheritdoc}
	 */
	public function configureSettings(OptionsResolver $resolver) {
		$resolver->setDefaults([
			'entity' => 'AppBundle:Statistic',
			'repository_method' => 'findAll',
			'title' => 'Insert block Title',
			'css_class' => 'bg-blue',
			'icon' => '',
			'template' => 'AppBundle:Block:block_stats.html.twig',
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function buildEditForm(FormMapper $formMapper, BlockInterface $block) {
		$formMapper->add('settings', 'sonata_type_immutable_array', [
				'keys' => [
					['entity', 'text', ['required' => false]],
					['repository_method', 'text'],
					['title', 'text', ['required' => false]],
					['css_class', 'text', ['required' => false]],
					['icon', 'text', ['required' => false]],
				],
			]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function validateBlock(ErrorElement $errorElement, BlockInterface $block) {
		$errorElement->with('settings[entity]')->assertNotNull(array())->assertNotBlank()->end()
			->with('settings[repository_method]')->assertNotNull(array())->assertNotBlank()->end()
			->with('settings[title]')->assertNotNull(array())->assertNotBlank()->assertMaxLength(array('limit' => 50))
			->end()->with('settings[css_class]')->assertNotNull(array())->assertNotBlank()->end()
			->with('settings[icon]')->assertNotNull(array())->assertNotBlank()->end();
	}

	/**
	 * {@inheritdoc}
	 */
	public function execute(BlockContextInterface $blockContext, Response $response = null) {
		$settings = $blockContext->getSettings();
		$entity = $settings['entity'];
		$method = $settings['repository_method'];
		$rows = $this->entityManager->getRepository($entity)->$method();
		return $this->templating->renderResponse($blockContext->getTemplate(), [
				'count' => $rows,
				'block' => $blockContext->getBlock(),
				'settings' => $settings,
			], $response);
	}
}