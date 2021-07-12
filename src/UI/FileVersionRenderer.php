<?php
namespace srag\Plugins\OnlyOffice\UI;

use ILIAS\UI\Implementation\Component\Table as T;
use ILIAS\UI\Component\Table as I;
use ILIAS\Data\Range;
use ILIAS\Data\Order;
use ILIAS\DI\Container;
use srag\Plugins\OnlyOffice\StorageService\DTO\FileVersion;

class FileVersionRenderer  //extends T\DataRetrieval
{

    /**
     * @var array
     */
    protected $data;
    /**
     * @var Container
     */
    protected $dic;
    /**
     * @var int
     */
    protected $obj_id;

    public function __construct(Container $dic, int $obj_id, array $data){
        $this->dic = $dic;
        $this->obj_id = $obj_id;
        $this->data = $data;
    }

    /**
     * Some very ugly "hand coded" template to visualize versions.
     * Used to test whether fetching file versions works correctly.
     *
     * Compromise as ILIAS rendering does not work (NYI)
     * and there is some issue with React.js too.
     *
     * @return string
     */
    public function renderUglyTable() : string {
        /** @var string $result */
        $result = '<div id="document_history">' .
            '<table><tr><th width="25%">Version</th><th width=25%>Date</th><th>Editor</th><th width=25%>Dateigrösse</th><th width=25%>Aktion</th></tr>';
        for ($i = 0; $i < count($this->data); $i++) {
            /** @var FileVersion $fileVersion */
            $fileVersion = $this->data[$i];
            $result .= '<tr><td>' . $fileVersion->getVersion() . '</td><td>' . $fileVersion -> getCreatedAt()->__toString() . '</td>'. //TODO: __toString() does not work!
                '<td>' . $fileVersion->getUserId() . '</td><td></td><td><button>Download</button></td></tr>';
        }
        $result .= '</table></div>';
        return $result;
    }



}

    /*protected $f;
    protected $r;
    protected $columns;*/




    /*public function __construct(Container $dic, int $obj_id, array $data)
    {
        $this->dic = $dic;
        $this->obj_id = $obj_id;
        $this->data = $data;
        $this->f = $this->dic['ui.factory'];
        $this->r = $this->dic['ui.renderer'];
        $this->initiateColumns();
    }

    public function render() {
        //setup the table
        $table = $this->f->table()->data('Document History', 50)
                   ->withColumns($this->columns)
                   ->withData($this->getRows()); //TODO: Does this work?

        //apply request and render
        $request = $this->dic->http()->request();
        return $this->r->render($table->withRequest($request));

    }

    public function getRows(
        I\RowFactory $row_factory,
        Range $range,
        Order $order,
        array $visible_column_ids,
        array $additional_parameters
    ) : \Generator {
        foreach ($this->data as $record) {
            //TODO: Replace userID with the users name
            yield $row_factory->map($record);
        }
    }

    private function initiateColumns() {
        $columns = [
            'version' => $this->f->table()->column()->text("Version")->withIsSortable(false), //TODO: Why does this not work? NYI -> Not yet implemented?
            'createdAt' => $this->f->table()->column()->text("Date")->withIsSortable(false),
            'user' => $this->f->table()->column()->text("Editor")->withIsSortable(false)
        ];
    }
}*/

/*


use ILIAS\DI\Container;
use ilTemplate;

class FileVersionRenderer
{
    /**
     * @var array

    protected $data;

    /**
     * @var Container

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
        return $result;
    }

}*/