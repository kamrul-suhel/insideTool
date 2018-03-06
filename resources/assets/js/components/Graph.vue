<template>
    <canvas :id="id"></canvas>  
</template>

<script>
    import Chart from 'chart.js';

    export default Vue.extend({
        props:{
            id: '',
            type: '',
            birth: '',
            postId: '',
            fields: '',
        },

        data(){
            return {
                chart: false,
                errors: [],
                defaultTitle: this.title
            };
        },

        mounted(){
            this.getData();
        },

        methods: {
            getData(){
                axios.get('/posts/'+this.postId+'/snapshots/'+this.type+'/'+this.birth, {
                    params:{
                        fields: this.fields
                    }
                })
                .then(response => {
                    this.render(response.data);
                })
                .catch(e => {
                    this.errors.push(e)
                });
            },

            render(data){
                var context = document.getElementById(this.id).getContext('2d');

                if(this.chart){
                    this.chart.data.datasets[0].data = values;
                    this.chart.data.labels = labels;
                    this.chart.update();
                }else{
                    this.chart = new Chart(context, {
                        type: 'line',
                        data:{
                            datasets: data
                        },
                        options: {
                            elements: {
                                point: {
                                    radius: 0
                                } 
                            },
                            scales: {
                                xAxes: [{
                                    type: "time"
                                }]
                            },
                            hover: {
                                intersect: false
                            },
                            tooltips: {
                                mode: 'index',
                                intersect: false
                            },
                            legend: {
                                labels: {
                                    fontStyle: 'bold'
                                }
                            }
                        }
                    });
                }
            }
        },

        events: {
            '_getData': function(){
                this.getData();
            },

            'setSingleGraphItem': function(id, title){
                this.title = title ? title : this.defaultTitle;

                this.getData();
            }
        }
    });
</script>