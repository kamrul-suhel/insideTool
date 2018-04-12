<template>
        <span>
            <td>
                <span :class="'badge ' + likesClass"><i class="fa fa-thumbs-up"></i> {{ likes | number_format }}</span>
            </td>
            <td>
                <span :class="'badge ' + commentsClass"><i class="fa fa-comment"></i> {{ comments | number_format }}</span>
            </td>
            <td>
                <span :class="'badge ' + sharesClass"><i class="fa fa-share"></i> {{ shares | number_format }}</span>
            </td>
        </span>
</template>

<script>
    export default Vue.extend({
        props:{
            postId: 0,
            postLikes: 0,
            postShares: 0,
            postComments: 0,
            defaultClass: 'bg-gray'
        },
        
        filters: {
            number_format(number) {
                if(number){
                    return number.toLocaleString('en');
                }
            }
        },

        mounted(){
            this.getData();

            setInterval(function () {
                this.getData();
            }.bind(this),5000);

        },

        data(){
            return {
                errors: [],
                likes: this.postLikes,
                shares: this.postShares,
                comments: this.postComments,
                likesClass: this.defaultClass,
                sharesClass: this.defaultClass,
                commentsClass: this.defaultClass
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
                    if (this.likes > $('.averages').data('average-likes')) {
                        this.likesClass = 'bg-green';
                    } else {
                        this.likesClass = 'bg-red';
                    }
                    this.comments = response.data.comments;
                    if (this.comments > $('.averages').data('average-comments')) {
                        this.commentsClass = 'bg-green';
                    } else {
                        this.commentsClass = 'bg-red';
                    }
                    this.shares = response.data.shares;
                    if (this.shares > $('.averages').data('average-shares')) {
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