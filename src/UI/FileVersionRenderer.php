<?php

namespace srag\Plugins\OnlyOffice\UI;

use ILIAS\DI\Container;
use ilTemplate;

class FileVersionRenderer
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var Container
     */
    protected $dic;


    public function __construct(Container $dic, array $data)
    {
        $this->dic = $dic;
        $this->data = $data;
    }

    public function renderTable(): string {
        //$tpl = new ilTemplate(__DIR__.'/table/build/index.html', false, false);
        //return $tpl.get();
        return '<div><p> Hello World </p></div>';

    }

}