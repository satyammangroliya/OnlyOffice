export{}

/*
import React, {useMemo} from "react";
import { useTable } from "react-table";
import {COLUMNS} from "./Columns";

export default const VersionTable = () => {

    const columns = useMemo(() => COLUMNS, []);
    // @ts-ignore
    const data = useMemo(() => window.exod_log_data, []);


    const tableInstance = useTable({
        columns,
        data
    })

    const {getTableProps, getTableBodyProps, headerGroups, rows, prepareRow} = tableInstance;

    return (
        <table {...getTableProps()}>
            <thead> {headerGroups.map((headerGroup: any) => (
                <tr {...headerGroup.getHeaderGroupProps()}>
                    {headerGroup.headers.map ((column: any) => (
                        <th {...column.getHeaderProps()}>{column.render('Header')}</th>
                    ))}
                </tr>
            ))}
            </thead>

            <tbody {...getTableBodyProps()}>
            {
                rows.map((row : any) => {
                    prepareRow(row)
                    return (
                        <tr {...row.getRowProps()}>
                            {row.cells.map((cell: any) => {
                                return <td {...cell.getCellProps()}>{cell.render('Cell')}</td>
                        })}
                        </tr>
                    )
                })}
            </tbody>
        </table>
    )
}*/
