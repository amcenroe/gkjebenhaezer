<html>
  <head>
   <title> testing </title>
   <script language="javascript" src="http://www.google.com/jsapi"></script>
   </head>
   <body>

   <div id="chart"></div>
      <div id="chart2"></div>

   <script type="text/javascript">
      var queryString = '';
      var dataUrl = '';

      function onLoadCallback() {
        if (dataUrl.length > 0) {
          var query = new google.visualization.Query(dataUrl);
          query.setQuery(queryString);
          query.send(handleQueryResponse);
        } else {
          var dataTable = new google.visualization.DataTable();
          dataTable.addRows(4);

          dataTable.addColumn('number');
          dataTable.setValue(0, 0, 2);
          dataTable.setValue(1, 0, 4);
          dataTable.setValue(2, 0, 6);
          dataTable.setValue(3, 0, 3);

          draw(dataTable);
        }
      }

      function draw(dataTable) {
        var vis = new google.visualization.ImageChart(document.getElementById('chart'));
        var options = {
          chxs: '0,000000,11.5',
          chxt: 'x',
          chs: '400x250',
          cht: 'p3',
          chco: '1500FF',
          chd: 't:2,4,6,3',
          chdl: 'label1|label2|label3|label4',
          chdlp: 'b',
          chl: 'lbl1|lbl2|lbl3|lbl4',
          chma: '|5',
          chtt: 'testing'
        };
        vis.draw(dataTable, options);
      }

      function handleQueryResponse(response) {
        if (response.isError()) {
          alert('Error in query: ' + response.getMessage() + ' ' + response.getDetailedMessage());
          return;
        }
        draw(response.getDataTable());
      }

      google.load("visualization", "1", {packages:["imagechart"]});
      google.setOnLoadCallback(onLoadCallback);

    </script>
  <script type="text/javascript">
      var queryString = '';
      var dataUrl = '';

      function onLoadCallback() {
        if (dataUrl.length > 0) {
          var query = new google.visualization.Query(dataUrl);
          query.setQuery(queryString);
          query.send(handleQueryResponse);
        } else {
          var dataTable = new google.visualization.DataTable();
          dataTable.addRows(4);

          dataTable.addColumn('number');
          dataTable.setValue(0, 0, 2);
          dataTable.setValue(1, 0, 4);
          dataTable.setValue(2, 0, 6);
          dataTable.setValue(3, 0, 3);

          draw(dataTable);
        }
      }

      function draw(dataTable) {
        var vis = new google.visualization.ImageChart(document.getElementById('chart2'));
        var options = {
          chxs: '0,000000,11.5',
          chxt: 'x',
          chs: '400x250',
          cht: 'p3',
          chco: '1500FF',
          chd: 't:2,4,6,3',
          chdl: 'label1|label2|label3|label4',
          chdlp: 'b',
          chl: 'lbl1|lbl2|lbl3|lbl4',
          chma: '|5',
          chtt: 'testing'
        };
        vis.draw(dataTable, options);
      }

      function handleQueryResponse(response) {
        if (response.isError()) {
          alert('Error in query: ' + response.getMessage() + ' ' + response.getDetailedMessage());
          return;
        }
        draw(response.getDataTable());
      }

      google.load("visualization", "1", {packages:["imagechart"]});
      google.setOnLoadCallback(onLoadCallback);

    </script>	
  </body>
</html>
