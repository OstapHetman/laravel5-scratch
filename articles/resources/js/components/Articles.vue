<template>
  <div>
    <h2>Articles</h2>
    <form class="mb-4" @submit.prevent="addArticle">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Title" v-model="article.title">
      </div>
      <div class="form-group">
        <textarea class="form-control" placeholder="Body text" v-model="article.body"></textarea>
      </div>
      <button class="btn btn-success" type="submit">Save</button>
    </form>
    <ul class="pagination">
      <li :class="[{ disabled: !pagination.prev_page_url }]" class="page-item">
        <a href="#" class="page-link" @click="fetchArticles(pagination.prev_page_url)">Prev</a>
      </li>

    <li class="page-item disabled">
      <a href="#" class="page-link text-dark">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a>
    </li>

      <li :class="[{ disabled: !pagination.next_page_url }]" class="page-item">
        <a href="#" class="page-link" @click="fetchArticles(pagination.next_page_url)">Next</a>
      </li>
    </ul>
    <div class="card card-body mb-3" v-for="article in articles" :key="article.id">
      <h3>{{ article.title }}</h3>
      <p>{{ article.body }}</p>
      <hr>
      <div class="d-flex">
        <button class="btn btn-danger" @click="onDelete(article.id)">Delete</button>
        <button class="btn btn-warning ml-2" @click="onEdit(article)">Edit</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      articles: [],
      article: {
        id: "",
        title: "",
        body: ""
      },
      article_id: "",
      pagination: {},
      edit: false
    };
  },
  created() {
    this.fetchArticles();
  },
  methods: {
    fetchArticles(page_url) {
      let vm = this;
      page_url = page_url || "/api/articles";
      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          this.articles = res.data;
          vm.makePagination(res.meta, res.links);
        })
        .catch(err => console.log(err));
    },
    makePagination(meta, links) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        next_page_url: links.next,
        prev_page_url: links.prev
      };

      this.pagination = pagination;
    },
    onDelete(id) {
      if (confirm("Are You Sure?")) {
        fetch(`api/article/${id}`, {
          method: "delete"
        })
          .then(res => res.json())
          .then(data => {
            alert("Article Removed");
            this.fetchArticles();
          })
          .catch(err => console.log(err));
      }
    },
    addArticle() {
      if (this.edit === false) {
        // Add
        fetch("api/article", {
          method: "post",
          body: JSON.stringify(this.article),
          headers: {
            "content-type": "application/json"
          }
        })
          .then(res => res.json())
          .then(data => {
            this.article.title = "";
            this.article.body = "";
            this.fetchArticles();
          })
          .catch(err => console.log(err));
      } else {
        // Update
        fetch("api/article", {
          method: "put",
          body: JSON.stringify(this.article),
          headers: {
            "content-type": "application/json"
          }
        })
          .then(res => res.json())
          .then(data => {
            this.article.title = "";
            this.article.body = "";
            alert("Article updated!");
            this.fetchArticles();
          })
          .catch(err => console.log(err));
      }
    },
    onEdit(article) {
      this.edit = true;
      this.article.id = article.id;
      this.article.article_id = article.id;
      this.article.title = article.title;
      this.article.body = article.body;
    }
  }
};
</script>