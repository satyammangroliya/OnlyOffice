<?php

use ILIAS\DI\Container;

/**
 * Class xoofAbstractGUI
 *
 * @author Theodor Truffer <tt@studer-raimann.ch>
 */
abstract class xonoAbstractGUI
{

    /**
     * @var Container
     */
    protected $dic;
    /**
     * @var ilOnlyOfficePlugin
     */
    protected $plugin;


    /**
     * xoofAbstractGUI constructor.
     *
     * @param Container          $dic
     * @param ilOnlyOfficePlugin $plugin
     */
    public function __construct(Container $dic, ilOnlyOfficePlugin $plugin)
    {
        $this->dic = $dic;
        $this->plugin = $plugin;
    }


}