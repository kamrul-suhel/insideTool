<template>
    <div :class="'info-box info-box-graph'">
        <span :class="'info-box-icon bg-'+color"><i :class="'fa fa-'+faIcon"></i></span>
        <div class="info-box-content">
            <div class="">
                <canvas :id="id" height="80"></canvas> 
            </div>
        </div>
        <div :class="'info-box-right average-indicator bg-'+color+' '+(this.is_under ? 'negative':'positive')">
            <span :class="'info-box-number info-box-number-medium icon-roi '+(this.is_under ? 'negative':'positive')">
                <span></span>{{ this.dayPercentage != 0 ? -Math.round((1-this.actual/(this.target*this.dayPercentage))*100) : Math.round((1-this.actual/(this.target))) }}%
            </span>    
        </div> 
    </div>
</template>

<script>
    export default Vue.extend({
        props:{
            id: '',
            max: '',
            label: '',
            target: '',
            actual: '',
            faIcon: '',
            color: '',
            dayPercentage: ''
        },

        data(){
            return {
                chart: false,
                errors: []
            };
        },

        mounted(){
            this.render();
        },

        computed: {
            is_under: function () {
                return this.actual<(this.dayPercentage*this.target);
            }
        },

        methods: {
            render(){
                var context = document.getElementById(this.id).getContext('2d');
                var color = this.is_under ? 'rgb(215, 85, 85)' : 'rgba(110, 215, 85, 0.5)';
                var target_color = this.is_under ? 'rgba(255, 255, 255, 0.5)' : 'rgb(110, 215, 85)';

                new Chart(context,{
                    "type":"horizontalBar",
                    "data":{
                        "datasets":[{
                            "data":[
                                (this.is_under ? this.actual : (this.target*this.dayPercentage))
                            ],
                            "fill":false,
                            "backgroundColor":color,
                            "borderWidth":1
                        },
                        {
                            "data":[
                                (this.is_under ? (this.target*this.dayPercentage)-this.actual : this.actual-(this.target*this.dayPercentage))
                            ],
                            "fill":false,
                            "backgroundColor":target_color,
                            "borderWidth":1
                        }]
                    },
                    "options":{
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        tooltips: {
                             enabled: false
                        },
                        "scales":{
                            "xAxes": [{
                                stacked: true,
                                gridLines: {
                                    color: "rgba(0, 0, 0, 0)",
                                },
                                ticks:{
                                    max: this.max,
                                    beginAtZero:true,
                                    callback : function (value, index, values) {
                                        return value.toLocaleString('en')
                                    }
                                }
                            }],
                            "yAxes":[{
                                stacked: true,
                                gridLines: {
                                    color: "rgba(0, 0, 0, 0)",
                                },
                                ticks:{
                                    beginAtZero:true
                                }
                            }]
                        },
                        annotation: {
                            annotations: [
                                {
                                    type: "line",
                                    mode: "vertical",
                                    scaleID: "x-axis-0",
                                    value: this.target,
                                    borderColor: "rgba(0,0,0,0.5)",
                                }
                            ]
                        }
                    }
                });
            }
        }
    });
</script>