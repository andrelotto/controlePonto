<?php
  require_once('config/database.php');
  $data = $_GET['data'];
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Controle de Horas</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="assets/css/sb-admin-2.css" rel="stylesheet">
    <link href="assets/font-awesome-4.1.0/assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.assets/js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a class="navbar-brand" >Controle de Horas v1.0</a>
            </div>
            

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a class="active" href="index.php"><i class="fa fa-dashboard fa-fw"></i> Resumo</a>
                        </li>
                        <li>
                            <a href="cadastro.php"><i class="fa fa-edit fa-fw"></i> Cadastro</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Relatório detalhado: <?php echo date("d/m/Y", strtotime(str_replace('>', '', $data))) ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <?php
                /**
                 * Box de cálculo de tempo
                 */

                $tempo = ORM::for_table('controlehoras')
                          ->raw_query("
                            SELECT  
                              SEC_TO_TIME( SUM( TIME_TO_SEC( `tempoAtiv` ) ) ) AS total_time, '08:48:00' AS total  
                            FROM controlehoras
                            WHERE dataAtiv = :data
                            AND tipoAtiv = 'Saida'", array('data' => str_replace('>', '', $data)))->find_one();


                $tempoFaltante = ORM::for_table('controlehoras')
                                 ->raw_query('SELECT TIMEDIFF( "'.$tempo->total.'","'.$tempo->total_time.'") as tempo')->find_one();

                $tempoAlmoco = ORM::for_table('controlehoras')
                               ->where(array(
                                    'dataAtiv' => $data,
                                    'descAtiv' => 'Almoço'))
                               ->find_one();


                $tempoSaidas = ORM::for_table('controlehoras')
                               ->raw_query(" SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(`tempoAtiv`))) AS total_saida, '08:48:00' AS totalNecessario, 
                                            TIMEDIFF(SEC_TO_TIME(SUM(TIME_TO_SEC(`tempoAtiv`))),'08:48:00') AS tempoFaltante
                                            FROM controlehoras
                                            WHERE dataAtiv = '".$data."' AND descAtiv = 'Cigarro'

                                ")->find_one();
    
            ?>
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $tempo->total; ?></div>
                                    <div>Horas Úteis</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-tasks fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $tempo->total_time; ?></div>
                                    <div>Horas Utilizadas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-shopping-cart fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo date ('H:i',strtotime($tempoFaltante->tempo)); ?></div>
                                    <div>Faltante</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-support fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo date ('H:i',strtotime($tempoAlmoco->tempoAtiv)); ?></div>
                                    <div>Almoço</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-support fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo date ('H:i',strtotime($tempoSaidas->total_saida)); ?></div>
                                    <div>Saídas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Motivo</th>
                                            <th>Data</th>
                                            <th>Hora</th>
                                            <th>Tipo</th>
                                            <th>Tempo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php

                                        $controleHoras = ORM::for_table('controlehoras')->where('dataAtiv',$data)->order_by_asc('horaAtiv')->find_many();

                                        foreach ($controleHoras as $cHoras) {
                                          
                                          echo "<tr class=\"odd gradeX\">
                                                  <td align='center'>".$cHoras->batidaAtiv."</td>
                                                  <td align='center'>".$cHoras->descAtiv."</td>
                                                  <td align='center'>".date("d/m/Y", strtotime($cHoras->dataAtiv))."</td>
                                                  <td align='center'>".$cHoras->horaAtiv."</td>
                                                  <td>".$cHoras->tipoAtiv."</td>
                                                  <td  align='center'>".$cHoras->tempoAtiv."</td>
                                                </tr>";
                                        }

                                    
                                      ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>


        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery Version 1.11.0 -->
    <script src="assets/js/jquery-1.11.0.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="assets/js/plugins/metisMenu/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="assets/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="assets/js/sb-admin-2.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').dataTable();
    });
    </script>

</body>

</html>
