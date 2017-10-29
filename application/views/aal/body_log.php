                <div class="page-content-wrapper">
                    <div class="page-content">
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <span>Monitoring</span>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>System Log</span>
                                </li>
                            </ul>
                        </div>
                        <h1 class="page-title"> System Log
                            <small>Log state lampu dan kipas</small>
                        </h1>
                        <div class="container-fluid">
                            <center>
                                <strong>
                                    <h4>Tabel Log Kondisi <i>End Device</i></h4>
                                </strong>
                            </center>
                            <br>
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Waktu</th>
                                        <th>Perangkat</th>
                                        <th>Aktivitas</th>
                                        <th>User Agent</th>
                                    </tr>
                                </thead>
                                <tbody id="body_log">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    function refresh_data(){
                        var str_tabel = '';
                        $.ajax({
                            url : "<?php echo site_url(); ?>/api/dataset/",
                            type : "GET",
                            dataType : "json",
                            success : function(response){
                                if(response.dataset.today_log.length > 0){
                                    var ctr = 1;
                                    for(var i=0;i<response.dataset.today_log.length;i++){
                                        str_tabel += '<tr>';
                                        str_tabel += '<td>' + ctr + '</td>';
                                        str_tabel += '<td>' + response.dataset.today_log[i].datetime + '</td>';
                                        str_tabel += '<td>' + response.dataset.today_log[i].device + '</td>';
                                        str_tabel += '<td>' + response.dataset.today_log[i].activity + '</td>';
                                        str_tabel += '<td>' + response.dataset.today_log[i].user_agent + '</td>';
                                        str_tabel += '</tr>';
                                        ctr++;
                                    }
                                }else{
                                    str_tabel += '<tr>';
                                    str_tabel += '<td colspan="5">Tidak ada log tersimpan hari ini.</td>';
                                    str_tabel += '</tr>';
                                }
                                $("#body_log").html(str_tabel);
                            }
                        });
                    }
                    setInterval(function(){refresh_data();},1000);
                </script>
            