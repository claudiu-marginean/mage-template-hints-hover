<?php
/**
 * This file is part of the TemplateHintsHover project.
 *
 * Hackathon TemplateHintsHover is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License version 3 as
 * published by the Free Software Foundation.
 *
 * This script is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * PHP version 5
 *
 * @category  TemplateHintsHover
 * @package   Hackathon_TemplateHintsHover
 * @author    Claudiu Marginean <claudiu.f.marginean@gmail.com>
 * @author    Mihai MATEI <mihaimatei@gmail.com>
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version   0.1.0
 */

/**
 * Observer
 *
 * @category  TemplateHintsHover
 * @package   Hackathon_TemplateHintsHover
 * @author    Claudiu Marginean <claudiu.f.marginean@gmail.com>
 * @author    Mihai MATEI <mihaimatei@gmail.com>
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @version   0.1.0
 */
class Hackathon_TemplateHintsHover_Model_Observer
{
    /**
     * @var int
     */
    protected $hintId = 0;

    /**
     * Check if hints should be displayed
     *
     * @return bool
     */
    public function showHints() {
        if (is_null($this->showHints)) {
            $this->showHints = false;

            if (Mage::getModel('core/cookie')->get('thh') || Mage::getSingleton('core/app')->getRequest()->get('thh')) {
                $this->showHints = true;
            }
        }
        return $this->showHints;
    }

    /**
     * Add Template Hints
     *
     * @param Varien_Event_Observer $observer
     *
     */
    public function addTemplateHints(Varien_Event_Observer $observer)
    {
        if (!$this->showHints()) {
            return;
        }

        if (substr(trim($observer->getTransport()->getHtml()), 0, 4) == 'http') {
            return;
        }

        $block = $observer->getBlock(); /* @var $block Mage_Core_Block_Abstract */

        if (!$block || !($block instanceof Mage_Core_Block_Abstract)) {
            return;
        }

        $transport = $observer->getTransport();
        $blockHtml = $transport->getHtml();

        $this->hintId++;

        $wrappedHtml = '<div id="thh-' . $this->hintId . '-start" class="thh-wrap thh-start"></div>';
        $wrappedHtml .= $blockHtml;
        $wrappedHtml .= '<div id="thh-' . $this->hintId . '-stop" class="thh-wrap thh-stop"></div>';

        $transport->setHtml($wrappedHtml);

    }
}
