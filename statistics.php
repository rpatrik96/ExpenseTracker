<?php
    session_start();
?>

<!DOCTYPE html>
<html>
    <?php
        require 'head.php';
        
    ?>
<script type="text/javascript" src="fusioncharts/fusioncharts.js"></script>
<script type="text/javascript" src="fusioncharts/themes/fusioncharts.theme.ocean.js"></script>
<body>
    <?php
        require 'menu.php';
    ?>
        
<!--<script type="text/javascript">
  FusionCharts.ready(function(){
    var revenueChart = new FusionCharts({
        "type": "column2d",
        "renderAt": "chartContainer",
        "width": "500",
        "height": "300",
        "dataFormat": "json",
        "dataSource":  {
          "chart": {
            "caption": "Monthly revenue for last year",
            "subCaption": "Harry's SuperMart",
            "xAxisName": "Month",
            "yAxisName": "Revenues (In USD)",
            "theme": "fint"
         },
         "data": [
            {
               "label": "Jan",
               "value": "420000"
            },
            {
               "label": "Feb",
               "value": "810000"
            },
            {
               "label": "Mar",
               "value": "720000"
            },
            {
               "label": "Apr",
               "value": "550000"
            },
            {
               "label": "May",
               "value": "910000"
            },
            {
               "label": "Jun",
               "value": "510000"
            },
            {
               "label": "Jul",
               "value": "680000"
            },
            {
               "label": "Aug",
               "value": "620000"
            },
            {
               "label": "Sep",
               "value": "610000"
            },
            {
               "label": "Oct",
               "value": "490000"
            },
            {
               "label": "Nov",
               "value": "900000"
            },
            {
               "label": "Dec",
               "value": "730000"
            }
          ]
      }

  });
revenueChart.render();
})
</script>-->
    <div id="chartContainer">FusionCharts XT will load here!</div>
    <div class="content">
    <form method="POST" class="form" >
        <label for="date_min" >Select date</label> 
        <BR/><BR/>
        <input id="date_min" type="date" name=date_min value="<?php echo date('Y-m-d'); ?>">
        <input id="date_max" type="date" name=date_max value="<?php echo date('Y-m-d'); ?>">
        <input type="submit" name=query>
    </form>
    
    <?php
        if($_SERVER['REQUEST_METHOD'] =="POST" and isset($_POST['query']))
        {
            $mysqli = new mysqli("localhost", "root", "", "expensetracker");
            if($mysqli->connect_errno)
            {
                echo "MySQL Error: " . $mysqli->connect_error . "<BR/>";
            }
            echo('<BR/><BR/><div class="form">');
            $query = sprintf("SELECT T.TransactionDate,C.CategoryName, T.TransactionDescription, T.TransactionValue FROM transaction T JOIN category C ON T.CategoryID=C.CategoryID WHERE TransactionOwnerID='%d' and TransactionDate BETWEEN '%s' and '%s' ORDER BY TransactionDate;",
                        $_SESSION['UserID'], $_POST['date_min'], $_POST['date_max']);
            $mysqli->real_query($query);
            $result = $mysqli->use_result();
            printf("<BR/><div class=\"table\"><TABLE>");
            printf("<TR><TH>Date</TH><TH>Category</TH><TH>Description</TH><TH>Value</TH></TR>");
            while($row = $result->fetch_row())
            {
                printf("<TR><TD>$row[0]</TD><TD>$row[1]</TD><TD>$row[2]</TD><TD>$row[3]</TD></TR>");
            }
            printf("</TABLE></div>");
            echo('</div>');
            $mysqli->close();
            $chart = new mysqli("localhost", "root", "", "expensetracker");
            if($chart->connect_errno)
            {
                echo "MySQL Error: " . $chart->connect_error . "<BR/>";
            }
            $chart_query = sprintf("SELECT C.CategoryName, SUM(T.TransactionValue) FROM transaction T JOIN category C ON T.CategoryID=C.CategoryID WHERE TransactionOwnerID='%d' and TransactionDate BETWEEN '%s' and '%s' GROUP BY T.CategoryID;",
                    $_SESSION['UserID'], $_POST['date_min'], $_POST['date_max']);
            $chart->query($chart_query);
            // If the query returns a valid response, prepare the JSON string
            if ($result) 
            {
                 // The `$arrData` array holds the chart attributes and data
                $arrData = array(
                    "chart" => array(
                    "caption" => "Incomes and expenses",
                    "paletteColors" => "#0075c2",
                    "bgColor" => "#ffffff",
                    "borderAlpha"=> "20",
                    "canvasBorderAlpha"=> "0",
                    "usePlotGradientColor"=> "0",
                    "plotBorderAlpha"=> "10",
                    "showXAxisLine"=> "1",
                    "xAxisLineColor" => "#999999",
                    "showValues" => "0",
                    "divlineColor" => "#999999",
                    "divLineIsDashed" => "1",
                    "showAlternateHGridColor" => "0"
                    )
                );
                $arrData["data"] = array();
                // Push the data into the array
                while($row = mysqli_fetch_array($result)) 
                {
                    array_push($arrData["data"], array(
                        "label" => $row[0],
                        "value" => $row[1]
                        )
                    );
                }
                /*JSON Encode the data to retrieve the string containing the JSON representation of the data in the array. */

                $jsonEncodedData = json_encode($arrData);

                /*Create an object for the column chart using the FusionCharts PHP class constructor. Syntax for the constructor is ` FusionCharts("type of chart", "unique chart id", width of the chart, height of the chart, "div id to render the chart", "data format", "data source")`. Because we are using JSON data to render the chart, the data format will be `json`. The variable `$jsonEncodeData` holds all the JSON data for the chart, and will be passed as the value for the data source parameter of the constructor.*/

                $columnChart = new FusionCharts("column2D", "myFirstChart" , 600, 300, "chart-1", "json", $jsonEncodedData);

                // Render the chart
                $columnChart->render();
                $chart->close();
            }
        }
    ?>
    </div>
    <?php
        include 'footer.php';
    ?>
</body>
</html>