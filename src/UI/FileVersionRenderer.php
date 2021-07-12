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


    public function __construct(Container $dic, int $obj_id, array $data)
    {
        $this->dic = $dic;
        $this->obj_id = $obj_id;
        $this->data = $data;
    }

    public function renderTable(): string {
        $tpl = new ilTemplate(__DIR__ . '/table/build/index.html', false, false);
        $json = json_encode(array_values($this->data));
        $result = '<script type="application/javascript">' .
            'window.exod_log_data = ' . $json . ';' .
            //'window.lng = "' . $this->dic->language()->getLangKey() . '";' .
            '</script>'
            . $tpl->get();
        return result;
    }

}