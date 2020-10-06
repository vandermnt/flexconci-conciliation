@extends('layouts.analytics-master')

@section('title', 'Metrica - Admin & Dashboard Template')

@section('headerStyle')
<link href="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet">
<link href="{{ URL::asset('assets/css/teste.css')}}" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/table-dragger@1.0.3/dist/table-dragger.js"></script>

@stop

@section('content')
<script type="text/javascript">

</script>
<!-- <script>
$(document).ready(function(){
  // teste();
  var teste = 50;

  var options = {
    chart: {
        height: 270,
        type: 'donut',
    },
    plotOptions: {
      pie: {
        donut: {
          size: '85%'
        }
      }
    },
    dataLabels: {
      enabled: false,
    },

    stroke: {
      show: true,
      width: 2,
      colors: ['transparent']
    },

    series: [teste, 70],
    legend: {
        show: true,
        position: 'bottom',
        horizontalAlign: 'center',
        verticalAlign: 'middle',
        floating: false,
        fontSize: '14px',
        offsetX: 0,
        offsetY: 5
    },
    labels: [ "Concluído", "Andamento"],
    colors: ["#ff9f43", "#506ee4"],

    responsive: [{
        breakpoint: 600,
        options: {
          plotOptions: {
              donut: {
                customScale: 0.2
              }
            },
            chart: {
                height: 240
            },
            legend: {
                show: false
            },
        }
    }],

    tooltip: {
      y: {
          formatter: function (val) {
              return   val + " %"
          }
      }
    }

  }});

</script> -->

<div id="tudo_page" class="container-fluid">
  <div class="row">
    <div class="col-sm-12">

      @component('common-components.breadcrumb')
      @slot('title') Projeto @endslot
      @slot('item1') Projetos @endslot
      <!-- @slot('item2') Antecipação de Venda @endslot -->
      @endcomponent

    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <div class="card">
        <div class="card-body">

          <div class="row">
            <div class="col-lg-4">
              <div class="card">
                <div class="card-body">
                  <h4 class="header-title mt-0" style="text-align: center">STATUS DE IMPLANTAÇÃO</h4>
                  <div id="ana_device" class=""></div>
                  <div class="table-responsive mt-4">
                    <!-- <table class="table mb-0">
                      <thead class="thead-light">
                        <tr>
                          <th>Device</th>
                          <th>Sassions</th>
                          <th>Day</th>
                          <th>Week</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th scope="row">Dasktops</th>
                          <td>1843</td>
                          <td>-3</td>
                          <td>-12</td>
                        </tr>
                        <tr>
                          <th scope="row">Tablets</th>
                          <td>2543</td>
                          <td>-5</td>
                          <td>-2</td>
                        </tr>
                        <tr>
                          <th scope="row">Mobiles</th>
                          <td>3654</td>
                          <td>-5</td>
                          <td>-6</td>
                        </tr>

                      </tbody>
                    </table> -->
                  </div>
                </div><!--end card-body-->
              </div><!--end card-->
            </div><!--end col-->

            <div class="col-lg-8" style="margin-top: 30px">
              <table class="table mb-0">
                <thead class="">
                  <tr style="background: #2D5275;">
                    <th style="color: white">TIPO PROJETO</th>
                    <th style="color: white">DATA INICIAL</th>
                    <th style="color: white">DATA PRAZO</th>
                    <th style="color: white">% CONCLUÍDO</th>
                    <!-- <th>DESCRIÇÃO DO PROJETO</th> -->

                  </tr>
                </thead>
                <tbody>
                  <!-- @foreach($p as $p) -->
                  <tr>
                    <td>{{$p->TIPO_PROJETO}}</td>
                    <td>{{$p->DATA_INICIAL}}</td>
                    <td>{{$p->DATA_FINAL}}</td>
                    <td id="fase_conclusao">{{$p->FASE_CONCLUSAO}}</td>
                  </tr>
                  <!-- @endforeach -->

                  <!-- <tr>
                    <td>TESTE</td>
                    <td>TESTE</td>
                    <td>TESTE</td>
                    <td>TESTE</td>
                    <td>TESTE</td>
                  </tr>
                  <tr>
                    <td>1843</td>
                    <td>3654</td>
                    <td>-5</td>
                    <td>-6</td>
                    <td>-12</td>
                  </tr> -->

                </tbody>
              </table>
              <br><br>
              <h5> Detalhamento do Projeto: </h5>
              <table class="table mb-0">
                <thead class="">
                  <tr style="background: #2D5275;">
                    <th style="color: white">ADQUIRENTE</th>
                    <th style="color: white">STATUS</th>
                    <th style="color: white">DETALHAMENTO DA TAREFA</th>
                    <th style="color: white">ULTIMA ATUALIZAÇÃO</th>
                    <!-- <th>DESCRIÇÃO DO PROJETO</th> -->

                  </tr>
                </thead>
                <tbody>
                  @foreach($projeto as $projeto)
                  <tr>
                    <td>{{$projeto->ADQUIRENTE}}</td>
                    <td>{{$projeto->STATUS_HOMOLOGADO}}</td>
                    <td>{{$projeto->DESCRICAO_TAREFA}}</td>
                    <td>{{$projeto->ULTIMA_ATUALIZACAO }}</td>
                  </tr>
                  @endforeach

                  <!-- <tr>
                    <td>TESTE</td>
                    <td>TESTE</td>
                    <td>TESTE</td>
                    <td>TESTE</td>
                    <td>TESTE</td>
                  </tr>
                  <tr>
                    <td>1843</td>
                    <td>3654</td>
                    <td>-5</td>
                    <td>-6</td>
                    <td>-12</td>
                  </tr> -->

                </tbody>
              </table>
            </div>


          </div>
          <div class="col-sm-12">
            <a href="{{ url('/lista-projetos') }}" style="text-align: center; justify-content: center; color: white; background: #2D5275;" class="btn btn-sm"> <i class="fas fa-arrow-left"></i> <b>VISUALIZAR TODOS PROJETOS</b>  </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">

  </div>


