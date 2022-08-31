<?php

declare(strict_types=1);

/**
 * @copyright   Copyright (c) Y1 Digital AG (http://www.y1.de/)
 * @contact     info@y1.de
 */

namespace Y1\ManagePagebuilderStatus\Plugin;

use Magento\Cms\Model\Block;
use Magento\Cms\Model\Page;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Ui\Component\Form;

/**
 * Manage usage of pagebuilder based on cms block/page preferences.
 *
 * Has to be done here because pagebuilder related components are created before the data is normally available:
 * @see \Magento\PageBuilder\Component\Form\Element\Wysiwyg::__construct()
 *
 * Data is only normally available here, when all ui components hav already been created:
 * @see \Magento\Framework\View\Element\UiComponent\Context::getDataSourceData()
 *
 * @author Dominik Meglič <dominik.meglic@y1.de>
 */
class ManagePagebuilderEditor
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @see \Magento\Framework\View\Element\UiComponentFactory::create()
     */
    public function afterCreate(UiComponentFactory $subject, UiComponentInterface $resultComponent): UiComponentInterface
    {
        if ($resultComponent instanceof Form &&
            ($resultComponent->getName() == 'cms_block_form' || $resultComponent->getName() == 'cms_page_form')
        ) {
            $entity = $this->getEntity($resultComponent);
            $contentComponent = null;

            if ($resultComponent->getName() == 'cms_block_form') {
                $contentComponent = $resultComponent->getComponent('general')->getComponent('content');
            } elseif ($resultComponent->getName() == 'cms_page_form') {
                $contentComponent = $resultComponent->getComponent('content')->getComponent('content');
            }

            if ($contentComponent != null) {
                $configuration = $contentComponent->getConfiguration();

                if ($entity->getData('is_pagebuilder_enabled') !== null) {
                    $configuration['wysiwygConfigData']['is_pagebuilder_enabled'] = (bool)$entity->getData('is_pagebuilder_enabled');

                    $contentComponent->setData('config', $configuration);
                }
            }
        }

        return $resultComponent;
    }

    /**
     * @return Block|Page|null
     */
    private function getEntity(UiComponentInterface $uiComponent): ?DataObject
    {
        $dataObject = null;

        if ($uiComponent->getName() == 'cms_block_form') {
            $dataObject = $this->registry->registry('cms_block');
        }

        if ($uiComponent->getName() == 'cms_page_form') {
            $dataObject = $this->registry->registry('cms_page');
        }

        return $dataObject;
    }
}
