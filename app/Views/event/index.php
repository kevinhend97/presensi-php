<?= $this->extend('layout/index') ?>

<?= $this->section('css') ?>
<link rel="stylesheet" href="<?=base_url('include/css')?>/app.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid" id="app">
    <div class="card text-muted">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <h1 class="text-muted">EVENT</h1>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="">Event Name</label>
                                <input type="text" placeholder="Search by Event Name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="">Location</label>
                                <input type="text" placeholder="Search by Event Location" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <div class="form-group">
                                <label for="">Date</label>
                                <input type="text" placeholder="Search by Event Date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                      
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <h1 class="text-muted">FORM ADD / EDIT EVENT</h1>
                    <div class="row">
                        <div class="container">
                           <div class="card">
                               <div class="card-body">
                                   <form>
                                       <div class="form-group">
                                           <label for="exampleInputEmail1">Event Name</label>
                                           <input type="text" class="form-control" v-model="eventName" placeholder="Enter Event Name">
                                       </div>
   
                                       <div class="form-group">
                                           <label for="exampleInputEmail1">Location</label>
                                           <input type="text" class="form-control" v-model="location" placeholder="Enter Location">
                                       </div>
   
                                       <div class="form-group">
                                           <label for="exampleInputEmail1">Description</label>
                                           <textarea name="" class="form-control" v-model="description" placeholder="Enter Description"></textarea>
                                       </div>
   
                                       <div class="form-group">
                                           <label for="exampleInputEmail1">Date</label>
                                           <input type="text" class="form-control" v-model="date" placeholder="Enter Date">
                                       </div>
   
                                       <div class="form-group">
                                           <label for="exampleInputEmail1">Start Time</label>
                                           <input type="text" class="form-control" v-model="startTime" placeholder="Enter Start Time">
                                       </div>
   
                                       <div class="form-group">
                                           <label for="exampleInputEmail1">End Time</label>
                                           <input type="text" class="form-control" v-model="endTime" placeholder="Enter End Time">
                                       </div>
                                       
                                       <button @click="saveEvent" type="button" class="btn btn-block btn-info">SUBMIT</button>
                                   </form>
                               </div>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        new Vue({
            el: '#app',
            data(){
                return{
                    events: '',
                    eventName: '',
                    location:'',
                    description:'',
                    date:'',
                    startTime:'',
                    endTime:''
                }
            },
            watch:{
                eventName(v){
                    console.log(v);
                }
            },
            methods: {
                saveEvent: function(){
                    axios.post('<?= base_url('event/store') ?>',{
                        eventName: this.eventName,
                        location: this.location,
                        description: this.desciption,
                        date: this.date,
                        startTime: this.startTime,
                        endTime: this.endTime
                    }).then(response => {
                        console.log(response);
                    }).catch(err => {
                        // handle error
                        console.log(err);
                    })
                }
            }
        })
    </script>
</div>
<?= $this->endSection() ?>