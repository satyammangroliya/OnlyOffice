<?php

namespace srag\Plugins\OnlyOffice\UI;

use ILIAS\DI\Container;
use srag\Plugins\OnlyOffice\StorageService\DTO\FileVersion;

// If rendering with React.js
use ilTemplate;
use Matrix\Exception;
use V8Js;


// If rendering with ILIAS
use ILIAS\UI\Implementation\Component\Table as T;
use ILIAS\UI\Component\Table as I;
use ILIAS\Data\Range;
use ILIAS\Data\Order;

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
    /**
     * @var int
     */
    protected $obj_id;

    //If rendering with ILIAS
    protected $f;
    protected $r;
    protected $columns;

    public function __construct(Container $dic, int $obj_id, array $data){
        $this->dic = $dic;
        $this->obj_id = $obj_id;
        $this->data = $data;

        // If rendering withILIAS
        $this->f = $this->dic->ui()->factory();
        $this->r = $this->dic->ui()->renderer();
        //$this->initiateColumns();
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
            '<button>Editor öffnen</button>'.
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

    /**
     * Render with React.js
     * TODO: Does not work yet!
     *
     * @return string
     */
    public function renderReactTable(): string {
        $tpl = new ilTemplate(__DIR__ . '/table/build/index.html', false, false);
        $json = json_encode($this->data);

        $v8 = new V8Js(); // TODO Why cant V8Js not be found at runtime?


        $react = [
            file_get_contents(__DIR__.'/table/node_modules/react/dist/react.min.js'),
            file_get_contents(__DIR__.'/table/node_modules/react-dom/dist/react-dom.min.js'),
            file_get_contents(__DIR__.'/table/bundle.js'),
            'React.renderToString(React.createElement(App,' . $json . '))'];

        try {
            $reactString = $v8->executeString(implode(PHP_EOL, $react));
        } catch (Exception $e) {
           $reactString = '<h1>'.$e->getMessage().'</h1>
                            <p>'.$e->getTraceAsString().'</p>';
        }

        return $reactString.$tpl->get();

        /**$result = '<script type="application/javascript">' .
            'window.exod_log_data = ' . $json . ';' .
            //'window.lng = "' . $this->dic->language()->getLangKey() . '";' .
            '</script>'
            . $tpl->get();
        return $result;**/
    }

    public function renderIliasTable() :string{
        $actions = array("All" => "#",	"Upcoming events" => "#");
        $aria_label = "filter entries";
        $view_controls = array(
            $this->f->viewControl()->mode($actions, $aria_label)->withActive("All")
        );

        //build table
        $table = $this->f->table()->presentation(
            'Document History', //title
            $view_controls,
            function ($row, $record, $ui_factory, $environment) { //mapping-closure
                return $row
                    ->withImportantFields(
                        array(
                            $record['Version'],
                            'Datum' => $record['createdAt'],
                            $record['userId']
                        )
                    )

                    ->withFurtherFieldsHeadline('Detailed Information')
                    ->withAction(
                        $ui_factory->button()->standard('Download', '#')
                    );
            }
        );
        return $this->r->render($table->withData($this->data));


/*        $table = $this->f->table()->data('Document History', 50)
                         ->withColumns($this->columns)
                         ->withData($this->getRows());

        //apply request and render
        $request = $this->dic->http()->request();
        return $this->r->render($table->withRequest($request));*/

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
}