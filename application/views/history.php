<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="assets/ico/favicon.png">

    <title>DUMBOBAY - shopping area</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/dumbobaycss.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/css/main.css" rel="stylesheet">


    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <style>
      li.watchlist{
        margin-top: 30px;
        margin-bottom: 30px;
      }
      
    </style>
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#" style="padding:2px 15px 2px 15px"><img src="assets/img/dumbobay-logo-sm.png"></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">

           <li><a href="#">HOME</a></li>
           <li><a href="#">PRODUCT</a></li>
           <li><a href="#">MY CART</a></li>
           <li><a href="#">WATCH LIST</a></li>
           <li><a href="#">ORDER</a></li>
           <li><a href="#">MSG BOX</a></li>
           <li class="active"><a href="#">HISTORY</a></li>
           <!-- <li><a data-toggle="modal" data-target="#myModal" href="#myModal"><i class="fa fa-envelope-o"></i></a></li> -->
            <li><a href="index.html">SIGN OUT</a></li>
            <li><a href="#">| Pookpik |</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div id="myheaderwrap">
      <div class="container">
        <div class="row centered">
            <h2>Want it that much ? Then <b>PAY</b> more. <b>-DumboBay-</b></h2> 
        </div><!-- row -->
      </div><!-- container -->
    </div><!-- headerwrap -->
    <div>
      <div class="container">
        <div>
          <div class="col-md-12" style="padding-top:50px; padding-bottom:25px">
            <div class="row">
              <div class="col-md-2" style="padding-left:20px">
                <img src="assets/img/history.png" width="130" height="100">
              </div>
              <div class="col-md-2">
                <h1 style="color:black;padding-top:13px">History</h1>
              </div>
              <div class="col-md-2">
              </div>
              <div class="col-md-5" style="padding-top:55px;padding-left:20px">
                <div class="col-md-10">
                  <input type="text" class="form-control" id="inputDirectSaleSearch" placeholder="Search...">
                </div>
                <div class="col-md-2" style="padding-left:0px">
                  <button type="button" class="btn btn-default">Search</button>
                </div>
              </div>
              <div class="col-md-1" style="padding-top:70px;padding-left:20px">
                items: 9
              </div>
            </div>
          </div>

          <div>
            <?php for($y=0; $y <= 2; $y++){
              echo '<div class="row">';
              for ($x = 0; $x <= 2; $x++){
              echo'

                <div class="col-md-4">
                  <div class="thumbnail">
                    <img src="assets/img/iphone6.jpg" >
                    <div class="caption">
                      <h3>iPhone6</h3>
                      <strong>Seller : </strong>Steve2345
                      <br>
                      <a href="#">more info...</a>
                      <br>
                      <br>
                      <div class="row" style="padding-top:0px;padding-left:20px">
                        Rate : 
                        <div class="starRating">
                          <div>
                            <div>
                              <div>
                                <div>
                                  <input id="rating1" type="radio" name="rating" value="1">
                                  <label for="rating1"><span>1</span></label>
                                </div>
                                <input id="rating2" type="radio" name="rating" value="2">
                                <label for="rating2"><span>2</span></label>
                              </div>
                              <input id="rating3" type="radio" name="rating" value="3">
                              <label for="rating3"><span>3</span></label>
                            </div>
                            <input id="rating4" type="radio" name="rating" value="4">
                            <label for="rating4"><span>4</span></label>
                          </div>
                          <input id="rating5" type="radio" name="rating" value="5">
                          <label for="rating5"><span>5</span></label>
                        </div>

                      </div>

                      <div class="row" style="padding-top:20px">
                        <div class="col-md-12">
                          <textarea class="form-control" rows="3" id="textArea" placeholder="your review..."></textarea>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
                ';
              }
              echo '</div>';
            }
            ?>
          </div>
        </div>
      </div>
    </div>
    <!-- FOOTER -->
    <div id="f" style="margin-top:50px">
      <div class="container">
        <div class="row">
        <div class="col-md-6 col-md-push-2">
          <img src="assets/img/DumboBay-Logo-b.png">
        </div>
        <div class="col-md-6 col-md-push-2">
          <img src="assets/img/Frappe-Logo-bg-sm.png">
        </div>
        </div><!-- row -->
      </div><!-- container -->
    </div><!-- Footer -->


  


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
  </body>
</html>