function exportTableToExcel(idTable, idButton){
  var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';

  tab_text = tab_text + "<table border='1px'>";
  tab_text = tab_text + $(idTable).html();
  tab_text = tab_text + '</table></body></html>';

  var data_type = 'data:application/vnd.ms-excel';

  var ua = window.navigator.userAgent;
  var msie = ua.indexOf("MSIE ");

  if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
    if (window.navigator.msSaveBlob) {
      var blob = new Blob([tab_text], {
        type: "application/csv;charset=utf-8;"
      });
      navigator.msSaveBlob(blob, 'Test file.xls');
    }
  } else {
    $(idButton).attr('href', data_type + ', ' + encodeURIComponent(tab_text));
    $(idButton).attr('download', 'vendas.xls');
  }
}
