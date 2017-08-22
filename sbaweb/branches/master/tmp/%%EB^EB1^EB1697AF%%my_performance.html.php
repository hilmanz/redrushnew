<?php /* Smarty version 2.6.13, created on 2012-04-12 08:29:32
         compiled from Social/widgets/my_performance.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'addslashes', 'Social/widgets/my_performance.html', 8, false),)), $this); ?>

                <div class="content">
                	<script src="js/json2.js" type="text/javascript"></script>
					<script src="../js/jquery-1.5.1.min.js" type="text/javascript"></script>
					<script src="js/charts/highcharts.js" type="text/javascript"></script>
					<div id="chart01"></div>
					<script>
var _data = JSON.parse("<?php echo ((is_array($_tmp=$this->_tpl_vars['data'])) ? $this->_run_mod_handler('addslashes', true, $_tmp) : addslashes($_tmp)); ?>
");


<?php echo '

	for(var j=0;j<_data.length;j++){
		_data[j][0] = new Date(parseInt(_data[j][0])*1000).getTime() + (24*1000*3600);
		
	}
'; ?>

</script>
<?php echo '
<script type="text/javascript">
Highcharts.theme = {
		   colors: ["#DDDF0D", "#7798BF", "#55BF3B", "#DF5353", "#aaeeee", "#ff0066", "#eeaaee", 
		      "#55BF3B", "#DF5353", "#7798BF", "#aaeeee"],
		   chart: {
		      backgroundColor: {
		         linearGradient: [0, 0, 0, 400],
		         stops: [
		            [0, \'rgb(96, 96, 96)\'],
		            [1, \'rgb(16, 16, 16)\']
		         ]
		      },
		      borderWidth: 0,
		      borderRadius: 15,
		      plotBackgroundColor: null,
		      plotShadow: false,
		      plotBorderWidth: 0
		   },
		   title: {
		      style: { 
		         color: \'#FFF\',
		         font: \'16px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif\'
		      }
		   },
		   subtitle: {
		      style: { 
		         color: \'#DDD\',
		         font: \'12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif\'
		      }
		   },
		   xAxis: {
		      gridLineWidth: 0,
		      lineColor: \'#999\',
		      tickColor: \'#999\',
		      labels: {
		         style: {
		            color: \'#999\',
		            fontWeight: \'bold\'
		         }
		      },
		      title: {
		         style: {
		            color: \'#AAA\',
		            font: \'bold 12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif\'
		         }            
		      }
		   },
		   yAxis: {
		      alternateGridColor: null,
		      minorTickInterval: null,
		      gridLineColor: \'rgba(255, 255, 255, .1)\',
		      lineWidth: 0,
		      tickWidth: 0,
		      labels: {
		         style: {
		            color: \'#999\',
		            fontWeight: \'bold\'
		         }
		      },
		      title: {
		         style: {
		            color: \'#AAA\',
		            font: \'bold 12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif\'
		         }            
		      }
		   },
		   legend: {
		      itemStyle: {
		         color: \'#CCC\'
		      },
		      itemHoverStyle: {
		         color: \'#FFF\'
		      },
		      itemHiddenStyle: {
		         color: \'#333\'
		      }
		   },
		   labels: {
		      style: {
		         color: \'#CCC\'
		      }
		   },
		   tooltip: {
		      backgroundColor: {
		         linearGradient: [0, 0, 0, 50],
		         stops: [
		            [0, \'rgba(96, 96, 96, .8)\'],
		            [1, \'rgba(16, 16, 16, .8)\']
		         ]
		      },
		      borderWidth: 0,
		      style: {
		         color: \'#FFF\'
		      }
		   },
		   
		   
		   plotOptions: {
		      line: {
		         dataLabels: {
		            color: \'#CCC\'
		         },
		         marker: {
		            lineColor: \'#333\'
		         }
		      },
		      spline: {
		         marker: {
		            lineColor: \'#333\'
		         }
		      },
		      area: {
		            fillColor: {
			               linearGradient: [0, 0, 0, 300],
			               stops: [
			                  [0, \'#e5e5e5\'],
			                  [1, \'rgba(2,2,0,0)\']
			               ]
			            },
			            lineWidth: 1,
			            marker: {
			               enabled: false,
			               states: {
			                  hover: {
			                     enabled: true,
			                     radius: 5
			                  }
			               }
			            },
			            shadow: false,
			            states: {
			               hover: {
			                  lineWidth: 1                  
			               }
			            }
			         },
		      scatter: {
		         marker: {
		            lineColor: \'#333\'
		         }
		      },pie: {
			         dataLabels: {
				            color: \'#fff\'
				         }
				      }
		   },
		   
		   toolbar: {
		      itemStyle: {
		         color: \'#CCC\'
		      }
		   },
		   
		   navigation: {
		      buttonOptions: {
		         backgroundColor: {
		            linearGradient: [0, 0, 0, 20],
		            stops: [
		               [0.4, \'#606060\'],
		               [0.6, \'#333333\']
		            ]
		         },
		         borderColor: \'#000000\',
		         symbolStroke: \'#C0C0C0\',
		         hoverSymbolStroke: \'#FFFFFF\'
		      }
		   },
		   
		   exporting: {
		      buttons: {
		         exportButton: {
		            symbolFill: \'#55BE3B\'
		         },
		         printButton: {
		            symbolFill: \'#7797BE\'
		         }
		      }
		   },   
		   
		   // special colors for some of the demo examples
		   legendBackgroundColor: \'rgba(48, 48, 48, 0.8)\',
		   legendBackgroundColorSolid: \'rgb(70, 70, 70)\',
		   dataLabelsColor: \'#444\',
		   textColor: \'#E0E0E0\',
		   maskColor: \'rgba(255,255,255,0.3)\'
		};

		// Apply the theme
		var highchartsOptions = Highcharts.setOptions(Highcharts.theme);


var chart1; // globally available

$(document).ready(function() {
      chart1 = new Highcharts.Chart({
         chart: {
            renderTo: \'chart01\',
            defaultSeriesType: \'area\',
            height:200
         },
         title: {
            text: \'Performance\'
         },
         xAxis: {
        	 type: \'datetime\',
        	 tickInterval:  24 * 3600 * 1000*15,
             dateTimeLabelFormats: { // don\'t display the dummy year
                 month: \'\',
                 year: \'\'
              }
         },
         yAxis: {
            title: {
               text: \'Registration Performance\'
            }
         },
         plotOptions:{
        	 line:{lineWidth:\'1px\'}
         },
         credits:{enabled:false},
         series: [{
            name: \'Daily Data\',
            data: _data
         }]
      });
   });

</script>
'; ?>

                </div>