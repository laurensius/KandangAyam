                <div class="page-content-wrapper">
                    <div class="page-content">
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <span>Monitoring</span>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Sensor</span>
                                </li>
                            </ul>
                        </div>
                        <h1 class="page-title"> Monitoring
                            <small>Sensor DHT11, Lampu dan Kipas</small>
                        </h1>
                        <div class="container-fluid">
                            <div class="row">
                                <!-- div class="col-lg-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="panel-title">Mode Kontrol Lampu dan Kipas </div>
                                        </div>
                                        <div class="panel-body">
                                            <span id="informasi"></span>
                                            <span id="mode_kontrol"></span>
                                        </div>
                                    </div>
                                </div-->
                                <div class="col-lg-3">
                                     <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="panel-title">Nilai Suhu</div>
                                        </div>
                                        <div class="panel-body">
                                            <center>
                                                <img src="<?php echo base_url(); ?>assets/img/temperature_icon.png"><br>
                                                <span style="font-size:70px;" id="nilai_suhu">0</span>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                     <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="panel-title">Nilai Kelembaban</div>
                                        </div>
                                        <div class="panel-body">
                                            <center>
                                                <img src="<?php echo base_url(); ?>assets/img/humidity_icon.png"><br>
                                                <span style="font-size:70px;" id="nilai_kelembaban">0</span>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                     <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                Status Lampu
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <center>
                                                <span style="font-size:72px;" id="status_lampu">-</span><p>
                                                <span id="saklar_lampu"></span>
                                            </center>  
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                     <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="panel-title">Status Kipas</div>
                                        </div>
                                        <div class="panel-body">
                                            <center>
                                                <span style="font-size:72px;" id="status_kipas">-</span><p>
                                                <span id="saklar_kipas"></span>
                                            </center>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                                    <ins class="adsbygoogle"
                                         style="display:block; text-align:center;"
                                         data-ad-format="fluid"
                                         data-ad-layout="in-article"
                                         data-ad-client="ca-pub-3588177853851357"
                                         data-ad-slot="2338369229"></ins>
                                    <script>
                                         (adsbygoogle = window.adsbygoogle || []).push({});
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="<?php echo base_url();?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
                <script src="<?php echo base_url();?>assets/global/scripts/highcharts.js"></script>
                <script type="text/javascript">
                    var str_lampu_on = '<center><img src="<?php echo base_url(); ?>assets/img/ON_BULB.png"></center>';
                    var str_lampu_off = '<center><img src="<?php echo base_url(); ?>assets/img/OFF_BULB.png"></center>';
                    var str_kipas_on = '<center><img src="<?php echo base_url(); ?>assets/img/ON_FAN.png"></center>';
                    var str_kipas_off = '<center><img src="<?php echo base_url(); ?>assets/img/OFF_FAN.png"></center>';
                    var saklar_lampu_on = '<center><img onClick="saklar(\'lampu\',false);" class="img img-responsive" src="<?php echo base_url(); ?>assets/img/saklar_off.png"></center>';
                    var saklar_lampu_off = '<center><img onClick="saklar(\'lampu\',true);" class="img img-responsive" src="<?php echo base_url(); ?>assets/img/saklar_on.png"></center>';
                    var saklar_kipas_on = '<center><img onClick="saklar(\'kipas\',false);" class="img img-responsive" src="<?php echo base_url(); ?>assets/img/saklar_off.png"></center>';
                    var saklar_kipas_off = '<center><img onClick="saklar(\'kipas\',true);" class="img img-responsive" src="<?php echo base_url(); ?>assets/img/saklar_on.png"></center>';
                    var str_mode_label = '';

                    function refresh_data(){
                        $.ajax({
                          url : "<?php echo site_url(); ?>/api/dataset/",
                          type : "GET",
                          cache: false,
                          dataType : "json",
                          async : true,
                          success : function(response){
                            console.log(length);
                            $("#nilai_suhu").html(response.dataset.last_data[0].suhu);
                            $("#nilai_kelembaban").html(response.dataset.last_data[0].kelembaban);
                            
                            if(response.dataset.current_state[0].lampu==='1')
                                $("#status_lampu").html(str_lampu_on);
                            else
                                $("#status_lampu").html(str_lampu_off);

                            if(response.dataset.current_state[0].kipas==='1')
                                $("#status_kipas").html(str_kipas_on);
                            else
                                $("#status_kipas").html(str_kipas_off);   
                            
                            if(response.dataset.current_state[0].lampu == "1"){
                                state_lampu = true;
                            }else
                            if(response.dataset.current_state[0].lampu == "0"){
                                state_lampu = false;
                            }

                            if(response.dataset.current_state[0].kipas == "1"){
                                state_kipas = true;
                            }else
                            if(response.dataset.current_state[0].kipas == "0"){
                                state_kipas = false;
                            } 

                            if(response.dataset.current_state[0].mode==='OTOMATIS'){
                                str_mode_label = 'Mode kontrol saat ini : <b>OTOMATIS</b><p> <button class="btn btn-primary form-control" onClick="changeMode(\'MANUAL\')">Ubah ke Mode MANUAL</button>';
                                $("#saklar_lampu").html(""); 
                                $("#saklar_kipas").html(""); 
                            }else
                            if(response.dataset.current_state[0].mode==='MANUAL'){
                                str_mode_label = 'Mode kontrol saat ini : <b>MANUAL</b> <p> <button class="btn btn-success form-control" onClick="changeMode(\'OTOMATIS\')">Ubah ke Mode OTOMATIS</button>';
                                if(state_lampu){
                                    $("#saklar_lampu").html(saklar_lampu_on); 
                                }else{
                                    $("#saklar_lampu").html(saklar_lampu_off); 
                                }
                                if(state_kipas){
                                    $("#saklar_kipas").html(saklar_kipas_on); 
                                }else{
                                    $("#saklar_kipas").html(saklar_kipas_off); 
                                }
                            }
                            $("#mode_kontrol").html(str_mode_label);      
                            
                            var categories = new Array();
                            var data_suhu = new Array();
                            for(var i=0;i<response.dataset.last_data.length;i++){
                                if(i==0){
                                    $("#informasi").html("Data terakhir diterima oleh server pada pukul : <b>" + response.dataset.last_data[0].datetime + "</b></p>"); 
                                }
                                categories[i] = response.dataset.last_data[i].datetime;
                                data_suhu[i] = parseFloat(response.dataset.last_data[i].suhu);
                            }                                         
                          }
                        });
                    }

                    function changeMode(set_to){
                        alert(set_to);
                        $.ajax({
                            url : "<?php echo site_url(); ?>/api/change_mode/"+set_to+"/",
                            type : "GET",
                            cache: false,
                            async : true,
                            success : function(response){
                                alert("OK");
                            }
                        });
                    }

                    function saklar(perangkat,set_nilai){
                        var ubah_state_ke;
                        if(set_nilai){
                            ubah_state_ke = "SET_ON";
                        }else{
                            ubah_state_ke = "SET_OFF";
                        }
                        var data_update = {"device" : perangkat,"ubah_state_ke" : ubah_state_ke,"user_agent" : "WEB APPS"};
                        $.ajax({
                            url : "<?php echo site_url(); ?>/api/update_state/",
                            type : "POST",
                            cache: false,
                            data : data_update,
                            contentType : "application/x-www-form-urlencoded",
                            dataType : "json",
                            success : function(response){
                                console.log("Update state OK");
                            }
                        });
                    }

                    setInterval(function(){refresh_data();},2000);

                </script>
            