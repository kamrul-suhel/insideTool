<template>
        <td>
            <span :class="'badge ' + likesClass"><i class="fa fa-thumbs-up"></i> {{ likes }}</span>
            <span :class="'badge ' + commentsClass"><i class="fa fa-comment"></i> {{ comments }}</span>
            <span :class="'badge ' + sharesClass"><i class="fa fa-share"></i> {{ shares }}</span>
        </td>
</template>

<script>
    export default Vue.extend({
        props:{
            postId: 0,
            likes: '',
            shares: '',
            comments: ''
        },

        mounted(){
            this.getData();

            setInterval(function () {
                this.getData();
            }.bind(this),5000);

        },

        data(){
            return {
                errors: []
            };
        },

        methods: {
            getData(){
                axios.get('/posts/'+this.postId+'/snapshots/latest/false', {
                    params:{
                        fields: this.fields
                    }
                })
                .then(response => {
                    this.likes = response.data.likes;
                    if (this.likes > $('#app').data('average-likes')) {
                        this.likesClass = 'bg-green';
                    } else {
                        this.likesClass = 'bg-red';
                    }
                    this.comments = response.data.comments;
                    if (this.comments > $('#app').data('average-comments')) {
                        this.commentsClass = 'bg-green';
                    } else {
                        this.commentsClass = 'bg-red';
                    }
                    this.shares = response.data.shares;
                    if (this.shares > $('#app').data('average-shares')) {
                        this.sharesClass = 'bg-green';
                    } else {
                        this.sharesClass = 'bg-red';
                    }
                })
                .catch(e => {
                    this.errors.push(e)
                });
            },
        }
    });
</script>