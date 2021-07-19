
function RenderTable() {
  const DATA = window.exod_log_data;
  const COLUMNS = [
    {
      Header: 'Version',
      accessor: 'version'
    },
    {
      Header: 'Date',
      accessor: 'created_at'
    },
    {
      Header: 'Editor',
      accessor: 'user'
    },
    {
      Header: 'Size',
      accessor: 'size'
    },
    {
      Header: 'Download',
      accessor: 'download'
    }
  ]


}