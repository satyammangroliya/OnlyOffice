import React from "react";
import ReactDOM from "react-dom";
//import MaterialTable from 'material-table';
//import columns from "./Columns";

class Table extends React.Component<any, any>{
    private data : any;

    constructor(props) {
        super(props);
        // @ts-ignore
        this.data = window.exod_log_data;
    }
    render() {
        return "<p> Hello World! </p>"
    }
}
export default Table;

const domContainer = document.querySelector('#document_history');
ReactDOM.render(e(Table), domContainer);