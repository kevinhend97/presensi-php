<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />
    <style>
        .header img{
            height:120px;
            width:120px;
            margin-bottom:7px;
        }
    </style>

  </head>
  <body>
    <div class="container p-5">
        <div class="row">
            <div class="float-left header">
                <div class="text-center">
                    <img src="<?= base_url('include/img/pnc.png') ?>" alt="">
                    <h4 class="text-center font-weight-bold">POLITEKNIK NEGERI CILACAP</h4>
                    <h6><?=$title?></h6>
                </div>
            </div>
        </div>
        <div class="row top mt-3 p-2">
            <table>
                <tr style="color:#fff;">
                    <th width="12%" style="background-color:#52b3e5; height:5px;padding:3px; margin-left:2px; text-align:left;font-size:12px;"><p style="font-size:12px;">DATE :<br><?= date('d M Y') ?></p>
                    </th>
                    <th width="40%" style="background-color:#ee9908; padding:3px; height:5px;text-align:center;"><p>PRESENCE REPORT</p></th>
                    <th width="12%" style="background-color:#ee9908; padding:3px; height:5px;text-align:center;"> <p class="text-center">HMT LING</p> </th>
                </tr>
                <tr>
                    <td style="padding:5px;font-size:12px;vertical-align:text-top;">
                        <p class="text-muted">
                            <b class="text-danger">Alamat</b><br>
                            Jalan Dokter Soetomo No.1, Karangcengis, Sidakaya, Kec. Cilacap Sel., Kabupaten Cilacap, Jawa Tengah 53212
                        </p>
                        <p class="text-muted">
                            <b class="text-danger">Telp</b><br>
                            (0282) 533329
                        </p>
                        <p class="text-muted">
                            <b class="text-danger">Website</b><br>
                            https://pnc.ac.id/
                        </p>
                    </td>
                    <td colspan="2" style="padding:15px;font-size:12px;vertical-align:text-top;margin-left:25px;">
                        <p class="text-muted">
                            <b class="text-danger">HMTLink</b><br>
                            HMT Ling merupakan organisasi mahasiswa yang dibentuk dan bernaung di Program Studi Teknik Lingkungan, Politeknik Negeri Cilacap.
                        </p>
                        <p class="text-muted">
                            <b class="text-danger">VISI HMTLink</b><br>
                            Menjadi organisasi yang profesional, amanah dan menjadi wadah pengembangan potensi mahasiswa Teknik Lingkungan dalam kehidupan global yang berprinsip pada pembangunan berkelanjutan, serta berkontribusi nyata terhadap bangsa indonesia.
                        </p>

                        <p class="text-muted">
                            <b class="text-danger">MISI HMTLink</b><br>
                            <ul>
                                <li>Melakukan kajian lingkungan secara obyektif, menyeluruh dan berkeadilan sebagai masukan yang akurat kepada Pimpinan dan warga Politeknik Negeri Cilacap serta masyarakat pada umumnya.</li>
                                <li>Menyelenggarakan pendidikan dan da’wah lingkungan kepada masyarakat dalam rangka peningkatan kesadaran dan kepedulian masyarakat.</li>
                                <li>Melakukan advokasi kepada masyarakat dan mendorong pemerintah pusat dan daerah dalam rangka pembuatan dan implementasi kebijakan lingkungan yang berkeadilan serta berkelanjutan.</li>
                                <li>Menjalin kerjasama yang setara dan bersinergi dengan majelis dan/atau lembaga internal Muhammadiyah dan institusi lingkungan di dalam maupun di luar negeri dalam rangka pengembangan dan keberlanjutan lingkungan.</li>
                            </ul>
                        </p>
                       
                        <p class="text-muted">
                            <b class="text-danger">GRAFIK KEANGGOTAAN</b><br>
                           
                            <table>
                                <tr>
                                    <td>
                                        <div id="member" style="width:400px; height:210px;"></div>
                                    </td>
                                    <td>
                                        <div id="chart" style="width:400px; height:210px;"></div>
                                    </td>
                                </tr>
                            </table>    
                               
                        </p>

                        <p>
                            <b class="text-danger">KEGIATAN</b><br>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Event Date</th>
                                        <th>Event</th>
                                        <th>Visitor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($visitor): ?>
                                        <?php
                                            $arrEvents = array(); 
                                        ?>
                                        <?php foreach($visitor as $visitor): ?>
                                            <?php
                                                if($visitor->presence > 0)
                                                {
                                                    $dataPush = array(
                                                        "value" => $visitor->presence,
                                                        "name"  => $visitor->event_name,
                                                        "label" => array(
                                                            "align" =>'center',
                                                            "formatter" => '{b} | ({c} Orang)'
                                                        )  
                                                    );
        
                                                    array_push($arrEvents, $dataPush);
                                                }
                                            ?>
                                        <tr>
                                            <td><?= date('d M Y', strtotime($visitor->date)) ?></td>
                                            <td><?= $visitor->event_name ?></td>
                                            <td><?= $visitor->presence ?></td>
                                        </tr>
                                        <?php endforeach ?>

                                        <?php 
                                            $encodeToJson = json_encode($arrEvents);
                                        ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="3" style="text-align:center"><p class="text-danger">Sorry No Event for this Range</p></td>
                                    </tr>
                                    <?php endif ?>
                                </tbody>
                            </table>
                        </p>

                        <p>
                            <b class="text-danger">ABSENSI ANGGOTA</b><br>
                            <p>
                                <span class="bg-danger text-light">&nbsp;SICK&nbsp;</span>
                                <span class="bg-primary text-light">&nbsp;PRESENCE&nbsp;</span>
                                <span class="bg-secondary text-light">&nbsp;NONE&nbsp;</span>
                                <span class="bg-warning text-light">&nbsp;ATTENDANCE&nbsp;</span>
                            </p>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                    <?php foreach($events as $event): ?>
                                    <th><?= $event->event_name ?></th>
                                    <?php endforeach ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($users as $user): ?>
                                        
                                        <tr>
                                            <td><?= $user->name ?></td>
                                            <?php foreach($list[$user->name] as $presence): ?>
                                                <?php if($presence == "none"): ?>
                                                    <td class="bg-light"></td>
                                                <?php elseif($presence == "PRESENCE"): ?>
                                                    <td class="bg-primary"></td>
                                                <?php elseif($presence == "SICK"): ?>
                                                    <td class="bg-warning"></td>
                                                <?php elseif($presence == "ALPHA"): ?>
                                                    <td class="bg-danger"></td>
                                                <?php else: ?>
                                                    <td class="bg-warning"></td>
                                                <?php endif ?>
                                            <?php endforeach ?>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->
     <!-- Jquery -->
     <script src="<?= base_url('include/plugins') ?>/jquery.js"></script>
     <!-- Lodash -->
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.13.1/lodash.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/echarts@5.1.1/dist/echarts.min.js" integrity="sha256-Yhe8c0oOs2dPYVQKLAi1aBB9uhr7yMmh67ukoWBqDuU=" crossorigin="anonymous"></script>


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
        $(() => {
            chartMember()
            chartPie()
        })

        let promiseCetak = new Promise((resolve, reject) => {
            setTimeout(() => {
                if (chartMember() === true && chartPie() === true) {
                    resolve("OK")
                }  else {
                    reject("Anda tidak bernyanyi")
                }
            }, 1500)
        })

        promiseCetak.then((result) => {
            window.print();
        }).catch((error) => {
            alert(error)
        })

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
                        tooltip: {
                            trigger: 'item'
                        },
                        series: [
                            {
                                name: 'MEMBER',
                                type: 'pie',
                                radius: ['50%', '67%'],
                                center: ['50%', '50%'],
                                data: [
                                    {
                                        value: male.length, 
                                        name: 'Male',
                                        label:{
                                            align:'center',
                                            formatter: '{b}\n({c} Orang)'
                                        }
                                    },
                                    {
                                        value: female.length, 
                                        name: 'Female',
                                        label:{
                                            align:'center',
                                            formatter: '{b}\n({c} Orang)'
                                        }
                                    },
                                ]
                            }
                        ]
                    };

                    optionMember && myChartMember.setOption(optionMember);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    toastr.error("Status: " + textStatus); 
                    toastr.error("Error: " + errorThrown); 
                } 
            })

            return true;
        }

        const chartPie = () =>{
            var chart = document.getElementById('chart');
            var myChart = echarts.init(chart);
            var option;

            option = {
                series: [
                    {
                        name: '访问来源',
                        type: 'pie',
                        radius: ['50%', '67%'],
                        center: ['45%', '50%'],
                        data: <?=$encodeToJson?>
                        
                    }
                ]
            };
            option && myChart.setOption(option);

            return true;
        
        }
    </script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    -->
  </body>
</html>