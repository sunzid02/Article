<template>
    <div class="container">
        <h2>Articles</h2>

        <!-- create article -->
        <form @submit.prevent="addArticle" class="mb-3">
            <!-- title -->
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Title"  v-model="article.title">
            </div>

            <!-- body -->
            <div class="form-group">
                <textarea type="text" class="form-control" placeholder="body"  v-model="article.body"></textarea>
            </div>      

            <button type="submit" class="btn btn-light btn-block">Save</button>      
        </form>

        <br><br>
        <!-- pagination -->
        <nav aria-label="Page navigation example mt-2">
            <ul class="pagination">
                <li   v-bind:class="[{ disabled: !pagination.prev_page_url }]"  class="page-item">
                    <a class="page-link" href="#"
                        @click="fetchArticles(pagination.prev_page_url)"
                    >
                        Previous
                    </a>
                </li>
                
                
                
                <li class="page-item disabled">
                    <a class="page-link text-dark" href="#">
                        Page {{ pagination.current_page }} of {{ pagination.last_page }}
                    </a>
                </li>


                <li   v-bind:class="[{ disabled: !pagination.next_page_url }]"  class="page-item">
                    <a class="page-link" href="#"
                        @click="fetchArticles(pagination.next_page_url)"
                    >
                        Next
                    </a>
                </li>                


            </ul>
        </nav>


        <!-- show articles -->
        <div class="card card-body mb-3" 
            v-for="article in articles" 
            v-bind:key="article.id"
        >
            <h3> {{ article.id }}. {{ article.title }} </h3>
            <p> {{ article.body }} </p>

            <!-- edit article -->
            <button class="btn btn-warning mb-2" 
              @click="editArticle(article, pagination.current_page)"
            > 
                Edit 
            </button>



            <!-- delete article -->
            <button class="btn btn-danger" 
              @click="deleteArticle(article.id, pagination.current_page)"
            > 
                Delete 
            </button>




        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('article Component mounted.')
        },

        data() {
            return {
                articles: [],

                article: {
                    id: '',
                    title: '',
                    body: ''
                },
                
                article_id: '',
                pagination: {},
                edit: false,
            }
        },

        created() {
            this.fetchArticles();
        },

        methods: {
            fetchArticles(page_url){

                page_url = page_url || '/api/articles';

                fetch(page_url)
                    .then( res => res.json())
                    .then( res => {
                        // console.log(res);
                        this.articles = res.data;
                        this.makePagination( res.meta, res.links );
                    })
                    .catch( err => console.log(err) );
            },

            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev
                };

                this.pagination = pagination;

                // console.log(pagination);
                
            },

            deleteArticle( articleId, currentPage ){
                
                if (confirm("ok to delete")) 
                {
                    fetch(  `api/article/${articleId}`,{
                        method: 'delete'
                    })
                    .then( res => res.json())
                    .then( res => {
                        // console.log(res);
                        alert(' Article removed ');
                        this.fetchArticles( 'api/articles?page='+currentPage );
                    })
                    .catch( err => console.log(err) );
                    
                }

            },

            editArticle( article, currentPage ){
                
                this.edit = true;
                this.article.id = article.id;
                this.article.title = article.title;
                this.article.body = article.body;

                window.scrollTo(0,0);
                
                // alert(article.id);
 
                

            },

            addArticle(){

                if (this.edit == false) 
                {
                    console.log(JSON.stringify(this.article));

                    fetch('api/article', {
                        method: 'post',
                        body: JSON.stringify(this.article),
                        headers:{
                            'content-type': 'application/json'
                        }
                    })
                    .then( res => res.json())
                    .then( res => {
                        // console.log(res);
                        this.article.id = '';
                        this.article.title = '';
                        this.article.body = '';
                        alert('Article created');

                        this.fetchArticles();
                    })
                    .catch( err => console.log(err) );                
                    
                }
                else
                {
                    console.log(JSON.stringify(this.article));
                    
                    //api call to update
                    fetch( 'api/article', {
                        method: 'put',
                    
                        body: JSON.stringify(this.article),

                        headers:{
                            'content-type': 'application/json'
                        }
                    })
                    .then( res => res.json())
                    .then( res => {
                        // console.log(res);
                        this.article.id = '';
                        this.article.title = '';
                        this.article.body = '';
                        alert('Article updated');

                        this.fetchArticles();
                    })
                    .catch( err => console.log(err) ); 
                }
                
            },


        },
    }
</script>
