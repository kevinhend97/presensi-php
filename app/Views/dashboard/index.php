<?= $this->extend('layout/index') ?>

<?= $this->section('content') ?>
    <div class="row row-flex">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div id="attendance" style="width:550px; height:400px;"></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>EVENTS (TODAY)</h5>
                    <div id="eventToday">
                        <h4>NOT FOUND</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5>UPCOMING EVENTS</h5>
                    <div id="eventUp">
                        <h4>NOT FOUND</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-flex">
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    <h5>RECENT PRESENCE</h5>
                    <div id="presence">
                        <h4>NOT FOUND</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div id="member" style="width:600px; height:290px;"></div>
                        <table class="table">
                            <tr>
                                <td><h6>MALE</h6></td>
                                <td id="male">null</td>
                            </tr>
                            <tr>
                                <td><h6>FEMALE</h6></td>
                                <td id="female">null</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="card p-2 text-center">
                    <div class="card-body">
                        <img src="<?=base_url('include/img/undraw_Up_to_date.svg')?>" class="mb-4" style="width:80%;height:80%"  alt="">
                        <button type="button" onclick="reload();" class="btn btn-info btn-md btn-block">REFRESH DATA <i class="fas fa-sync-alt"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        <?php date_default_timezone_set('Asia/Jakarta'); ?>
        var today = "<?=date('Y-m-d')?>";

        $(() => {
            load();
            loadPresence();
            chartPresence();
            chartMember();
        });

        const reload = () => {
            Swal.fire({
                icon: 'info',
                title: 'Refreshed',
                text: 'Please Wait ...',
                showConfirmButton: false,
                timer: 800
                })

            load();
            loadPresence();
            chartPresence();
            chartMember();
        }

        const load = () => {
            $.ajax({
                url: "<?= base_url('event/list') ?>",
                type: "GET",
                dataType: "JSON",
                success:function(res)
                {
                    const events = res.kamarApps.results;

                    let filterByToday = _.filter(events, { 'date': today });
                    let html = '';

                    _.forEach(_.take(filterByToday, 3),function(value, key){
                        html += `<div class="card mt-4 shadow-none" style="background-color:#f1f4f6;">`;
                        html +=    `<div class="card-body">`;
                        html +=        `<h5 class="card-title">${value.event_name}</h5>`;
                        html +=        `<p class="card-text text-danger"><i class="fas fa-map-marker-alt"></i> ${value.location} - ${value.date}</p>`;
                        html +=    `</div>`;
                        html += `</div>`;
                    })

                    $('#eventToday').html(html);

                    let filterByUpcoming = _.reject(events, { 'date': today });
                    let htmlUpcoming = '';

                    _.forEach(_.take(filterByUpcoming, 3),function(valueUp, keyUp){
                        htmlUpcoming += `<div class="card mt-4 shadow-none" style="background-color:#f1f4f6;">`;
                        htmlUpcoming +=    `<div class="card-body">`;
                        htmlUpcoming +=        `<h5 class="card-title">${valueUp.event_name}</h5>`;
                        htmlUpcoming +=        `<p class="card-text text-danger"><i class="fas fa-map-marker-alt"></i> ${valueUp.location} - ${valueUp.date}</p>`;
                        htmlUpcoming +=    `</div>`;
                        htmlUpcoming += `</div>`;
                    })

                    $('#eventUp').html(htmlUpcoming);

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    toastr.error("Status: " + textStatus); 
                    toastr.error("Error: " + errorThrown); 
                }  
            })
        }

        const loadPresence = () => {
            $.ajax({
                url : "<?= base_url('attendance/list') ?>",
                type : "GET",
                dataType : "JSON",
                success: function(res)
                {
                    let html = '';

                    _.forEach(_.take(res.kamarApps.results, 6),function(value, key){
                        html += `<div class="card mt-4 shadow-none" style="background-color:#f1f4f6;">`;
                        html +=    `<div class="card-body">`;
                        html +=        `<div class="float-right">`;
                        html +=            `<small class="text-muted">${value.timestamp} WIB</small>`;
                        html +=        `</div>`;
                        html +=        `<h5 class="card-title">${value.name}</h5>`;
                        html +=        `<p class="card-text ${value.status != 'PRESENCE'  ? 'text-danger' : 'text-success'}"><i class="fas fa-sign-in-alt"></i></i> ${value.status} - ${value.event_name}</p>`;
                        html +=    `</div>`;
                        html += `</div>`;
                    })

                    $('#presence').html(html);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    toastr.error("Status: " + textStatus); 
                    toastr.error("Error: " + errorThrown); 
                } 
            })
        }

        const chartMember = () => {
            var chartDomMember = document.getElementById('member');
            var myChartMember = echarts.init(chartDomMember);
            var optionMember;

            $.ajax({
                url : "<?= base_url('users/list') ?>",
                type: "GET",
                dataType: "JSON",
                success:function(res)
                {
                    let male = _.filter(res.kamarApps.results, { 'gender': 'MALE' });
                    let female = _.filter(res.kamarApps.results, { 'gender': 'FEMALE' });
                    
                    optionMember = {
                        title:{
                            show:true,
                            text:'MEMBERS',
                            textStyle:{
                                fontWeight:'bolder'
                            }
                        },
                        tooltip: {
                            trigger: 'item'
                        },
                        series: [
                            {
                                name: 'MEMBER',
                                type: 'pie',
                                radius: ['50%', '67%'],
                                avoidLabelOverlap: false,
                                center: ['25%', '50%'],
                                label: {
                                    show: false,
                                    position: 'center'
                                },
                                labelLine: {
                                    show: false
                                },
                                data: [
                                    {value: male.length, name: 'Male'},
                                    {value: female.length, name: 'Female'},
                                ]
                            }
                        ]
                    };

                    optionMember && myChartMember.setOption(optionMember);
                    $('#male').html(male.length);
                    $('#female').html(female.length);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    toastr.error("Status: " + textStatus); 
                    toastr.error("Error: " + errorThrown); 
                } 
            })
        }

        const chartPresence = () => {
            var chartDom = document.getElementById('attendance');
            var myChart = echarts.init(chartDom);
            var option;

            $.ajax({
                url: "<?= base_url('attendance/listPerYear') ?>",
                type: "GET",
                dataType: "JSON",
                success:function(res)
                {

                    let valueMonth = $.map(res.kamarApps.results, function(v) {
                        return parseInt(v);
                    })

                    console.log(valueMonth)

                    option = {
                        grid: {
                            top: '6',
                            right: '0',
                            bottom: '17',
                            left: '25',
                        },
                        title:{
                            show:true,
                            text:'ATTENDANCES',
                            textStyle:{
                                fontWeight:'bolder'
                            }
                        },
                        tooltip: {
                            trigger: 'axis'
                        },
                        toolbox: {
                            show: true,
                            feature: {
                            magicType: {
                                type: ["line", "bar"]
                            },
                            saveAsImage: {}
                            }
                        },
                        xAxis: {
                            type: 'category',
                            data: ['Jan', 'Feb', 'March', 'Apr', 'May', 'June', 'July','Aug','Sept','Oct','Nov','Dec']
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: [{
                            data: valueMonth,
                            type: 'line'
                        }]
                    };
                    option && myChart.setOption(option);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    toastr.error("Status: " + textStatus); 
                    toastr.error("Error: " + errorThrown); 
                } 

            })

        }
    </script>
<?= $this->endSection() ?>