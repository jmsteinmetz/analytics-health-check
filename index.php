<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>b:Reporting</title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="/css/main.css">
    <link href="//cdnjs.cloudflare.com/ajax/libs/c3/0.1.29/c3.css" rel="stylesheet" type="text/css">
    <!-- JQuery -->
    <script src="/js/jquery-2.1.4.min.js"></script>
    <!-- Bootstrap -->
    <script src="/js/bootstrap.min.js"></script>
    <!-- Charts (C3.js) -->
    <script src="/js/d3.min.js"></script>
    <script src="/js/c3-0.4.10/c3.min.js"></script>
    <!-- Load page code -->
    <script src="/js/bvpixel.js"></script>
    <script src="/js/load.js"></script>
    <!-- Test to PDF -->
    <script type="text/javascript" src="/js/html2canvas-0.4.1/build/html2canvas.js"></script>
    <script type="text/javascript" src="/js/canvas2image.js"></script>
    <script type="text/javascript" src="/js/canvas-toBlob.js"></script>
    <script type="text/javascript" src="/js/jspdf.debug.js"></script>
    <style>
    .exists0 {color: #ff0000;}
    /* Popover Body */
    .popover-content {
        padding: 0 20px;
    }
    .item-columns span {
        min-width: 100px;
    }
    .panel {
        font-size: 90%
    }
    </style>
</head>

<body>
    <div id="layout">
        <div class="header">
            <div class="container">
                <div class="col-sm-12 thelogo hidden-xs">
                    <img class="logo" src="/img/logo.png">
                </div>
            </div>
        </div>
        <div class="container">
            <!-- Titlebar -->
            <div class="col-sm-12 titlebar">
                <span class="title">BV Pixel Health Scorecard <span class="smallerDate" id='showDate'> -- </span></span>
                <span class="pull-right"><a class="download" id="btn-download" href="#">Download PDF</a></span>
                <div class="col-sm-12 reportdetails" style="display:none">
                    <div class="col-sm-2 caption removeleftpadding">List
                        <br>
                        <select class="form-control">
                            <option>IR 100 - United States</option>
                            <option>IR 100 - Europe</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- End Titlebar -->
            <!-- Blocked Content Areas -->
            <div class="col-sm-12 content first">
                <div class="panel panel-default">
                    <div class="panel-heading">Health Report : <span class="country"></span></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="col-sm-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Filters</h3>
                                        </div>
                                        <div class="panel-body">
                                            <select id="filterDate">
                                                <option>Select Date</option>
                                            </select>
                                            <select id="filterCountry">
                                                <option>Select Country</option>
                                                <option value="us">United States</option>
                                                <option value="uk">United Kingdom</option>
                                                <option value="germany">Germany</option>
                                                <option value="france">France</option>
                                            </select>
                                            <a id="filterAction" class="btn btn-default" href="#" role="button">View</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <ul class="list-group">
                                        <li class="list-group-item"><b>Monthly Unique Visitors</b>
                                            <span class="pull-right totalActualUnique">0</span></li>
                                        <li class="list-group-item list-group-item-warning">EOY Target
                                            <span class="pull-right totalUniqueTarget">0</span></li>
                                        <li class="list-group-item list-group-item">% of Target
                                            <span class="pull-right totalUniqueTargetGoal">0%</span></li>
                                    </ul>
                                </div>
                                <div class="col-sm-12" style="display:none">
                                    <ul class="list-group">
                                        <li class="list-group-item"><b>Number of Clients</b>
                                            <span class="pull-right totalBVClients">0</span></li>
                                        <li class="list-group-item list-group-item-warning">EOY Target
                                            <span class="pull-right totalClientTarget">0</span></li>
                                        <li class="list-group-item list-group-item">% of Target
                                            <span class="pull-right totalClientGoal"></span></li>
                                    </ul>
                                </div>
                                <div class="col-sm-12">
                                    <ul class="list-group">
                                        <li class="list-group-item"><b>Network Value</b>
                                            <span class="pull-right totalNetworkPercent">0</span></li>
                                        <li class="list-group-item list-group-item-warning">EOY Target
                                            <span class="pull-right networkTarget">0</span></li>
                                        <li class="list-group-item list-group-item">% of Target
                                            <span class="pull-right totalNetworkGoal">0%</span></li>
                                    </ul>
                                </div>
                                <div class="col-sm-12">
                                    <ul class="list-group">
                                        <li class="list-group-item"><b>Global Conversion Coverage</b></li>
                                        <li class="list-group-item ">BV Pixel Sites
                                            <span class="pull-right eventTotalClients">0</span></li>
                                            <li class="list-group-item "><a href='http://ucs.us-east-1.nexus.bazaarvoice.com/model/api/bvsites.txt?sitetag=edr' target='_blank'>EDR Sites</a>
                                            <span class="pull-right eventTotalEDR">--</span></li>
                                        <li class="list-group-item list-group-item">Currency
                                            <span class="pull-right eventCurrency">0%</span></li>
                                        <li class="list-group-item list-group-item">Quantity
                                            <span class="pull-right eventQuantity">0%</span></li>
                                        <li class="list-group-item list-group-item">Price
                                            <span class="pull-right eventPrice">0%</span></li>
                                        <li class="list-group-item list-group-item">Order Total
                                            <span class="pull-right eventOrderTotal">0%</span></li>
                                        <li class="list-group-item list-group-item">Product Name
                                            <span class="pull-right eventProductName">0%</span></li>
                                        <li class="list-group-item list-group-item">Product ID
                                            <span class="pull-right eventProductID">0%</span></li>
                                        <li class="list-group-item list-group-item">Product ID Match
                                            <span class="pull-right eventProductIDMatch">0%</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="panel panel-default">
                                    <!-- Default panel contents -->
                                    <div class="panel-heading">Top Targets (<span class="edr">EDR clients in RED</span>)</div>
                                    <!-- List group -->
                                    <ul id="top100" class="list-group">
                                        <li class="list-group-item itemheader">
                                            <span>Company</span>
                                            <div class="item-columns pull-right">
                                                <span class="left-align">Unique Visitors</span>
                                                <span>Mobile</span>
                                                <span>Desktop</span>
                                                <span>Composite</span>
                                                <span>Network Value</span>
                                                <span>Potential Value</span>
                                            </div>
                                        </li>
                                        <!-- INSERT DATA FROM DATABASE -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="container footer">
                <div class="col-sm-12"><span id="copyrightText" class="pull-right"></span></div>
            </div>
        </div>
        <!-- /container -->
        <!-- Placed at the end of the document so the pages load faster -->
    </div>
    <script type="text/javascript">
    $(".download").click(function() {
        //printPage();

        $("label").css("font-family", "helvetica");

        var pdf = new jsPDF('p', 'pt', 'a4');

        pdf.addHTML(document.body, function() {
            pdf.save("test.pdf");
        });

    });
    </script>
</body>

</html>