</div><!--end card-body-->

@section('footerScript')
<script src="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ URL::asset('plugins/jvectormap/jquery-jvectormap-us-aea-en.js') }}"></script>
<script>
 /**
  * Theme: Metrica - Responsive Bootstrap 4 Admin Dashboard
  * Author: Mannatthemes
  * Dashboard Js
  */

  var fase_conclusao = null;

  // $(document).ready(function(){
   fase_conclusao = document.getElementById("fase_conclusao").innerHTML;
   // var restante = 50;
   faseconclusao = Math.round(fase_conclusao);

   restante = 100 - faseconclusao;

   // var value = '100' - fase_conclusao;

      // });

   //colunm-1

   var options = {
     chart: {
         height: 340,
         type: 'bar',
         toolbar: {
             show: false
         }
     },
     plotOptions: {
         bar: {
             horizontal: false,
             endingShape: 'rounded',
             columnWidth: '25%',
         },
     },
     dataLabels: {
         enabled: false
     },
     stroke: {
         show: true,
         width: 2,
         colors: ['transparent']
     },
     colors: ["#1ccab8", '#2a76f4'],
     series: [{
         name: 'New Visitors',
         data: [68, 44, 55, 57, 56, 61, 58, 63, 60, 66]
     }, {
         name: 'Unique Visitors',
         data: [51, 76, 85, 101, 98, 87, 105, 91, 114, 94]
     },],
     xaxis: {
         categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
         axisBorder: {
           show: true,
           color: '#bec7e0',
         },
         axisTicks: {
           show: true,
           color: '#bec7e0',
         },
     },
     legend: {
       offsetY: 6,
     },
     yaxis: {
         title: {
             text: 'Visitors',
         },
     },
     fill: {
         opacity: 1

     },
     // legend: {
     //     floating: true
     // },
     grid: {
         row: {
             colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
             opacity: 0.2,
         },
         strokeDashArray: 4,
     },
     tooltip: {
         y: {
             formatter: function (val) {
                 return "" + val + "k"
             }
         }
     }
   }

   var chart = new ApexCharts(
     document.querySelector("#ana_dash_1"),
     options
   );

   chart.render();


   // traffice chart


   var optionsCircle = {
       chart: {
         type: 'radialBar',
         height: 280,
         offsetY: -30,
         offsetX: 20
       },
       plotOptions: {
         radialBar: {
           inverseOrder: true,
           hollow: {
             margin: 5,
             size: '55%',
             background: 'transparent',
           },
           track: {
             show: true,
             background: '#ddd',
             strokeWidth: '10%',
             opacity: 1,
             margin: 5, // margin is in pixels
           },

           dataLabels: {
             name: {
                 fontSize: '18px',
             },
             value: {
                 fontSize: '16px',
                 color: '#50649c',
             },

           }
         },
       },
       series: [71, 63],
       labels: ['Domestic', 'International'],
       legend: {
         show: true,
         position: 'bottom',
         offsetX: -40,
         offsetY: -5,
         formatter: function (val, opts) {
           return val + " - " + opts.w.globals.series[opts.seriesIndex] + '%'
         }
       },
       fill: {
         type: 'gradient',
         gradient: {
           shade: 'dark',
           type: 'horizontal',
           shadeIntensity: 0.5,
           inverseColors: true,
           opacityFrom: 1,
           opacityTo: 1,
           stops: [0, 100],
           gradientToColors: ['#40e0d0', '#ff8c00', '#ff0080']
         }
       },
       stroke: {
         lineCap: 'round'
       },
     }

     var chartCircle = new ApexCharts(document.querySelector('#circlechart'), optionsCircle);
     chartCircle.render();



     var iteration = 11

     function getRandom() {
       var i = iteration;
       return (Math.sin(i / trigoStrength) * (i / trigoStrength) + i / trigoStrength + 1) * (trigoStrength * 2)
     }

     function getRangeRandom(yrange) {
       return Math.floor(Math.random() * (yrange.max - yrange.min + 1)) + yrange.min
     }

     window.setInterval(function () {

       iteration++;

       chartCircle.updateSeries([getRangeRandom({ min: 10, max: 100 }), getRangeRandom({ min: 10, max: 100 })])


     }, 3000)



   //Device-widget

   console.log(restante);
   var options = {
     chart: {
         height: 270,
         type: 'donut',
     },
     plotOptions: {
       pie: {
         donut: {
           size: '85%'
         }
       }
     },
     dataLabels: {
       enabled: false,
     },

     stroke: {
       show: true,
       width: 2,
       colors: ['transparent']
     },

     series: [faseconclusao, restante],
     legend: {
         show: true,
         position: 'bottom',
         horizontalAlign: 'center',
         verticalAlign: 'middle',
         floating: false,
         fontSize: '14px',
         offsetX: 0,
         offsetY: 5
     },
     labels: [ "Concluído", "Pendente"],
     colors: ["#ff9f43", "#506ee4"],

     responsive: [{
         breakpoint: 600,
         options: {
           plotOptions: {
               donut: {
                 customScale: 0.2
               }
             },
             chart: {
                 height: 240
             },
             legend: {
                 show: false
             },
         }
     }],

     tooltip: {
       y: {
           formatter: function (val) {
               return   val + " %"
           }
       }
     }

   }

   var chart = new ApexCharts(
     document.querySelector("#ana_device"),
     options
   );

   chart.render();


   // map

 $('#usa').vectorMap({
   map: 'us_aea_en',
   backgroundColor: 'transparent',
   borderColor: '#818181',
   regionStyle: {
     initial: {
       fill: '#506ee424',
     }
   },
   series: {
     regions: [{
         values: {
             "US-VA": '#506ee452',
             "US-PA": '#506ee452',
             "US-TN": '#506ee452',
             "US-WY": '#506ee452',
             "US-WA": '#506ee452',
             "US-TX": '#506ee452',
         },
         attribute: 'fill',
     }]
   },
 });

</script>
@stop
@stop
