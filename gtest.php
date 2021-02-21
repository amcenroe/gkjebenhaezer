<?php
//$K1 = FilterInput($_GET["K1"],'char');
//$K2 = FilterInput($_GET["K2"],'char');
//$V1 = FilterInput($_GET["V1"],'int');
//$V2 = FilterInput($_GET["V2"],'int');

//echo $K1."-".$K2."-".$V1."-".$V2;
//echo $chartdiv;
?>

    <!--Load the AJAX API-->

    <script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart<?php echo json_encode($div); ?>);
	//  google.setOnLoadCallback(initialize);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart<?php echo json_encode($div); ?>() {
//google.setOnLoadCallback(initialize);
        // Create the data table.
        var data<?php echo json_encode($div); ?> = new google.visualization.DataTable();
        data<?php echo json_encode($div); ?>.addColumn('string', 'Key');
        data<?php echo json_encode($div); ?>.addColumn('number', 'Value');
        data<?php echo json_encode($div); ?>.addRows([
          [<?php echo json_encode($K1); ?>, <?php echo json_encode($V1); ?>],
          [<?php echo json_encode($K2); ?>, <?php echo json_encode($V2); ?>],
          [<?php echo json_encode($K3); ?>, <?php echo json_encode($V3); ?>],
		  [<?php echo json_encode($K4); ?>, <?php echo json_encode($V4); ?>],
		  [<?php echo json_encode($K5); ?>, <?php echo json_encode($V5); ?>],
		  [<?php echo json_encode($K6); ?>, <?php echo json_encode($V6); ?>],
		  [<?php echo json_encode($K7); ?>, <?php echo json_encode($V7); ?>],
		  [<?php echo json_encode($K8); ?>, <?php echo json_encode($V8); ?>],
		  [<?php echo json_encode($K9); ?>, <?php echo json_encode($V9); ?>],
		  [<?php echo json_encode($K10); ?>, <?php echo json_encode($V10); ?>]

        ]);

        // Set chart options
        var options<?php echo json_encode($div); ?> = {'title':<?php echo json_encode($Judul); ?>,
                       'width':360,
                       'height':300};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById(<?php echo json_encode($chartdiv); ?>));
        chart.draw(data<?php echo json_encode($div); ?>, options<?php echo json_encode($div); ?>);
      }
    </script>




